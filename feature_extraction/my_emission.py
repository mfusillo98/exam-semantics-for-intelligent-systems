import json
import os

import pymysql
import database.connection
import database.query
import scraping.edamam

def import_my_emission():
    f = open("dataset/myemissions.green-ingredients-with-emission.json")
    myEmissionJSON = json.load(f)

    for food in myEmissionJSON:

        #Setto food_name, emissions
        if 'ingredients' in food['emissionsData']:
            food_name = food['emissionsData']['ingredients'][0]['food_name']
            emissions = food['emissionsData']['ingredients'][0]['emissions']
        else:
            print(food)
            food_name = food['label']
            emissions = ''

        #Setto emissions_category_letter, emissions_category_description
        if 'recipe' in food['emissionsData']:
            emissions_category_letter = food['emissionsData']['recipe'][0]['emissions_category_letter']
            emissions_category_description = food['emissionsData']['recipe'][0]['emissions_category_description']
        else:
            emissions_category_letter = ''
            emissions_category_description = ''

        # database connection
        connection = pymysql.connect(host="localhost",
                                     user="root",
                                     passwd="root",
                                     database="semantics_our_schema",
                                     port=8889,
                                     charset='utf8',
                                     cursorclass=pymysql.cursors.DictCursor)
        cursor = connection.cursor()

        insert = "INSERT INTO me_foods (me_food_id, food_name, emissions, emissions_category_letter, emissions_category_description, me_json_response) " \
                 "VALUES ('"+food['id']+"', '"+food_name+"', '"+str(emissions)+"', '"+emissions_category_letter+"', '"+emissions_category_description+"', '"+json.dumps(food)+"');"

        print(insert)

        cursor.execute(insert)

        connection.commit()
        connection.close()


# Matteo Fusillo
def save_ingredient_edamam_food_id(me_food_id, edamam_food_id, cursor):
    database.query.update(
        cursor,
        'me_foods',
        {
            "edamam_food_id": edamam_food_id
        },
        {
            "me_food_id": me_food_id,
        }
    )


# Matteo Fusillo
def save_edamam_food_hint(edamam_food_id, me_food_id, cursor):
    database.query.insert(cursor, 'edamam_hints', {
        'edamam_food_id': edamam_food_id,
        'type_id': me_food_id,
        'type': "me"
    }, True)


# Matteo Fusillo
def fetch_me_food_edamam_foods():
    connection = database.connection.get_connection(os.environ.get('DB_DATABASE'))
    connection2 = database.connection.new_connection(os.environ.get('DB_DATABASE'))
    update_cursor = connection2.cursor()
    cursor = connection.cursor(pymysql.cursors.SSDictCursor)
    # Fetching all ingredients with an unbuffered cursor (we cannot keep all rows in memory)
    cursor.execute('SELECT * FROM me_foods WHERE edamam_food_id IS NULL')
    counter = 1
    for row in cursor:
        if row['food_name'] == '':
            continue
        print(f"> {counter}) Getting edamam foods for {row['food_name']}")
        edamam_response = scraping.edamam.fetch_edamam_food(row['food_name'])
        # Checking if the response is valid
        if edamam_response is not False and 'parsed' in edamam_response and \
                len(edamam_response['parsed']) > 0 and 'food' in edamam_response['parsed'][0]:
            parsed = edamam_response['parsed'][0]
            # Save the edamam food
            scraping.edamam.save_edamam_food(parsed, update_cursor)

            # Linking edamam food to the my emission's foods
            save_ingredient_edamam_food_id(row['me_food_id'], parsed['food']['foodId'], update_cursor)

        # Saving possible hints in the edamam response
        if 'hints' in edamam_response:
            hints = edamam_response['hints']

            for h in hints:
                if 'food' in h:
                    scraping.edamam.save_edamam_food(h, update_cursor)
                    save_edamam_food_hint(h['food']['foodId'], row['me_food_id'], update_cursor)
        connection2.commit()
        counter = counter + 1

    connection.close()
    connection2.close()
    return
