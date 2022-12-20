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

-- Disabling recipes based on blacklist keywords
alter table recipes add disabled int(1) default 0;
alter table recipes add index(disabled);
alter table recipes add rating int(1) default null;
alter table recipes add index(rating);
alter table recipes add rating_count int(5) default null;
alter table recipes add index(rating_count);
update recipes set disabled = 1 where title like '% dip%' or title like 'dip %';
update recipes set disabled = 1 where title like '% dipping%' or title like 'dipping %';
update recipes set disabled = 1 where title like '% cocktail%' or title like 'cocktail %';
update recipes set disabled = 1 where title like '% candy%' or title like 'candy %';
update recipes set disabled = 1 where title like '% bars%' or title like 'bars %';
update recipes set disabled = 1 where title like '% caramel%' or title like 'caramel %';
update recipes set disabled = 1 where title like '% ice cube%' or title like 'ice cube %';
update recipes set disabled = 1 where title like '% smoothie%' or title like 'smoothie %';
update recipes set disabled = 1 where title like '% amaretto%' or title like 'amaretto %';
update recipes set disabled = 1 where title like '% cubes%' or title like 'cubes %';
update recipes set disabled = 1 where title like '% sauce%' or title like 'sauce %';
update recipes set disabled = 1 where title like '% dressing%' or title like 'dressing %';
update recipes set disabled = 1 where title like '% shake%' or title like 'shake %';
update recipes set disabled = 1 where title like '% syrup%' or title like 'syrup %';
update recipes set disabled = 1 where title like '% ice cream%' or title like 'ice cream %';

-- SURVEY USERS

DROP TABLE survey_users;
DROP TABLE survey_users_recipes;

CREATE TABLE survey_users (
                              survey_user_id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                              age INT(3) NOT NULL,
                              gender VARCHAR(255) NOT NULL,
                              height VARCHAR(255) NOT NULL,
                              weight VARCHAR(255) NOT NULL,
                              importance_sustainable_food_choice varchar(255),
                              sustainability_of_your_food_choice varchar(255),
                              sustainable_food_choices varchar(255),
                              importance_healthy_lifestyle varchar(255) NOT NULL,
                              healthy_of_your_lifestyle varchar(255) NOT NULL,
                              healthy_food_choices varchar(255) NOT NULL,
                              employment varchar(255) ,
                              recipe_website_usage varchar(255) ,
                              preparing_home_cooked_meals varchar(255),
                              goal varchar(255) ,
                              `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                              `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                              with_suggestion INT(11) default 0
);


CREATE TABLE survey_users_recipes (
    survey_user_id int(11) NOT NULL,
    type VARCHAR(255) NOT NULL,
    chosen_recipe_id int(11) NOT NULL,
    other_recipe_id INT(11) NOT NULL,
    PRIMARY KEY (survey_user_id, chosen_recipe_id, other_recipe_id),
    FOREIGN KEY (survey_user_id) REFERENCES survey_users(survey_user_id),
    FOREIGN KEY (chosen_recipe_id) REFERENCES recipes(recipe_id),
    FOREIGN KEY (other_recipe_id) REFERENCES recipes(recipe_id)
);


ALTER TABLE survey_users_recipes
    ADD why_selection VARCHAR(255),
    ADD favorite_to_cook VARCHAR(255),
    ADD favorite_to_cook_why VARCHAR(255),
    ADD better_recipe_id INT(11),
    ADD FOREIGN KEY (better_recipe_id) REFERENCES recipes(recipe_id)
;


ALTER TABLE survey_users_recipes
    DROP column favorite_to_cook_why,
    ADD matches_preferences VARCHAR(255),
    ADD tastier VARCHAR(255),
    ADD helps_eat_healthily VARCHAR(255),
    ADD helps_eat_sustainable INT(11),
    ADD easy_to_prepare INT(11)
;