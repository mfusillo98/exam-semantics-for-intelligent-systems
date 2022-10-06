-- Ingredienti con almeno 1 valore di CO2
select COUNT(DISTINCT text)
from `1m_recipes_ingredients`
where co2_min_by_hints_similarity IS NOT NULL
   OR co2_direct_my_emission IS NOT NULL
   OR co2_mean_groups IS NOT NULL;


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
