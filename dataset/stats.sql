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
SELECT 'Ricette totali' as description, COUNT(*)
FROM `1m_recipe`
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
-- Ricette 80% ingr. con CO2  | 1360  |
-- -----------------------------------
-- Ricette 90% ingr. con CO2  | 905   |
-- -----------------------------------
-- Ricette 100% ingr. con CO2 | 905   |
-- -----------------------------------

-- Ricette totali
SELECT 'Ricette totali' as description, COUNT(*)
FROM `1m_recipe`
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
                        Where IFNULL(co2_direct_my_emission, IFNULL(co2_corgis_category,
                                                                    IFNULL(co2_mean_by_hints_similarity, IFNULL(co2_mean_groups, NULL)))) IS NOT NULL
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
                        Where IFNULL(co2_direct_my_emission, IFNULL(co2_corgis_category,
                                                                    IFNULL(co2_mean_by_hints_similarity, IFNULL(co2_mean_groups, NULL)))) IS NOT NULL
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
                        Where IFNULL(co2_direct_my_emission, IFNULL(co2_corgis_category,
                                                                    IFNULL(co2_mean_by_hints_similarity, IFNULL(co2_mean_groups, NULL)))) IS NOT NULL
                        GROUP BY `1m_recipe_id`) v ON v.`1m_recipe_id` = r.`1m_recipe_id`
     ) t
WHERE t.valid_ingredients_perc >= 100;



-- ----------------------------------
-- STIME RICETTE IN BASE A VALORI H2O
-- ----------------------------------

-- -----------------------------------
-- Ricette totali             | 51235 |
-- -----------------------------------
-- Ricette 80% ingr. con H2O  | 357   |
-- -----------------------------------
-- Ricette 90% ingr. con H2O  | 295   |
-- -----------------------------------
-- Ricette 100% ingr. con H2O | 295   |
-- -----------------------------------

-- Ricette totali
SELECT 'Ricette totali' as description, COUNT(*)
FROM `1m_recipe`
UNION
-- Ricette con almeno 80% di ingredienti con almeno 1 valore di H20
SELECT 'Ricette 80% ingr. con H2O' as description, COUNT(*)
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
                        Where IFNULL(h20_liters_per_kg_direct_healabel, IFNULL(h2o_corgis_category, NULL)) IS NOT NULL
                        GROUP BY `1m_recipe_id`) v ON v.`1m_recipe_id` = r.`1m_recipe_id`
     ) t
WHERE t.valid_ingredients_perc >= 80
UNION
-- Ricette con almeno 90% di ingredienti con almeno 1 valore di H20
SELECT 'Ricette 90% ingr. con H2O' as description, COUNT(*)
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
                        Where IFNULL(h20_liters_per_kg_direct_healabel, IFNULL(h2o_corgis_category, NULL)) IS NOT NULL
                        GROUP BY `1m_recipe_id`) v ON v.`1m_recipe_id` = r.`1m_recipe_id`
     ) t
WHERE t.valid_ingredients_perc >= 90
UNION
-- Ricette con 100% di ingredienti con almeno 1 valore di H20
SELECT 'Ricette 100% ingr. con H2O' as description, COUNT(*)
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
                        Where IFNULL(h20_liters_per_kg_direct_healabel, IFNULL(h2o_corgis_category, NULL)) IS NOT NULL
                        GROUP BY `1m_recipe_id`) v ON v.`1m_recipe_id` = r.`1m_recipe_id`
     ) t
WHERE t.valid_ingredients_perc >= 100;



-- ----------------------------------
-- STIME RICETTE IN BASE A VALORI CO2 & H2O
-- ----------------------------------

-- -----------------------------------------
-- Ricette totali                   | 51235 |
-- -----------------------------------------
-- Ricette 80% ingr. con CO2 e H2O  | 357   |
-- -----------------------------------------
-- Ricette 90% ingr. con CO2 e H2O  | 295   |
-- -----------------------------------------
-- Ricette 100% ingr. con CO2 e H2O | 295   |
-- -----------------------------------------

-- Ricette totali
SELECT 'Ricette totali' as description, COUNT(*)
FROM `1m_recipe`
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
                        Where IFNULL(co2_direct_my_emission, IFNULL(co2_corgis_category,
                                                                    IFNULL(co2_mean_by_hints_similarity, IFNULL(co2_mean_groups, NULL)))) IS NOT NULL
                          AND IFNULL(h20_liters_per_kg_direct_healabel, IFNULL(h2o_corgis_category, NULL)) IS NOT NULL
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
                        Where IFNULL(co2_direct_my_emission, IFNULL(co2_corgis_category,
                                                                    IFNULL(co2_mean_by_hints_similarity, IFNULL(co2_mean_groups, NULL)))) IS NOT NULL
                          AND IFNULL(h20_liters_per_kg_direct_healabel, IFNULL(h2o_corgis_category, NULL)) IS NOT NULL
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
                        Where IFNULL(co2_direct_my_emission, IFNULL(co2_corgis_category,
                                                                    IFNULL(co2_mean_by_hints_similarity, IFNULL(co2_mean_groups, NULL)))) IS NOT NULL
                          AND IFNULL(h20_liters_per_kg_direct_healabel, IFNULL(h2o_corgis_category, NULL)) IS NOT NULL
                        GROUP BY `1m_recipe_id`) v ON v.`1m_recipe_id` = r.`1m_recipe_id`
     ) t
WHERE t.valid_ingredients_perc >= 100;


-- Frequenza ingredienti nelle ricette
SELECT i.edamam_food_id, COUNT(r.`1m_recipe_id`) as recipes_num
FROM `1m_recipes_ingredients` i
         JOIN `1m_recipe` r on r.`1m_recipe_id` = i.`1m_recipe_id`
where i.edamam_food_id is not null
GROUP BY i.edamam_food_id;



-- Top 100 ingredients by lowest CO2 score
SET SESSION sql_mode = 'ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';

select t.*
FROM (
         SELECT text,
                edamam_food_id,
                IFNULL(co2_direct_my_emission, IFNULL(co2_corgis_category,
                                                      IFNULL(co2_mean_by_hints_similarity, IFNULL(co2_mean_groups, NULL)))) as co2_foot_print
         FROM `1m_recipes_ingredients`
         where edamam_food_id is not null
         group by edamam_food_id
     ) as t
where t.co2_foot_print is not null
  and co2_foot_print > 0
order by t.co2_foot_print
limit 100;

-- Ingredients with missing co2 footprint score
SET SESSION sql_mode = 'ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';

select t.*, f.recipes_num
FROM (
         SELECT text,
                edamam_food_id,
                IFNULL(co2_direct_my_emission, IFNULL(co2_corgis_category,
                                                      IFNULL(co2_mean_by_hints_similarity, IFNULL(co2_mean_groups, NULL)))) as co2_foot_print
         FROM `1m_recipes_ingredients`
         where edamam_food_id is not null
         group by edamam_food_id
     ) as t
         JOIN (
    SELECT i.edamam_food_id, COUNT(r.`1m_recipe_id`) as recipes_num
    FROM `1m_recipes_ingredients` i
             JOIN `1m_recipe` r on r.`1m_recipe_id` = i.`1m_recipe_id`
    where i.edamam_food_id is not null
    GROUP BY i.edamam_food_id
) as f ON t.edamam_food_id = f.edamam_food_id
where t.co2_foot_print is null
   OR t.co2_foot_print = ''
   OR t.co2_foot_print = 0
order by f.recipes_num DESC;

-- Top 100 ingredients by lowest water foot print score
SET SESSION sql_mode = 'ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';

select t.*
FROM (
         SELECT text,
                edamam_food_id,
                CAST(IFNULL(h20_liters_per_kg_direct_healabel,
                            IFNULL(h2o_corgis_category, 0)) as DECIMAL) as h2o_foot_print
         FROM `1m_recipes_ingredients`
         where edamam_food_id is not null
         group by edamam_food_id
     ) as t
where t.h2o_foot_print is not null
  and h2o_foot_print > 0
order by t.h2o_foot_print
limit 100;


-- Ingredients with missing h2o footprint score
SET SESSION sql_mode = 'ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';

select t.*, f.recipes_num
FROM (
         SELECT text,
                edamam_food_id,
                CAST(IFNULL(h20_liters_per_kg_direct_healabel,
                            IFNULL(h2o_corgis_category, NULL)) as DECIMAL) as h2o_foot_print
         FROM `1m_recipes_ingredients`
         where edamam_food_id is not null
         group by edamam_food_id
     ) as t
         JOIN (
    SELECT i.edamam_food_id, COUNT(r.`1m_recipe_id`) as recipes_num
    FROM `1m_recipes_ingredients` i
             JOIN `1m_recipe` r on r.`1m_recipe_id` = i.`1m_recipe_id`
    where i.edamam_food_id is not null
    GROUP BY i.edamam_food_id
) as f ON t.edamam_food_id = f.edamam_food_id
where t.h2o_foot_print is null
   OR t.h2o_foot_print = ''
   OR t.h2o_foot_print = 0
order by f.recipes_num DESC;

