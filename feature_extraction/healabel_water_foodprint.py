import pymysql
import database.connection
import scraping.edamam
import os
import time
import requests
import json
import re
from requests.adapters import HTTPAdapter
from urllib3.util.retry import Retry


def import_healabel_water_foodprint():
    f = open("dataset/healabel.com_water_footprint.json")
    healabel = json.load(f)

    connection = database.connection.get_connection(os.environ.get('DB_DATABASE'))
    cursor = connection.cursor(pymysql.cursors.SSDictCursor)

    for f in healabel:
        database.query.insert(cursor, 'healabel_water_foodprint', {
            "lasso_id": f['lasso_id'],
            "url": f['url'],
            "name": f['name'],
            "liters_per_kg": f['liters_per_kg']
        })

    connection.commit()
    connection.close()



def save_ingredient_edamam_food_id(lasso_id, edamam_food_id, cursor):
    database.query.update(
        cursor,
        'healabel_water_foodprint',
        {
            "edamam_food_id": edamam_food_id
        },
        {
            "lasso_id": lasso_id,
        }
    )



def save_edamam_food_hint(edamam_food_id, lasso_id, cursor):
    print("QUI")
    database.query.insert(cursor, 'edamam_hints', {
        'edamam_food_id': edamam_food_id,
        'type_id': lasso_id,
        'type': "healabel"
    }, True)



def fetch_healabel_edamam_foods():
    connection = database.connection.get_connection(os.environ.get('DB_DATABASE'))
    connection2 = database.connection.new_connection(os.environ.get('DB_DATABASE'))
    update_cursor = connection2.cursor()
    cursor = connection.cursor(pymysql.cursors.SSDictCursor)
    # Fetching all ingredients with an unbuffered cursor (we cannot keep all rows in memory)
    cursor.execute('SELECT * FROM healabel_water_foodprint WHERE edamam_food_id IS NULL')
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

            # Linking edamam food to the my emission's foods
            save_ingredient_edamam_food_id(row['lasso_id'], parsed['food']['foodId'], update_cursor)

        # Saving possible hints in the edamam response
        if 'hints' in edamam_response:
            hints = edamam_response['hints']

            for h in hints:
                if 'food' in h:
                    scraping.edamam.save_edamam_food(h, update_cursor)
                    save_edamam_food_hint(h['food']['foodId'], row['lasso_id'], update_cursor)
        connection2.commit()
        counter = counter + 1

    connection.close()
    connection2.close()
    return