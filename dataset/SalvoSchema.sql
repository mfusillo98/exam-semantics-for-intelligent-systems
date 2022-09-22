CREATE TABLE edamam_foods (
	edamam_food_id VARCHAR (255) NOT NULL PRIMARY KEY,
    label VARCHAR (255) NOT NULL,
    known_as VARCHAR (255) NOT NULL,
    nutrients_json TEXT,
    category VARCHAR (255),
    category_label VARCHAR (255),
    me_json_response TEXT,
    image VARCHAR (255)
);

CREATE TABLE me_foods (
	me_food_id VARCHAR (255) NOT NULL PRIMARY KEY,
    food_name VARCHAR (255) NOT NULL,
    emissions FLOAT,
    emissions_category_letter VARCHAR (1),
    emissions_category_description VARCHAR (255),
    me_json_response TEXT,
    edamam_food_id VARCHAR (255),
    FOREIGN KEY (edamam_food_id) REFERENCES edamam_foods(edamam_food_id)
);

CREATE TABLE edamam_hints (
  me_food_id VARCHAR (255) NOT NULL,
  type_id VARCHAR (255) NOT NULL,
  type ENUM('1m', 'foodb', 'me') NOT NULL,
  PRIMARY KEY (me_food_id, type_id, type)
);