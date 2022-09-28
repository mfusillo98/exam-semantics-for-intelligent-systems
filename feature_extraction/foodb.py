import json
import os
import time

import pymysql

import scraping.edamam
import database.connection
import database.query


def save_ingredient_edamam_food_id(foodb_food_id, edamam_food_id, cursor):
    database.query.update(
        cursor,
        'foodb_foods',
        {
            "edamam_food_id": edamam_food_id
        },
        {
            "foodb_food_id": foodb_food_id,
        }
    )


# Matteo Fusillo
def save_edamam_food_hint(edamam_food_id, foodb_food_id, cursor):
    database.query.insert(cursor, 'edamam_hints', {
        'edamam_food_id': edamam_food_id,
        'type_id': foodb_food_id,
        'type': "foodb"
    }, True)


# Matteo Fusillo
def fetch_foodb_edamam_foods():
    connection = database.connection.get_connection(os.environ.get('DB_DATABASE'))
    connection2 = database.connection.new_connection(os.environ.get('DB_DATABASE'))
    update_cursor = connection2.cursor()
    cursor = connection.cursor(pymysql.cursors.SSDictCursor)
    # Fetching all ingredients with an unbuffered cursor (we cannot keep all rows in memory)
    cursor.execute('SELECT * FROM foodb_foods WHERE edamam_food_id IS NULL')
    counter = 1
    for row in cursor:
        if row['name'] == '':
            continue
        print(f"> {counter}) Getting edamam foods for {row['name']}")
        edamam_response = scraping.edamam.fetch_edamam_food(row['name'])
        # Checking if the response is valid
        if edamam_response is not False and 'parsed' in edamam_response and \
                len(edamam_response['parsed']) > 0 and 'food' in edamam_response['parsed'][0]:
            parsed = edamam_response['parsed'][0]
            # Save the edamam food
            scraping.edamam.save_edamam_food(parsed, update_cursor)

            # Linking edamam food to the foodb's ingredient
            save_ingredient_edamam_food_id(row['foodb_food_id'], parsed['food']['foodId'], update_cursor)

        # Saving possible hints in the edamam response
        if 'hints' in edamam_response:
            hints = edamam_response['hints']

            for h in hints:
                if 'food' in h:
                    scraping.edamam.save_edamam_food(h, update_cursor)
                    save_edamam_food_hint(h['food']['foodId'], row['foodb_food_id'], update_cursor)
        connection2.commit()
        counter = counter + 1

    connection.close()
    connection2.close()
    return
