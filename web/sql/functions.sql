SET sql_mode = 'ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';

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


-- Computing Z-normalization for cfp/wfp => cfp_std = (cfp - µ) / σ
UPDATE food_print.ingredients SET carbon_foot_print_z_score = (carbon_foot_print - food_print.get_global_mean_cfp())/food_print.get_global_std_dev_cfp() where carbon_foot_print IS NOT NULL;
UPDATE food_print.ingredients SET water_foot_print_z_score = (water_foot_print - food_print.get_global_mean_wfp())/food_print.get_global_std_dev_wfp() where water_foot_print IS NOT NULL;
