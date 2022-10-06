import json
import os
import time

import pymysql

import scraping.edamam
import database.connection
import database.query


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
            database.query.insert(cursor, '1m_recipe', recipeData)
    connection.commit()


# Matteo Fusillo
def import_1m_recipes_ingredients():
    connection = database.connection.get_connection(os.environ.get('DB_DATABASE'))
    cursor = connection.cursor()
    cursor.execute("SELECT * FROM `1m_recipe`")
    for recipe in cursor.fetchall():
        ingredients = json.loads(recipe['ingredients_json'])
        print(f"Importing Recipe ")
        for i in range(0, len(ingredients)):
            ingredientData = {
                "1m_recipe_id": recipe['1m_recipe_id'],
                "ingredient_idx": i+1,
                "valid": 1,
                "text": ingredients[i]["text"][:255]
            }
            database.query.insert(cursor, '1m_recipes_ingredients', ingredientData)
    connection.commit()


# Matteo Fusillo
def save_ingredient_edamam_food_id(text, edamam_food_id, cursor):
    database.query.update(
        cursor,
        '1m_recipes_ingredients',
        {
            "edamam_food_id": edamam_food_id
        },
        {
            "text": text,
        }
    )


# Matteo Fusillo
def save_edamam_food_hint(edamam_food_id, text, cursor):
    query = f"INSERT IGNORE INTO edamam_hints (edamam_food_id, type_id, type) VALUES (%s, %s, '1m')"
    result = cursor.execute(query, (edamam_food_id, text))
    return result


# Matteo Fusillo
def fetch_1m_recipes_edamam_foods():
    connection = database.connection.get_connection(os.environ.get('DB_DATABASE'))
    connection2 = database.connection.new_connection(os.environ.get('DB_DATABASE'))
    update_cursor = connection2.cursor()
    cursor = connection.cursor(pymysql.cursors.SSDictCursor)
    # Fetching all ingredients with an unbuffered cursor (we cannot keep 1 million rows in memory)
    cursor.execute('SELECT text FROM `1m_recipes_ingredients` WHERE valid = 1 AND edamam_food_id IS NULL GROUP BY text')
    counter = 1
    for row in cursor:
        if row['text'] == '':
            continue
        print(f"> {counter}) Getting edamam foods for {row['text']}")
        edamam_response = scraping.edamam.fetch_edamam_food(row['text'])
        # Checking if the response is valid
        if edamam_response is not False and 'parsed' in edamam_response and \
                len(edamam_response['parsed']) > 0 and 'food' in edamam_response['parsed'][0]:
            parsed = edamam_response['parsed'][0]
            # Save the edamam food
            scraping.edamam.save_edamam_food(parsed, update_cursor)
            # print("> Saved edamam food")

            # Linking edamam food to the 1m recipe's ingredient
            save_ingredient_edamam_food_id(row['text'], parsed['food']['foodId'], update_cursor)
            # print("> Saved link to edamam food")

        # Saving possible hints in the edamam response
        if 'hints' in edamam_response:
            hints = edamam_response['hints']

            for h in hints:
                if 'food' in h:
                    scraping.edamam.save_edamam_food(h, update_cursor)
                    # print("> Saved edamam food hint")
                    save_edamam_food_hint(h['food']['foodId'], row['text'], update_cursor)
                    # print("> Saved edamam food hint link")
        connection2.commit()
        counter = counter + 1

    connection.close()
    connection2.close()
    return
