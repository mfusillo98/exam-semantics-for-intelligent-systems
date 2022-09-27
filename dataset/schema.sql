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

CREATE TABLE edamam_hints
(
    edamam_food_id VARCHAR(255)               NOT NULL,
    type_id    VARCHAR(255)               NOT NULL,
    type       ENUM ('1m', 'foodb', 'me') NOT NULL,
    PRIMARY KEY (edamam_food_id, type_id, type)
);

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
    1m_recipe_id varchar(255) NOT NULL,
    ingredient_idx int(3),
    text varchar(255),
    valid int(1),
    edamam_food_id                 VARCHAR(255),
    FOREIGN KEY (edamam_food_id) REFERENCES edamam_foods (edamam_food_id),
    -- foreign key (1m_recipe_id) references 1m_recipe(1m_recipe_id),
    primary key (1m_recipe_id, ingredient_idx)
);

alter table 1m_recipes_ingredients add index (1m_recipe_id);