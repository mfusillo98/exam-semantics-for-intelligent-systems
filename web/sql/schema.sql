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