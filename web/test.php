<?php
require_once __DIR__ . '/php/FuxFramework/bootstrap.php';


outputTable(getClassesStats('carbon_foot_print', 5));
outputTable(getClassesStats('water_foot_print', 5));

/**
 * @var array $stats = [[
 *     "min" => 123,
 *     "max" => 123,
 *     "num" => 123,
 * ]]
*/
function outputTable($stats){
    echo "<table>";
    echo "<tr><th>min</th><th>max</th><th>num</th></tr>";
    foreach ($stats as $stat) {
        echo "<tr><td>$stat[min]</td><td>$stat[max]</td><td>$stat[num]</td></tr>";
    }
    echo "</table>";
}


function getClassesStats($column, $intervalsNum)
{
    $minMax = getMinMax($column);
    $intervals = makeClassesIntervals($minMax['min'], $minMax['max'], $intervalsNum);
    return getIntervalFrequency($intervals, $column);
}

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


function getIntervalFrequency($intervals, $valueColumn)
{
    $results = [];
    foreach ($intervals as $interval) {
        $num = \App\Models\IngredientsModel::getAggregateWhere(
            "COUNT",
            "ingredient_id",
            "$valueColumn is not null AND $valueColumn >= $interval[0] AND $valueColumn < $interval[1]",
        );
        $results[] = [
            "min" => $interval[0],
            "max" => $interval[1],
            "num" => $num
        ];
    }
    return $results;
}

