SET sql_mode = 'ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';

drop table if exists food_print.recipes;
create table food_print.recipes
(
    recipe_id  int(11)      not null primary key auto_increment,
    title      varchar(128),
    url        varchar(255),
    vendor_id  varchar(128) not null,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update current_timestamp
);
alter table food_print.recipes
    add index (vendor_id);

insert into food_print.recipes (title, url, vendor_id)
select title, url, `1m_recipe_id`
from semantics_our_schema.`1m_recipe`;

drop table if exists food_print.ingredients;
create table food_print.ingredients
(
    ingredient_id            int(11)      not null primary key auto_increment,
    name                     varchar(128) not null,
    category_name            varchar(128) default null,
    carbon_foot_print        double       default null,
    carbon_foot_print_source varchar(50)  default null comment 'How the WFP value has been retrieved',
    carbon_foot_print_weight varchar(50)  default null comment 'The weight of ingredient corresponding to the given WFP',
    water_foot_print         double       default null,
    water_foot_print_source  varchar(50)  default null comment 'How the WFP value has been retrieved',
    water_foot_print_weight  varchar(50)  default null comment 'The weight of ingredient corresponding to the given WFP',
    kcal                     float        default null,
    kcal_weight              float        default null,
    protein                  float        default null,
    protein_weight           float        default null,
    fat                      float        default null,
    fat_weight               float        default null,
    carbohydrates            float        default null,
    carbohydrates_weight     float        default null,
    fiber                    float        default null,
    fiber_weight             float        default null,
    vendor_recipe_ids        LONGTEXT         default null comment 'A list of strings in format idx@recipe_vendor_id which help to assign an ingredient to a vendor recipe',
    created_at               timestamp    default current_timestamp,
    updated_at               timestamp    default current_timestamp on update current_timestamp
);

-- All ingredients with edamam
SET group_concat_max_len=3000000;
SET @@group_concat_max_len=3000000;
SET GLOBAL group_concat_max_len=3000000;
INSERT INTO food_print.ingredients (name, category_name, carbon_foot_print, carbon_foot_print_source, carbon_foot_print_weight, water_foot_print, water_foot_print_source, water_foot_print_weight, kcal, kcal_weight, protein, protein_weight, fat, fat_weight, carbohydrates, carbohydrates_weight, fiber, fiber_weight, vendor_recipe_ids)
SELECT i.text, c.category as category_name,
       IFNULL(i.co2_direct_my_emission, IFNULL(i.co2_corgis_category, IFNULL(i.co2_mean_by_hints_similarity,
                                                                             IFNULL(i.co2_mean_groups, NULL)))) as carbon_foot_print,
       IF(i.co2_direct_my_emission IS NOT NULL, 'myemissions.green',
          IF(i.co2_corgis_category IS NOT NULL, 'corgis_category_mean',
             IF(i.co2_mean_by_hints_similarity IS NOT NULL, 'edamam_hits_similarity',
                IF(i.co2_mean_groups IS NOT NULL, 'foodb_category_mean', NULL))))                               as carbon_foot_print_source,
       100                                                                                                      as carbon_foot_print_weight,
       CAST(IFNULL(i.h20_liters_per_kg_direct_healabel,
                   IFNULL(i.h2o_corgis_category, NULL)) as DECIMAL)                                             as water_foot_print,
       IF(i.h20_liters_per_kg_direct_healabel IS NOT NULL, 'healabel',
          IF(i.h2o_corgis_category IS NOT NULL, 'corgis_category_mean', NULL))                                  as water_foot_print_source,
       1000                                                                                                     as water_foot_print_weight,
       JSON_UNQUOTE(JSON_EXTRACT(ef.nutrients_json, '$.ENERC_KCAL'))                                            as kcal,
       IF(JSON_EXTRACT(ef.nutrients_json, '$.ENERC_KCAL') IS NOT NULL, 100,
          null)                                                                                                 as kcal_weight,
       JSON_UNQUOTE(JSON_EXTRACT(ef.nutrients_json, '$.PROCNT'))                                                as protein,
       IF(JSON_EXTRACT(ef.nutrients_json, '$.PROCNT') IS NOT NULL, 100,
          null)                                                                                                 as protein_weight,
       JSON_UNQUOTE(JSON_EXTRACT(ef.nutrients_json, '$.FAT'))                                                   as fat,
       IF(JSON_EXTRACT(ef.nutrients_json, '$.FAT') IS NOT NULL, 100, null)                                      as fat_weight,
       JSON_UNQUOTE(JSON_EXTRACT(ef.nutrients_json, '$.CHOCDF'))                                                as carbohydrates,
       IF(JSON_EXTRACT(ef.nutrients_json, '$.CHOCDF') IS NOT NULL, 100,
          null)                                                                                                 as carbohydrates_weight,
       JSON_UNQUOTE(JSON_EXTRACT(ef.nutrients_json, '$.FIBTG'))                                                 as fiber,
       IF(JSON_EXTRACT(ef.nutrients_json, '$.FIBTG') IS NOT NULL, 100,
          null)                                                                                                 as fiber_weight,
       CONCAT('[',
              GROUP_CONCAT(CONCAT('{"idx":"', i.ingredient_idx, '","recipe_id":"', i.`1m_recipe_id`, '"}')),
              ']')                                                                                              as vendor_recipe_ids
FROM `1m_recipes_ingredients` i
         LEFT JOIN edamam_foods ef on i.edamam_food_id = ef.edamam_food_id
         LEFT JOIN corgis_ingredients c ON c.edamam_food_id = i.edamam_food_id
where i.edamam_food_id IS NOT NULL
GROUP BY i.edamam_food_id;

