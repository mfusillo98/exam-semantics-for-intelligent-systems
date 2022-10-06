-- Ingredienti con almeno 1 valore di CO2
select COUNT(DISTINCT text)
from `1m_recipes_ingredients`
where co2_min_by_hints_similarity IS NOT NULL
   OR co2_direct_my_emission IS NOT NULL
   OR co2_mean_groups IS NOT NULL;

-- Ingredienti con almeno 1 valore di H20
select COUNT(DISTINCT text)
from `1m_recipes_ingredients`
where h20_liters_per_kg_direct_healabel IS NOT NULL;

-- Ricette totali
SELECT 'Ricette totali' as description, COUNT(*) FROM `1m_recipe`
UNION
-- Ricette con almeno 80% di ingredienti collegati ad edamam
SELECT 'Ricette 80% ingr.' as description, COUNT(*)
FROM (
         SELECT r.`1m_recipe_id`,
                m.total,
                v.valid_ingredients,
                ROUND(v.valid_ingredients / m.total * 100, 2) as valid_ingredients_perc
         FROM `1m_recipe` r
                  JOIN (SELECT MAX(ingredient_idx) as total, `1m_recipe_id`
                        from `1m_recipes_ingredients`
                        GROUP BY `1m_recipe_id`) m ON m.`1m_recipe_id` = r.`1m_recipe_id`
                  JOIN (SELECT COUNT(*) as valid_ingredients, `1m_recipe_id`
                        from `1m_recipes_ingredients`
                        Where edamam_food_id IS NOT NULL
                        GROUP BY `1m_recipe_id`) v ON v.`1m_recipe_id` = r.`1m_recipe_id`
     ) t
WHERE t.valid_ingredients_perc >= 80
UNION
-- Ricette con almeno 90% di ingredienti collegati ad edamam
SELECT 'Ricette 90% ingr.' as description, COUNT(*)
FROM (
         SELECT r.`1m_recipe_id`,
                m.total,
                v.valid_ingredients,
                ROUND(v.valid_ingredients / m.total * 100, 2) as valid_ingredients_perc
         FROM `1m_recipe` r
                  JOIN (SELECT MAX(ingredient_idx) as total, `1m_recipe_id`
                        from `1m_recipes_ingredients`
                        GROUP BY `1m_recipe_id`) m ON m.`1m_recipe_id` = r.`1m_recipe_id`
                  JOIN (SELECT COUNT(*) as valid_ingredients, `1m_recipe_id`
                        from `1m_recipes_ingredients`
                        Where edamam_food_id IS NOT NULL
                        GROUP BY `1m_recipe_id`) v ON v.`1m_recipe_id` = r.`1m_recipe_id`
     ) t
WHERE t.valid_ingredients_perc >= 90
UNION
-- Ricette con 100% di ingredienti collegati ad edamam
SELECT 'Ricette 100% ingr.' as description, COUNT(*)
FROM (
         SELECT r.`1m_recipe_id`,
                m.total,
                v.valid_ingredients,
                ROUND(v.valid_ingredients / m.total * 100, 2) as valid_ingredients_perc
         FROM `1m_recipe` r
                  JOIN (SELECT MAX(ingredient_idx) as total, `1m_recipe_id`
                        from `1m_recipes_ingredients`
                        GROUP BY `1m_recipe_id`) m ON m.`1m_recipe_id` = r.`1m_recipe_id`
                  JOIN (SELECT COUNT(*) as valid_ingredients, `1m_recipe_id`
                        from `1m_recipes_ingredients`
                        Where edamam_food_id IS NOT NULL
                        GROUP BY `1m_recipe_id`) v ON v.`1m_recipe_id` = r.`1m_recipe_id`
     ) t
WHERE t.valid_ingredients_perc >= 100;



-- ---------------------------
-- DESCRIZIONE        | TOTAL |
-- ---------------------------
-- Ricette totali     | 51235 |
-- ---------------------------
-- Ricette 80% ingr.  | 48714 |
-- ---------------------------
-- Ricette 90% ingr.  | 44853 |
-- ---------------------------
-- Ricette 100% ingr. | 44061 |
-- ---------------------------


-- Ricette con almeno un valore di H2O (12683)
SELECT COUNT(with2o.1m_recipe_id)
FROM (
    SELECT r.1m_recipe_id, MAX(i.h20_liters_per_kg_direct_healabel) h20
    FROM `1m_recipe` r
    LEFT JOIN `1m_recipes_ingredients` i ON r.1m_recipe_id = i.1m_recipe_id
    GROUP BY r.1m_recipe_id
) with2o
WHERE with2o.h20 IS NOT NULL


-- Ricette con almeno un valore di CO2 (19552)
-- TODO: (DA rieseguire perché non ho dati completi)
SELECT COUNT(withco2.1m_recipe_id)
FROM (
    SELECT r.1m_recipe_id,
    MAX(i.co2_direct_my_emission) co2_1, MAX(i.co2_min_by_hints_similarity) co2_2,
    MAX(i.co2_max_by_hints_similarity) co2_3, MAX(i.co2_mean_by_hints_similarity) co2_4,
    MAX(i.co2_mean_max_freq_class_by_hints_similarity) co2_5, MAX(i.co2_raw_data_hints_similarity) co2_6,
    MAX(i.co2_mean_groups) co2_7
    FROM `1m_recipe` r
    LEFT JOIN `1m_recipes_ingredients` i ON r.1m_recipe_id = i.1m_recipe_id
    GROUP BY r.1m_recipe_id
) withco2
WHERE withco2.co2_1 IS NOT NULL OR withco2.co2_2 IS NOT NULL OR withco2.co2_3 IS NOT NULL OR withco2.co2_4 IS NOT NULL
OR withco2.co2_5 IS NOT NULL OR withco2.co2_6 IS NOT NULL OR withco2.co2_7 IS NOT NULL


-- Ricette che hanno almeno un valore di CO2 ED HANNO ANCHE un valore di H20 (12683)
-- TODO: (DA rieseguire perché non ho dati completi)
SELECT COUNT(withco2.1m_recipe_id)
FROM (
    SELECT r.1m_recipe_id,
    MAX(i.co2_direct_my_emission) co2_1, MAX(i.co2_min_by_hints_similarity) co2_2,
    MAX(i.co2_max_by_hints_similarity) co2_3, MAX(i.co2_mean_by_hints_similarity) co2_4,
    MAX(i.co2_mean_max_freq_class_by_hints_similarity) co2_5, MAX(i.co2_raw_data_hints_similarity) co2_6,
    MAX(i.co2_mean_groups) co2_7
    FROM `1m_recipe` r
    LEFT JOIN `1m_recipes_ingredients` i ON r.1m_recipe_id = i.1m_recipe_id
    GROUP BY r.1m_recipe_id
) withco2
LEFT JOIN (
    SELECT r.1m_recipe_id, MAX(i.h20_liters_per_kg_direct_healabel) h20
    FROM `1m_recipe` r
    LEFT JOIN `1m_recipes_ingredients` i ON r.1m_recipe_id = i.1m_recipe_id
    GROUP BY r.1m_recipe_id
) with2o ON withco2.1m_recipe_id = with2o.1m_recipe_id
WHERE (withco2.co2_1 IS NOT NULL OR withco2.co2_2 IS NOT NULL OR withco2.co2_3 IS NOT NULL OR withco2.co2_4 IS NOT NULL
OR withco2.co2_5 IS NOT NULL OR withco2.co2_6 IS NOT NULL OR withco2.co2_7 IS NOT NULL) AND with2o.h20 IS NOT NULL;