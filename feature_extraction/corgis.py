import json
import os

import pymysql

import filemanager
import database.query
import database.connection
import scraping.edamam


def import_ingredients():
    connection = database.connection.get_connection(os.environ.get("DB_DATABASE"))
    cursor = connection.cursor()
    file = filemanager.get_abs_path('/dataset/corgis.json')
    with open(file, 'r') as f:
        ingredients = json.load(f)
        for ingredient in ingredients:
            # Saving ingredient
            ingredientData = {'text': ingredient['Description'], 'category': ingredient['Category']}
            database.query.insert(cursor, "corgis_ingredients", ingredientData, True)
    connection.commit()


def save_ingredient_edamam_food_id(corgis_food_id, edamam_food_id, cursor):
    database.query.update(
        cursor,
        'corgis_ingredients',
        {
            "edamam_food_id": edamam_food_id
        },
        {
            "corgis_food_id": corgis_food_id,
        }
    )


# Matteo Fusillo
def save_edamam_food_hint(edamam_food_id, corgis_food_id, cursor):
    database.query.insert(cursor, 'edamam_hints', {
        'edamam_food_id': edamam_food_id,
        'type_id': corgis_food_id,
        'type': "corgis"
    }, True)


# Matteo Fusillo
def fetch_foodb_edamam_foods():
    connection = database.connection.get_connection(os.environ.get('DB_DATABASE'))
    connection2 = database.connection.new_connection(os.environ.get('DB_DATABASE'))
    update_cursor = connection2.cursor()
    cursor = connection.cursor(pymysql.cursors.SSDictCursor)
    # Fetching all ingredients with an unbuffered cursor (we cannot keep all rows in memory)
    cursor.execute('SELECT * FROM corgis_ingredients WHERE edamam_food_id IS NULL')
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

            # Linking edamam food to the foodb's ingredient
            save_ingredient_edamam_food_id(row['corgis_food_id'], parsed['food']['foodId'], update_cursor)

        # Saving possible hints in the edamam response
        if 'hints' in edamam_response:
            hints = edamam_response['hints']

            for h in hints:
                if 'food' in h:
                    scraping.edamam.save_edamam_food(h, update_cursor)
                    save_edamam_food_hint(h['food']['foodId'], row['corgis_food_id'], update_cursor)
        connection2.commit()
        counter = counter + 1

    connection.close()
    connection2.close()
    return
