import json
import os

import pymysql

import database.connection
import database.query


# ---- CO2 ----

def create_co2_category_cluster():
    connection = database.connection.get_connection(os.environ.get('DB_DATABASE'))
    cursor = connection.cursor()

    cursor.execute('SELECT category, me.edamam_food_id, emissions '
                   'FROM corgis_ingredients ci '
                   'LEFT JOIN me_foods me ON ci.edamam_food_id = me.edamam_food_id '
                   'WHERE ci.edamam_food_id IS NOT NULL AND me.me_food_id IS NOT NULL AND category IS NOT NULL '
                   'GROUP BY category, me.edamam_food_id, emissions')
    categoryAndEmission = cursor.fetchall()

    categoryCluster = {}
    for row in categoryAndEmission:
        # Definisco il gruppo se non esiste
        if row["category"] not in categoryCluster:
            categoryCluster[row["category"]] = {"items": []}

        categoryCluster[row["category"]]["items"].append({
            "edamam_food_id": row["edamam_food_id"],
            "emissions": row["emissions"]
        })

    for category in categoryCluster.keys():
        maxVal = None
        minVal = None
        avg = 0
        for item in categoryCluster[category]["items"]:
            emissionValue = 0 if item["emissions"] == '' else float(item["emissions"])
            if not maxVal or emissionValue > maxVal:
                maxVal = emissionValue
            if not minVal or emissionValue < minVal:
                minVal = emissionValue
            avg += emissionValue
        avg = avg / len(categoryCluster[category]["items"])

        categoryCluster[category]["max_value"] = maxVal
        categoryCluster[category]["min_value"] = minVal
        categoryCluster[category]["avg"] = avg

    json.dumps(categoryCluster)
    with open("data_mining/category_corgis_co2_cluster.json", "w+") as f:
        json.dump(categoryCluster, f)


def save_co2_category_cluster():
    f = open("data_mining/category_corgis_co2_cluster.json")
    categoryCluster = json.load(f)

    connection = database.connection.get_connection(os.environ.get('DB_DATABASE'))
    cursor = connection.cursor(pymysql.cursors.SSDictCursor)

    for category in categoryCluster:
        database.query.insert(cursor, 'corgis_category_emissions_h2o', {
            "category": category,
            "emissions_max_value": categoryCluster[category]["max_value"],
            "emissions_min_value": categoryCluster[category]["min_value"],
            "emissions_avg": categoryCluster[category]["avg"]})

        print(categoryCluster[category])

    connection.commit()
    connection.close()


# ---- H2O ----

def create_h2o_category_cluster():
    connection = database.connection.get_connection(os.environ.get('DB_DATABASE'))
    cursor = connection.cursor()

    cursor.execute('SELECT category, h.edamam_food_id, max(liters_per_kg) as liters_per_kg '
                   'FROM corgis_ingredients ci '
                   'LEFT JOIN healabel_water_foodprint h ON ci.edamam_food_id = h.edamam_food_id '
                   'WHERE ci.edamam_food_id IS NOT NULL AND h.edamam_food_id IS NOT NULL AND category IS NOT NULL '
                   'GROUP BY category, h.edamam_food_id')
    categoryAndEmission = cursor.fetchall()

    categoryCluster = {}
    for row in categoryAndEmission:
        # Definisco il gruppo se non esiste
        if row["category"] not in categoryCluster:
            categoryCluster[row["category"]] = {"items": []}

        categoryCluster[row["category"]]["items"].append({
            "edamam_food_id": row["edamam_food_id"],
            "liters_per_kg": row["liters_per_kg"]
        })

    for category in categoryCluster.keys():
        maxVal = None
        minVal = None
        avg = 0
        for item in categoryCluster[category]["items"]:
            h2oVal = 0 if item["liters_per_kg"] == '' else float(item["liters_per_kg"])
            if not maxVal or h2oVal > maxVal:
                maxVal = h2oVal
            if not minVal or h2oVal < minVal:
                minVal = h2oVal
            avg += h2oVal
        avg = avg / len(categoryCluster[category]["items"])

        categoryCluster[category]["max_value"] = maxVal
        categoryCluster[category]["min_value"] = minVal
        categoryCluster[category]["avg"] = avg

    json.dumps(categoryCluster)
    with open("data_mining/category_corgis_h2o_cluster.json", "w+") as f:
        json.dump(categoryCluster, f)


def save_h2o_category_cluster():
    f = open("data_mining/category_corgis_h2o_cluster.json")
    categoryCluster = json.load(f)

    connection = database.connection.get_connection(os.environ.get('DB_DATABASE'))
    cursor = connection.cursor(pymysql.cursors.SSDictCursor)

    for category in categoryCluster:
        database.query.update(cursor, 'corgis_category_emissions_h2o', {
            "liters_per_kg_max_value": categoryCluster[category]["max_value"],
            "liters_per_kg_h2o_min_value": categoryCluster[category]["min_value"],
            "liters_per_kg_h2o_avg": categoryCluster[category]["avg"]},
                              {'category': category})

        print(categoryCluster[category])

    connection.commit()
    connection.close()
