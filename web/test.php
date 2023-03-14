<?php
require_once __DIR__ . '/php/FuxFramework/bootstrap.php';

set_time_limit(0);

function getMetaTags($str)
{
    $pattern = '
  ~<\s*meta\s

  # using lookahead to capture type to $1
    (?=[^>]*?
    \b(?:name|property|http-equiv)\s*=\s*
    (?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|
    ([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))
  )

  # capture content to $2
  [^>]*?\bcontent\s*=\s*
    (?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|
    ([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))
  [^>]*>

  ~ix';

    if(preg_match_all($pattern, $str, $out))
        return array_combine($out[1], $out[2]);
    return array();
}

$recipes = \App\Models\RecipesModel::queryBuilder()->whereNull("image_url")->whereNotNull("static_score")->execute();
$web = new \Spekulatius\PHPScraper\PHPScraper;
//$web->setConfig(['disable_ssl' => true]);
//$web->setConfig(['agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:107.0) Gecko/20100101 Firefox/107.0']);

foreach ($recipes as $r) {
    $metatags = getMetaTags(file_get_contents($r['url']));
    if (!$metatags) {
        \App\Models\RecipesModel::save([
            "recipe_id" => $r["recipe_id"],
            "image_url" => "404"
        ]);
        continue;
    }
    $image_url = ($metatags['og:image'] ?? null) ?: ($metatags['twitter:image'] ?? null);
    if ($image_url) {
        \App\Models\RecipesModel::save([
            "recipe_id" => $r["recipe_id"],
            "image_url" => $image_url
        ]);
        print_r_pre($image_url);
    }
}