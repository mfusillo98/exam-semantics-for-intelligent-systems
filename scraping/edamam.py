import os
import time
import requests
import json
import re
from requests.adapters import HTTPAdapter
from urllib3.util.retry import Retry
import database.connection
import database.query

EDAMAM_APP_IDS = ['8de831c7', 'b2b2ff8c', '2d1436b7', 'bf0f3fa3', '31f990a9', '5ef93b87', 'f94333b0',
                  'd1322d3f', 'a26c727d', '26994147', '025345d4']
EDAMAM_APP_KEYS = ['253633c4114caf3068426f4c86d80b5f', '00b08e8c9f0f90f99bbfca5896f48ad9',
                   '846bcc5c8e9342a65c98e867cef1463f',
                   '827eea357594b7c94129cc9fec8d98ca', '7a1bf9c67f526ce35b7c2f7440860d3c',
                   'e6c1a31045584eeeecc2a8500ab7e75d', '9c79175ba2af0f2603958ef7e52c63a2',
                   '462a327a199d1b77994116c07b9406ee', '0e815c23b1c55c2a97a00be4ea018d79',
                   '9705a737a2daecd8b773aed6477b7cbe', '598e99ef16de1c01918e4e5e44257006']

os.environ['EDAMAM_APP_ID'] = EDAMAM_APP_IDS[0]
os.environ['EDAMAM_APP_KEY'] = EDAMAM_APP_KEYS[0]

app_index = 0
app_index_requests = 0
app_request_limit = 50


# Matteo Fusillo
def fetch_edamam_food(ingredients):
    global app_index_requests, app_index, app_request_limit
    if app_index_requests >= app_request_limit:
        app_index = app_index + 1
        app_index_requests = 0
        os.environ['EDAMAM_APP_ID'] = EDAMAM_APP_IDS[app_index % len(EDAMAM_APP_IDS)]
        os.environ['EDAMAM_APP_KEY'] = EDAMAM_APP_KEYS[app_index % len(EDAMAM_APP_IDS)]
        print(f"Reached {app_index_requests} requests, switching to APP_ID={os.environ.get('EDAMAM_APP_ID')}")
    app_index_requests = app_index_requests + 1
    # Richiesta emadame
    session = requests.Session()
    retry = Retry(connect=3, backoff_factor=0.5)
    adapter = HTTPAdapter(max_retries=retry)
    session.mount('http://', adapter)
    session.mount('https://', adapter)

    params = {'app_id': os.environ.get('EDAMAM_APP_ID'), 'app_key': os.environ.get('EDAMAM_APP_KEY'),
              'ingr': ingredients}
    edamam_response = session.get('https://api.edamam.com/api/food-database/v2/parser', params=params)

    # Se la risposta è positiva proseguo
    if (edamam_response.status_code != 200):
        print(f"Richiesta con {ingredients} non riuscita")
        return False
    else:
        return json.loads(edamam_response.content)


# Matteo Fusillo
def save_edamam_food(parsed, cursor):
    recordData = {
        "edamam_food_id": parsed['food']['foodId'],
        "label": parsed['food']['label'],
        "known_as": parsed['food']['knownAs'],
        "nutrients_json": json.dumps(parsed['food']['nutrients']),
        "category": parsed['food']['category'],
        "category_label": parsed['food']['categoryLabel'],
        "me_json_response": json.dumps(parsed),
        "image": parsed['food']['image'] if 'image' in parsed['food'] else ''
    }
    database.query.insert(cursor, 'edamam_foods', recordData, True)


def escapeString(str):
    return re.escape(str).replace("'", "")
