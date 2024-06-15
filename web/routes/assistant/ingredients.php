<?php


/**
 * The aim of this script is to update the ingredient score of CFP e WFP values, and then compute general score which is
 * the mean of both of them.
 */

\Fux\Routing\Routing::router()->get('/assistant/ingredients/assign-score', function () {


    /**
     * @return array{min: float, max:float}
     */
    function getMinMax($column): array
    {
        return (new \Fux\FuxQueryBuilder())
            ->select("MIN($column) as 'min'", "MAX($column) as 'max'")
            ->from(\App\Models\IngredientsModel::class)
            ->whereNotNull($column)
            ->first();
    }

    function makeClassesIntervals($min, $max, $intervalsNum)
    {
        $range = $max - $min;
        $intervalWidth = $range / $intervalsNum;
        $intervals = [];
        for ($i = 0; $i < $intervalsNum; $i++) {
            $start = $min + ($i * $intervalWidth);
            $end = $start + $intervalWidth;
            $intervals[] = [$start, $end];
        }
        $intervals[count($intervals) - 1][1] += 0.1; //to make sure the last interval value is included

        return $intervals;
    }


    $classesNum = 5;
    $cols = ['carbon_foot_print' => 'cfp_score', 'water_foot_print' => 'wfp_score'];

    \Fux\DB::ref()->begin_transaction();
    foreach ($cols as $sourceCol => $scoreCol) {
        $minMax = getMinMax($sourceCol);
        $intervals = makeClassesIntervals($minMax['min'], $minMax['max'], $classesNum);
        foreach ($intervals as $i => $interval) {
            $qb = (new \Fux\FuxQueryBuilder())
                ->update(\App\Models\IngredientsModel::class)
                ->set($scoreCol, $i + 1)
                ->whereGreaterEqThan($sourceCol, $interval[0]);
            if ($i < $classesNum - 1) $qb->whereLowerThan($sourceCol, $interval[1]); //The last interval has no upper bound
            if (!$qb->execute()) {
                \Fux\DB::ref()->rollback();
                throw new \Exception("Error while updating $scoreCol");
            }
        }


        if(!(new \Fux\FuxQueryBuilder())
            ->update(\App\Models\IngredientsModel::class)
            ->set("score", "ROUND((" . implode("+", array_values($cols)) . ")/" . count($cols) . ",2)", true)
            ->execute()){
            \Fux\DB::ref()->rollback();
            throw new \Exception("Error while updating scores");
        }

    }

    \Fux\DB::ref()->commit();
    echo "OK";
});

