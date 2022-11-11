SET sql_mode = 'ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';

drop table if exists food_print.recipes;
create table food_print.recipes
(
    recipe_id    int(11)      not null primary key auto_increment,
    title        varchar(128),
    url          varchar(255),
    vendor_id    varchar(128) not null,
    static_score double    default null,
    mcfp         double    default null,
    trust_cfp    double    default null,
    mwfp         double    default null,
    trust_wfp    double    default null,
    created_at   timestamp default current_timestamp,
    updated_at   timestamp default current_timestamp on update current_timestamp
);

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
    vendor_recipe_ids        LONGTEXT     default null comment 'A list of strings in format idx@recipe_vendor_id which help to assign an ingredient to a vendor recipe',
    created_at               timestamp    default current_timestamp,
    updated_at               timestamp    default current_timestamp on update current_timestamp
);


-- All ingredients with edamam
SET group_concat_max_len = 3000000;
SET @@group_concat_max_len = 3000000;
SET GLOBAL group_concat_max_len = 3000000;
INSERT INTO food_print.ingredients (name, category_name, carbon_foot_print, carbon_foot_print_source,
                                    carbon_foot_print_weight, water_foot_print, water_foot_print_source,
                                    water_foot_print_weight, kcal, kcal_weight, protein, protein_weight, fat,
                                    fat_weight, carbohydrates, carbohydrates_weight, fiber, fiber_weight,
                                    vendor_recipe_ids)
SELECT i.text,
       c.category                                                                                               as category_name,
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

INSERT INTO food_print.ingredients (name, vendor_recipe_ids)
SELECT i.text,
       CONCAT('[',
              GROUP_CONCAT(CONCAT('{"idx":"', i.ingredient_idx, '","recipe_id":"', i.`1m_recipe_id`, '"}')),
              ']') as vendor_recipe_ids
from `1m_recipes_ingredients` i
where i.edamam_food_id IS NULL
group by i.text;

drop table if exists food_print.categories;
create table food_print.categories
(
    category_id int(11)     not null primary key auto_increment,
    name        varchar(50) not null,
    created_at  timestamp default current_timestamp,
    updated_at  timestamp default current_timestamp on update current_timestamp
);

insert into food_print.categories (name)
select category_name
from food_print.ingredients
where category_name is not null
group by category_name;

alter table food_print.ingredients
    add category_id int(11) default null after name;
alter table food_print.ingredients
    add foreign key (category_id) references food_print.categories (category_id) on update cascade on delete cascade;
update food_print.ingredients i JOIN food_print.categories c ON i.category_name = c.name
SET i.category_id = c.category_id
where i.category_name is not null;
alter table food_print.ingredients
    drop column category_name;

drop table if exists food_print.ingredients_recipes;
create table food_print.ingredients_recipes
(
    recipe_id        int(11) not null,
    ingredient_id    int(11) not null,
    ingredient_index int(2) default 1,
    primary key (recipe_id, ingredient_id),
    foreign key (recipe_id) references food_print.recipes (recipe_id) on update cascade on delete cascade,
    foreign key (ingredient_id) references food_print.ingredients (ingredient_id) on update cascade on delete cascade
);

-- #########################################################################################################################
-- At this point you have to execute the function in feature_extraction.standardization.populate_recipes_ingredients_pivot()
-- #########################################################################################################################


-- Functions used to compute part of the score related to Carbon Foot Print (CFP)
DROP FUNCTION IF EXISTS food_print.`get_mean_cfp`;
DELIMITER //
CREATE FUNCTION food_print.`get_mean_cfp`(recipe_id int(11))
    RETURNS DOUBLE
    DETERMINISTIC
    NO SQL
    COMMENT 'mcfp = 1/N • ∑carbon_foot_print(n) where N is the number of the ingredients we know CFP value'
BEGIN
    declare mcfp DOUBLE default 0.0;

    SELECT AVG(i.carbon_foot_print)
    INTO mcfp
    FROM food_print.ingredients_recipes ir
             JOIN food_print.ingredients i ON ir.ingredient_id = i.ingredient_id
    WHERE ir.recipe_id = recipe_id
      AND i.carbon_foot_print IS NOT NULL;

    RETURN mcfp;
END;
//
DELIMITER ;


DROP FUNCTION IF EXISTS food_print.`get_trust_cfp`;
DELIMITER //
CREATE FUNCTION food_print.`get_trust_cfp`(recipe_id int(11))
    RETURNS DOUBLE
    DETERMINISTIC
    NO SQL
    COMMENT 'trust_cfp is the number of ingredients we know the cfp value over the total number of ingredient of the recipe'
BEGIN
    declare trust_cfp DOUBLE default 0.0;

    SELECT COUNT(i.carbon_foot_print) / COUNT(*)
    INTO trust_cfp
    FROM food_print.ingredients_recipes ir
             JOIN food_print.ingredients i ON ir.ingredient_id = i.ingredient_id
    WHERE ir.recipe_id = recipe_id;

    RETURN trust_cfp;
END;
//
DELIMITER ;


-- Functions used to compute part of the score related to Water Foot Print (WFP)
DROP FUNCTION IF EXISTS food_print.`get_mean_wfp`;
DELIMITER //
CREATE FUNCTION food_print.`get_mean_wfp`(recipe_id int(11))
    RETURNS DOUBLE
    DETERMINISTIC
    NO SQL
    COMMENT 'mwfp = 1/N • ∑water_foot_print(n) where N is the number of the ingredients we know wfp value'
BEGIN
    declare mwfp DOUBLE default 0.0;

    SELECT AVG(i.water_foot_print)
    INTO mwfp
    FROM food_print.ingredients_recipes ir
             JOIN food_print.ingredients i ON ir.ingredient_id = i.ingredient_id
    WHERE ir.recipe_id = recipe_id
      AND i.water_foot_print IS NOT NULL;

    RETURN mwfp;
END;
//
DELIMITER ;


DROP FUNCTION IF EXISTS food_print.`get_trust_wfp`;
DELIMITER //
CREATE FUNCTION food_print.`get_trust_wfp`(recipe_id int(11))
    RETURNS DOUBLE
    DETERMINISTIC
    NO SQL
    COMMENT 'trust_wfp is the number of ingredients we know the wfp value over the total number of ingredient of the recipe'
BEGIN
    declare trust_wfp DOUBLE default 0.0;

    SELECT COUNT(i.water_foot_print) / COUNT(*)
    INTO trust_wfp
    FROM food_print.ingredients_recipes ir
             JOIN food_print.ingredients i ON ir.ingredient_id = i.ingredient_id
    WHERE ir.recipe_id = recipe_id;

    RETURN trust_wfp;
END;
//
DELIMITER ;


UPDATE food_print.recipes
SET mcfp      = food_print.get_mean_cfp(recipe_id),
    trust_cfp = food_print.get_trust_cfp(recipe_id),
    mwfp      = food_print.get_mean_wfp(recipe_id),
    trust_wfp = food_print.get_trust_wfp(recipe_id)
WHERE 1;

UPDATE food_print.recipes
SET static_score = (mcfp / IF(trust_cfp > 0, trust_cfp, 0.00001)) + (mwfp / IF(trust_wfp > 0, trust_wfp, 0.00001))
WHERE 1;


-- Changing scoring formula using Z-scores for cfp and wfp

DROP FUNCTION IF EXISTS food_print.`get_global_mean_cfp`;
DELIMITER //
CREATE FUNCTION food_print.`get_global_mean_cfp`()
    RETURNS DOUBLE
    DETERMINISTIC
    NO SQL
    COMMENT 'µ = 1/N * ∑cfp'
BEGIN
    declare mcfp DOUBLE default 0.0;

    SELECT AVG(carbon_foot_print)
    INTO mcfp
    FROM food_print.ingredients
    WHERE carbon_foot_print IS NOT NULL;

    RETURN mcfp;
END;
//
DELIMITER ;


DROP FUNCTION IF EXISTS food_print.`get_global_mean_wfp`;
DELIMITER //
CREATE FUNCTION food_print.`get_global_mean_wfp`()
    RETURNS DOUBLE
    DETERMINISTIC
    NO SQL
    COMMENT 'µ = 1/N * ∑wfp'
BEGIN
    declare mwfp DOUBLE default 0.0;

    SELECT AVG(water_foot_print)
    INTO mwfp
    FROM food_print.ingredients
    WHERE water_foot_print IS NOT NULL;

    RETURN mwfp;
END;
//
DELIMITER ;



DROP FUNCTION IF EXISTS food_print.`get_global_std_dev_cfp`;
DELIMITER //
CREATE FUNCTION food_print.`get_global_std_dev_cfp`()
    RETURNS DOUBLE
    DETERMINISTIC
    NO SQL
    COMMENT 'σ = √( (1/(N-1)) * ∑(cfp - µ)^2 ) with µ = get_global_mean_cfp()'
BEGIN
    declare std_dev DOUBLE default 0.0;
    declare n INT default 0;
    declare m DOUBLE default 0.0;

    SELECT COUNT(*) INTO n FROM ingredients;
    SELECT get_global_mean_cfp() INTO m;

    SELECT SQRT((1/(n-1)) * SUM((carbon_foot_print - m)^2))
    INTO std_dev
    FROM food_print.ingredients
    WHERE carbon_foot_print IS NOT NULL;

    RETURN std_dev;
END;
//
DELIMITER ;


DROP FUNCTION IF EXISTS food_print.`get_global_std_dev_wfp`;
DELIMITER //
CREATE FUNCTION food_print.`get_global_std_dev_wfp`()
    RETURNS DOUBLE
    DETERMINISTIC
    NO SQL
    COMMENT 'σ = √( (1/(N-1)) * ∑(wfp - µ)^2 ) with µ = get_global_mean_wfp()'
BEGIN
    declare std_dev DOUBLE default 0.0;
    declare n INT default 0;
    declare m DOUBLE default 0.0;

    SELECT COUNT(*) INTO n FROM ingredients;
    SELECT get_global_mean_wfp() INTO m;

    SELECT SQRT((1/(n-1)) * SUM((water_foot_print - m)^2))
    INTO std_dev
    FROM food_print.ingredients
    WHERE water_foot_print IS NOT NULL;

    RETURN std_dev;
END;
//
DELIMITER ;

alter table food_print.ingredients
    add carbon_foot_print_z_score float default null after carbon_foot_print;
alter table food_print.ingredients
    add water_foot_print_z_score float default null after water_foot_print;


-- Computing Z-normalization for cfp/wfp => cfp_std = (cfp - µ) / σ
UPDATE food_print.ingredients SET carbon_foot_print_z_score = (carbon_foot_print - food_print.get_global_mean_cfp())/food_print.get_global_std_dev_cfp() where carbon_foot_print IS NOT NULL;
UPDATE food_print.ingredients SET water_foot_print_z_score = (water_foot_print - food_print.get_global_mean_wfp())/food_print.get_global_std_dev_wfp() where water_foot_print IS NOT NULL;


-- Users
CREATE TABLE users (
                       user_id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                       username varchar(255) NOT NULL,
                       password varchar(255) NOT NULL,
                       age INT(3) NOT NULL,
                       gender INT(1) NOT NULL,
                       height float(3,2) NOT NULL,
                       weight float(3,2) NOT NULL,
                       `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                       `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE ingredients_user (
                                  user_id INT(11) NOT NULL,
                                  ingredient_id INT(11) NOT NULL,
                                  type VARCHAR(255) NOT NULL,
                                  PRIMARY KEY (user_id, ingredient_id, type),
                                  FOREIGN KEY (user_id) REFERENCES users(user_id),
                                  FOREIGN KEY (ingredient_id) REFERENCES ingredients(ingredient_id)
);