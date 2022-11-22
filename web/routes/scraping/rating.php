<?php

\Fux\Routing\Routing::router()->get('/scraping/ratings', function (\Fux\Request $request) {
    $recipes = \App\Models\RecipesModel::queryBuilder()
        ->where("disabled", 0)
        ->whereNull("rating")
        ->whereGreaterEqThan("trust_cfp", 0.75)
        ->whereGreaterEqThan("trust_wfp", 0.75)
        ->execute();

    foreach ($recipes as $r) {

        if (str_contains($r['url'], "food.com")) {
            continue; //TODO
            $html = file_get_contents($r['url']);
            $jsonld = json_decode(\App\Utils\StringUtils::getBetween($html, '<script type="application/ld+json">', '</script>'), true);
            if ($jsonld['aggregateRating']['ratingValue'] ?? null) {
                \App\Models\RecipesModel::save([
                    'recipe_id' => $r['recipe_id'],
                    'rating' => $jsonld['aggregateRating']['ratingValue'],
                    'rating_count' => $jsonld['aggregateRating']['reviewCount'],
                ]);
            }
        }elseif (str_contains($r['url'], "epicurious.com")) {
            continue;
            $html = file_get_contents($r['url']);
            $jsonld = json_decode(\App\Utils\StringUtils::getBetween($html, '<script type="application/ld+json">', '</script>'), true);
            if ($jsonld['aggregateRating']['ratingValue'] ?? null) {
                \App\Models\RecipesModel::save([
                    'recipe_id' => $r['recipe_id'],
                    'rating' => $jsonld['aggregateRating']['ratingValue'],
                    'rating_count' => $jsonld['aggregateRating']['reviewCount'],
                ]);
            }
        }elseif(str_contains($r['url'], "recipeland.com")){
            continue;
            $html = file_get_contents($r['url']);
            $ratingValue = \App\Utils\StringUtils::getBetween($html, '<meta itemprop="ratingValue" content="', '"') ?: null;
            $ratingCount = \App\Utils\StringUtils::getBetween($html, '<meta itemprop="ratingCount" content="', '"') ?: null;
            \App\Models\RecipesModel::save([
                'recipe_id' => $r['recipe_id'],
                'rating' => $ratingValue ?? null,
                'rating_count' => $ratingCount ?? null,
            ]);
        }else{
            $html = file_get_contents($r['url']);
            $jsonld = json_decode(\App\Utils\StringUtils::getBetween($html, '<script type="application/ld+json">', '</script>'), true);
            if ($jsonld['aggregateRating']['ratingValue'] ?? null) {
                \App\Models\RecipesModel::save([
                    'recipe_id' => $r['recipe_id'],
                    'rating' => $jsonld['aggregateRating']['ratingValue'],
                    'rating_count' => $jsonld['aggregateRating']['reviewCount'],
                ]);
            }else{
                $ratingValue = \App\Utils\StringUtils::getBetween($html, '<meta itemprop="ratingValue" content="', '"') ?: null;
                $ratingCount = \App\Utils\StringUtils::getBetween($html, '<meta itemprop="ratingCount" content="', '"') ?: null;
                \App\Models\RecipesModel::save([
                    'recipe_id' => $r['recipe_id'],
                    'rating' => $ratingValue ?? null,
                    'rating_count' => $ratingCount ?? null,
                ]);
            }
        }

    }
    print_r_pre($recipes);
});