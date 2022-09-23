import json
import os
import database.connection


# Matteo Fusillo
def import_1m_recipes():
    connection = database.connection.get_connection(os.environ.get('DB_DATABASE'))
    cursor = connection.cursor()

    with open('dataset/1m_recipes_with_nutritional_info.json') as f:
        recipes = json.load(f)
        for recipe in recipes:
            recipeData = {
                "1m_recipe_id": recipe['id'],
                "title": recipe['title'],
                "url": recipe['url'],
                "ingredients_json": json.dumps(recipe['ingredients']),
                "instructions_json": json.dumps(recipe['instructions']),
                "fsa_lights_per100g_json": json.dumps(recipe['fsa_lights_per100g']),
                "nutr_per_ingredient_json": json.dumps(recipe['nutr_per_ingredient']),
                "nutr_values_per100g_json": json.dumps(recipe['nutr_values_per100g']),
                "quantity_json": json.dumps(recipe['quantity']),
                "unit_json": json.dumps(recipe['unit']),
                "weight_per_ingr_json": json.dumps(recipe['weight_per_ingr']),
                "partition": recipe['partition']
            }
            try:
                cols = '`,`'.join(recipeData.keys())
                placeholders = ', '.join(['%({})s'.format(key) for key in recipeData.keys()])
                query = "INSERT INTO 1m_recipe (`{}`) VALUES ({})".format(cols, placeholders);
                result = cursor.execute(query, recipeData)
            except:
                print(cursor._last_executed)
                raise
    connection.commit()


# Matteo Fusillo
def import_1m_recipes_ingredients():
    connection = database.connection.get_connection(os.environ.get('DB_DATABASE'))
    cursor = connection.cursor()

    with open('dataset/1m_recipes_det_ingrs.json') as f:
        recipesIngredients = json.load(f)
        for recipeIngredients in recipesIngredients:
            for i in range(0, len(recipeIngredients["ingredients"])):
                ingredient = recipeIngredients["ingredients"][i]
                valid = 1 if recipeIngredients["valid"][i] else 0
            ingredientData = {
                "1m_recipe_id": recipeIngredients['id'],
                "ingredient_idx": i,
                "valid": valid,
                "text": ingredient["text"][:255]
            }
            try:
                cols = '`,`'.join(ingredientData.keys())
                placeholders = ', '.join(['%({})s'.format(key) for key in ingredientData.keys()])
                query = "INSERT INTO 1m_recipes_ingredients (`{}`) VALUES ({})".format(cols, placeholders);
                result = cursor.execute(query, ingredientData)
            except:
                print(cursor._last_executed)
                raise

    connection.commit()
