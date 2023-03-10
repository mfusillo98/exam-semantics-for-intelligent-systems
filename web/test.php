<?php
require_once __DIR__ . '/php/FuxFramework/bootstrap.php';

set_time_limit(0);

$recipes = \App\Models\RecipesModel::queryBuilder()->whereNull("image_url")->whereNotNull("static_score")->execute();
$web = new \Spekulatius\PHPScraper\PHPScraper;
$web->setConfig(['disable_ssl' => true]);
$web->setConfig([
    'agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:107.0) Gecko/20100101 Firefox/107.0'
]);
echo "OK";
foreach ($recipes as $r) {
    $web->go($r['url']);
    $image_url = ($web->openGraph['og:image'] ?? null) ?: ($web->twitterCard['twitter:image'] ?? null);
    if ($image_url) {
        \App\Models\RecipesModel::save([
            "recipe_id" => $r["recipe_id"],
            "image_url" => $image_url
        ]);
        print_r_pre($image_url);
    }
}