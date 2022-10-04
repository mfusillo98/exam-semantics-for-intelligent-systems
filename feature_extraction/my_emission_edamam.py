import database.connection
import os
import time
import requests
import json
import re
from requests.adapters import HTTPAdapter
from urllib3.util.retry import Retry


EDAMAM_APP_ID = 26994147
EDAMAM_APP_KEY = '9705a737a2daecd8b773aed6477b7cbe'

def my_emission_edamam():
    #Recupero il numero totale di elementi
    connection = database.connection.get_connection(os.environ.get('DB_DATABASE'))
    cursor = connection.cursor()

    cursor.execute('SELECT COUNT(*) as n_values FROM me_foods')
    values = cursor.fetchone()['n_values']

    for i in range(0, values-1):

        # Recupero l'me_food

        cursor.execute("SELECT * FROM me_foods LIMIT 1 OFFSET " + str(i) + ";")
        me_food = cursor.fetchone()

        print(me_food['food_name'])

        # Richiesta emadame
        session = requests.Session()
        retry = Retry(connect=3, backoff_factor=0.5)
        adapter = HTTPAdapter(max_retries=retry)
        session.mount('http://', adapter)
        session.mount('https://', adapter)

        params = {'app_id': EDAMAM_APP_ID, 'app_key': EDAMAM_APP_KEY, 'ingr': me_food['food_name']}
        edamam_response = session.get('https://api.edamam.com/api/food-database/v2/parser', params=params)

        # Se la risposta è positiva proseguo
        if (edamam_response.status_code != 200):
            print('Il cibo:' + me_food['me_food_id'] + ' ha avuto esito negativo nella richiesta')
        else:
            edamam_response_content = json.loads(edamam_response.content)

            # Se ha trovato qualcosa posso salvare
            if 'parsed' in edamam_response_content and 0 in edamam_response_content and 'food' in edamam_response_content['parsed'][0]:
                parsed = edamam_response_content['parsed'][0]

                #Salvo il cibo trovato in edamam
                saveEdamamFood(parsed, cursor)

                # Lo collego al cibo cercato
                insert = "UPDATE me_foods SET edamam_food_id = '" + parsed['food']['foodId'] + \
                         "' WHERE me_food_id = '" + me_food['me_food_id'] + "'"
                cursor.execute(insert)

            # Salvo i possibili hints
            if 'hints' in edamam_response_content:
                hints = edamam_response_content['hints']

                for h in hints:
                    if 'food' in h:
                        saveEdamamFood(h, cursor)

                        insert = "INSERT INTO edamam_hints (me_food_id, type_id, type)" \
                                 "VALUES ('" + h['food']['foodId'] + "', '" + me_food[
                                     'me_food_id'] + "', 'me')"
                        cursor.execute(insert)

        time.sleep(0.8)

    connection.commit()
    connection.close()



def saveEdamamFood(parsed, cursor):

    print(parsed)

    cursor.execute("SELECT * FROM edamam_foods WHERE edamam_food_id='"+parsed['food']['foodId']+"'")
    me_food = cursor.fetchone()

    if me_food:
        return 0

    if 'image' not in parsed['food']:
        parsed['food']['image'] = ''

    # Salvo l'edamame food
    insert = "INSERT INTO edamam_foods (edamam_food_id, label, known_as, nutrients_json, category, category_label, me_json_response, image) " \
             "VALUES ('" + parsed['food']['foodId'] + "', '" + escapeString(parsed['food']['label']) + "', '" + \
             escapeString(parsed['food']['knownAs']) + "', '" + \
             escapeString(json.dumps(parsed['food']['nutrients'])) + "', '" + escapeString(parsed['food']['category']) + "', '" + \
             parsed['food']['categoryLabel'] + "', '" \
             + escapeString(json.dumps(parsed)) + "', '" + parsed['food']['image'] + "');"

    print(insert)

    cursor.execute(insert)



def escapeString(str):
    return re.escape(str).replace("'", "")