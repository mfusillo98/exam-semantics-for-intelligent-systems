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



-- ----------------------------------
-- STIME RICETTE IN BASE ALLA CORRISPONDENZA CON EDAMAM
-- ----------------------------------

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


-- ----------------------------------
-- STIME RICETTE IN BASE A VALORI CO2
-- ----------------------------------

-- -----------------------------------
-- Ricette totali             | 51235 |
-- -----------------------------------
-- Ricette 80% ingr. con CO2  | 293   |
-- -----------------------------------
-- Ricette 90% ingr. con CO2  | 228   |
-- -----------------------------------
-- Ricette 100% ingr. con CO2 | 228   |
-- -----------------------------------

-- Ricette totali
SELECT 'Ricette totali' as description, COUNT(*) FROM `1m_recipe`
UNION
-- Ricette con almeno 80% di ingredienti con almeno 1 valore di CO2
SELECT 'Ricette 80% ingr. con CO2' as description, COUNT(*)
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
                        -- Codizione da cambiare per gli ingredienti da considerare "validi"
                        Where IFNULL(co2_direct_my_emission, IFNULL(co2_mean_by_hints_similarity, IFNULL(co2_mean_groups, NULL))) IS NOT NULL
                        GROUP BY `1m_recipe_id`) v ON v.`1m_recipe_id` = r.`1m_recipe_id`
     ) t
WHERE t.valid_ingredients_perc >= 80
UNION
-- Ricette con almeno 90% di ingredienti con almeno 1 valore di CO2
SELECT 'Ricette 90% ingr. con CO2' as description, COUNT(*)
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
                        Where IFNULL(co2_direct_my_emission, IFNULL(co2_mean_by_hints_similarity, IFNULL(co2_mean_groups, NULL))) IS NOT NULL
                        GROUP BY `1m_recipe_id`) v ON v.`1m_recipe_id` = r.`1m_recipe_id`
     ) t
WHERE t.valid_ingredients_perc >= 90
UNION
-- Ricette con 100% di ingredienti con almeno 1 valore di CO2
SELECT 'Ricette 100% ingr. con CO2' as description, COUNT(*)
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
                        Where IFNULL(co2_direct_my_emission, IFNULL(co2_mean_by_hints_similarity, IFNULL(co2_mean_groups, NULL))) IS NOT NULL
                        GROUP BY `1m_recipe_id`) v ON v.`1m_recipe_id` = r.`1m_recipe_id`
     ) t
WHERE t.valid_ingredients_perc >= 100;




-- ----------------------------------
-- STIME RICETTE IN BASE A VALORI H2O
-- ----------------------------------

-- -----------------------------------
-- Ricette totali             | 51235 |
-- -----------------------------------
-- Ricette 80% ingr. con H2O  | 22    |
-- -----------------------------------
-- Ricette 90% ingr. con H2O  | 22    |
-- -----------------------------------
-- Ricette 100% ingr. con H2O | 22    |
-- -----------------------------------

-- Ricette totali
SELECT 'Ricette totali' as description, COUNT(*) FROM `1m_recipe`
UNION
-- Ricette con almeno 80% di ingredienti con almeno 1 valore di H20
SELECT 'Ricette 80% ingr. con CO2' as description, COUNT(*)
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
                        -- Codizione da cambiare per gli ingredienti da considerare "validi"
                        Where IFNULL(h20_liters_per_kg_direct_healabel, NULL) IS NOT NULL
                        GROUP BY `1m_recipe_id`) v ON v.`1m_recipe_id` = r.`1m_recipe_id`
     ) t
WHERE t.valid_ingredients_perc >= 80
UNION
-- Ricette con almeno 90% di ingredienti con almeno 1 valore di H20
SELECT 'Ricette 90% ingr. con CO2' as description, COUNT(*)
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
                        Where IFNULL(h20_liters_per_kg_direct_healabel, NULL) IS NOT NULL
                        GROUP BY `1m_recipe_id`) v ON v.`1m_recipe_id` = r.`1m_recipe_id`
     ) t
WHERE t.valid_ingredients_perc >= 90
UNION
-- Ricette con 100% di ingredienti con almeno 1 valore di H20
SELECT 'Ricette 100% ingr. con CO2' as description, COUNT(*)
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
                        Where IFNULL(h20_liters_per_kg_direct_healabel, NULL) IS NOT NULL
                        GROUP BY `1m_recipe_id`) v ON v.`1m_recipe_id` = r.`1m_recipe_id`
     ) t
WHERE t.valid_ingredients_perc >= 100;



-- ----------------------------------
-- STIME RICETTE IN BASE A VALORI CO2 & H2O
-- ----------------------------------

-- -----------------------------------------
-- Ricette totali                   | 51235 |
-- -----------------------------------------
-- Ricette 80% ingr. con CO2 e H2O  | 22    |
-- -----------------------------------------
-- Ricette 90% ingr. con CO2 e H2O  | 22    |
-- -----------------------------------------
-- Ricette 100% ingr. con CO2 e H2O | 22    |
-- -----------------------------------------

-- Ricette totali
SELECT 'Ricette totali' as description, COUNT(*) FROM `1m_recipe`
UNION
-- Ricette con almeno 80% di ingredienti con almeno 1 valore di CO2 & H20
SELECT 'Ricette 80% ingr. con CO2 e H2O' as description, COUNT(*)
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
                        -- Codizione da cambiare per gli ingredienti da considerare "validi"
                        Where
                              IFNULL(co2_direct_my_emission, IFNULL(co2_mean_by_hints_similarity, IFNULL(co2_mean_groups, NULL))) IS NOT NULL AND
                              IFNULL(h20_liters_per_kg_direct_healabel, NULL) IS NOT NULL
                        GROUP BY `1m_recipe_id`) v ON v.`1m_recipe_id` = r.`1m_recipe_id`
     ) t
WHERE t.valid_ingredients_perc >= 80
UNION
-- Ricette con almeno 90% di ingredienti con almeno 1 valore di CO2 & H20
SELECT 'Ricette 90% ingr. con CO2 e H2O' as description, COUNT(*)
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
                        Where
                              IFNULL(co2_direct_my_emission, IFNULL(co2_mean_by_hints_similarity, IFNULL(co2_mean_groups, NULL))) IS NOT NULL AND
                              IFNULL(h20_liters_per_kg_direct_healabel, NULL) IS NOT NULL
                        GROUP BY `1m_recipe_id`) v ON v.`1m_recipe_id` = r.`1m_recipe_id`
     ) t
WHERE t.valid_ingredients_perc >= 90
UNION
-- Ricette con 100% di ingredienti con almeno 1 valore di CO2 & H20
SELECT 'Ricette 100% ingr. con CO2 e H2O' as description, COUNT(*)
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
                        Where
                              IFNULL(co2_direct_my_emission, IFNULL(co2_mean_by_hints_similarity, IFNULL(co2_mean_groups, NULL))) IS NOT NULL AND
                              IFNULL(h20_liters_per_kg_direct_healabel, NULL) IS NOT NULL
                        GROUP BY `1m_recipe_id`) v ON v.`1m_recipe_id` = r.`1m_recipe_id`
     ) t
WHERE t.valid_ingredients_perc >= 100;


