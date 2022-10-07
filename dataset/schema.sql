CREATE TABLE edamam_foods
(
    edamam_food_id   VARCHAR(255) NOT NULL PRIMARY KEY,
    label            VARCHAR(255) NOT NULL,
    known_as         VARCHAR(255) NOT NULL,
    nutrients_json   TEXT,
    category         VARCHAR(255),
    category_label   VARCHAR(255),
    me_json_response TEXT,
    image            VARCHAR(255)
);

CREATE TABLE me_foods
(
    me_food_id                     VARCHAR(255) NOT NULL PRIMARY KEY,
    food_name                      VARCHAR(255) NOT NULL,
    emissions                      double,
    emissions_category_letter      VARCHAR(1),
    emissions_category_description VARCHAR(255),
    me_json_response               TEXT,
    edamam_food_id                 VARCHAR(255),
    FOREIGN KEY (edamam_food_id) REFERENCES edamam_foods (edamam_food_id)
);

alter table me_foods
    add index (edamam_food_id);

CREATE TABLE edamam_hints
(
    edamam_food_id VARCHAR(255)               NOT NULL,
    type_id        VARCHAR(255)               NOT NULL,
    type           ENUM ('1m', 'foodb', 'me') NOT NULL,
    PRIMARY KEY (edamam_food_id, type_id, type)
);

alter table edamam_hints
    add index (edamam_food_id);
alter table edamam_hints
    add index (type_id);
alter table edamam_hints
    add index (type);
alter table edamam_hints
    add index (type, type_id);

create table 1m_recipe
(
    1m_recipe_id             varchar(255) NOT NULL primary key,
    title                    varchar(255),
    url                      varchar(255),
    valid_json               text,
    ingredients_json         text,
    instructions_json        text,
    fsa_lights_per100g_json  text,
    nutr_per_ingredient_json text,
    nutr_values_per100g_json text,
    quantity_json            text,
    unit_json                text,
    weight_per_ingr_json     text,
    `partition`              varchar(50)
);


create table 1m_recipes_ingredients
(
    1m_recipe_id   varchar(255) NOT NULL,
    ingredient_idx int(3),
    text           varchar(255),
    valid          int(1),
    edamam_food_id VARCHAR(255),
    FOREIGN KEY (edamam_food_id) REFERENCES edamam_foods (edamam_food_id),
    -- foreign key (1m_recipe_id) references 1m_recipe(1m_recipe_id),
    primary key (1m_recipe_id, ingredient_idx)
);

alter table 1m_recipes_ingredients
    add index (1m_recipe_id);
alter table 1m_recipes_ingredients
    add index (edamam_food_id);


CREATE TABLE `foodb_foods`
(
    `foodb_food_id` int(11)                              NOT NULL primary key,
    `name`          varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `wikipedia_id`  varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `food_group`    varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `food_subgroup` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    edamam_food_id  VARCHAR(255),
    FOREIGN KEY (edamam_food_id) REFERENCES edamam_foods (edamam_food_id)
);

-- Setta la CO2 stimata in base ad una relazione diretta con i cibi my_emission
alter table 1m_recipes_ingredients
    add co2_direct_my_emission varchar(128);
update 1m_recipes_ingredients i JOIN me_foods me ON i.edamam_food_id = me.edamam_food_id
set co2_direct_my_emission = me.emissions
where 1;

alter table 1m_recipes_ingredients
    add co2_min_by_hints_similarity varchar(128);
alter table 1m_recipes_ingredients
    add co2_max_by_hints_similarity varchar(128);
alter table 1m_recipes_ingredients
    add co2_mean_by_hints_similarity varchar(128);
alter table 1m_recipes_ingredients
    add co2_mean_max_freq_class_by_hints_similarity varchar(128);
alter table 1m_recipes_ingredients
    add co2_raw_data_hints_similarity text;


-- Crea la tabella contente le informaizoni gruppi-emissioni

CREATE TABLE group_with_emissions
(
    food_group          VARCHAR(255),
    food_subgroup       VARCHAR(255),
    emissions_max_value VARCHAR(255),
    emissions_min_value VARCHAR(255),
    emissions_avg       VARCHAR(255),
    PRIMARY KEY (food_group, food_subgroup)
);

ALTER TABLE 1m_recipes_ingredients
    ADD co2_mean_groups VARCHAR(128);

UPDATE 1m_recipes_ingredients 1m
    LEFT JOIN edamam_foods ed ON 1m.edamam_food_id = ed.edamam_food_id
    LEFT JOIN foodb_foods fd ON ed.edamam_food_id = fd.edamam_food_id
    LEFT JOIN group_with_emissions gwe ON gwe.food_group = fd.food_group AND gwe.food_subgroup = fd.food_subgroup
SET co2_mean_groups = emissions_avg
WHERE 1;


-- Recupero cibi edamam dai soli hints
SELECT i.`1m_recipe_id`, i.text, ef.label, ef.known_as
FROM `1m_recipes_ingredients` as i
         join edamam_hints as h ON h.type = '1m' AND h.type_id = i.text
         join edamam_foods ef on h.edamam_food_id = ef.edamam_food_id
where i.valid = 1
  and i.edamam_food_id IS NULL
  and (
        lower(i.text) LIKE CONCAT('%', lower(ef.label), '%') OR lower(i.text) LIKE CONCAT('%', lower(ef.known_as), '%')
    )
GROUP BY text;


-- Corgis
drop table if exists corgis_ingredients;
create table corgis_ingredients
(
    corgis_food_id int(11) not null primary key auto_increment,
    text           varchar(128),
    category       varchar(128),
    edamam_food_id VARCHAR(255) default null,
    FOREIGN KEY (edamam_food_id) REFERENCES edamam_foods (edamam_food_id)
);


-- Recupero cibi edamam dagli hints corgis

UPDATE corgis_ingredients i
    JOIN edamam_hints eh on i.corgis_food_id = eh.type_id and eh.type = 'corgis'
    JOIN edamam_foods f on eh.edamam_food_id = f.edamam_food_id
SET i.edamam_food_id = f.edamam_food_id
where i.edamam_food_id IS NULL
  -- AND (lower(f.known_as) = lower(i.text) OR lower(f.label) = lower(i.text))
  AND (lower(f.known_as) LIKE CONCAT('%', lower(i.text), '%') OR lower(f.label) LIKE CONCAT('%', lower(i.text), '%'));

-- CREA LA TABELLA HEALABEL WATER

CREATE TABLE healabel_water_foodprint
(
    lasso_id         VARCHAR(255) NOT NULL PRIMARY KEY,
    url              TEXT,
    name             VARCHAR(255),
    liters_per_kg    VARCHAR(255),
    me_json_response TEXT,
    edamam_food_id   VARCHAR(255),
    FOREIGN KEY (edamam_food_id) REFERENCES edamam_foods (edamam_food_id)
);

ALTER TABLE `edamam_hints`
    CHANGE `type` `type` ENUM ('1m','foodb','me','healabel','corgis') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;


UPDATE healabel_water_foodprint w
    LEFT JOIN (SELECT lasso_id, min(h.edamam_food_id) id_eda
               FROM healabel_water_foodprint w
                        LEFT join edamam_hints as h ON h.type = 'healabel' AND h.type_id = w.lasso_id
                        LEFT join edamam_foods ef on h.edamam_food_id = ef.edamam_food_id
               where w.edamam_food_id IS NULL
                 and (
                       lower(w.name) LIKE CONCAT('%', lower(ef.label), '%') OR
                       lower(w.name) LIKE CONCAT('%', lower(ef.known_as), '%')
                   )
               GROUP BY lasso_id) new_link ON w.lasso_id = new_link.lasso_id
SET edamam_food_id = new_link.id_eda
WHERE w.edamam_food_id = new_link.lasso_id;


ALTER TABLE 1m_recipes_ingredients
    ADD h20_liters_per_kg_direct_healabel VARCHAR(128);


UPDATE 1m_recipes_ingredients 1m
    LEFT JOIN healabel_water_foodprint w ON 1m.edamam_food_id = w.edamam_food_id
SET h20_liters_per_kg_direct_healabel = liters_per_kg
WHERE 1;


-- Nuovo raggruppamento

CREATE TABLE corgis_category_emissions_h2o
(
    category          VARCHAR(255),
    emissions_max_value VARCHAR(255),
    emissions_min_value VARCHAR(255),
    emissions_avg       VARCHAR(255),
    liters_per_kg_max_value VARCHAR(255),
    liters_per_kg_h2o_min_value VARCHAR(255),
    liters_per_kg_h2o_avg       VARCHAR(255),
    PRIMARY KEY (category)
);

UPDATE `healabel_water_foodprint` SET `liters_per_kg` = '1.599' WHERE `healabel_water_foodprint`.`lasso_id` = '3042';

ALTER TABLE 1m_recipes_ingredients
    ADD co2_corgis_category VARCHAR(128),
    ADD h2o_corgis_category VARCHAR(128);

-- Salvo i valori di h2o e co2 nuovi basati su corgis category

UPDATE 1m_recipes_ingredients 1m
    LEFT JOIN corgis_ingredients c ON 1m.edamam_food_id = c.edamam_food_id
    LEFT JOIN corgis_category_emissions_h2o cc ON c.category = cc.category
SET co2_corgis_category = emissions_avg
WHERE 1;


UPDATE 1m_recipes_ingredients 1m
    LEFT JOIN corgis_ingredients c ON 1m.edamam_food_id = c.edamam_food_id
    LEFT JOIN corgis_category_emissions_h2o cc ON c.category = cc.category
SET h2o_corgis_category = liters_per_kg_h2o_avg
WHERE 1;