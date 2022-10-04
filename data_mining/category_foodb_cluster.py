import json
import os
import database.connection
import database.query

def create_category_cluster():
    connection = database.connection.get_connection(os.environ.get('DB_DATABASE'))
    cursor = connection.cursor()

    cursor.execute('SELECT food_group, food_subgroup, me.edamam_food_id, emissions '
                   'FROM foodb_foods fdb '
                   'LEFT JOIN me_foods me ON fdb.edamam_food_id = me.edamam_food_id '
                   'WHERE fdb.edamam_food_id IS NOT NULL AND me.me_food_id IS NOT NULL AND food_group IS NOT NULL '
                   'GROUP BY food_group, food_subgroup, me.edamam_food_id, emissions')
    categoryAndEmission = cursor.fetchall()

    categoryCluster = {}
    for row in categoryAndEmission:
        #Definisco il gruppo se non esiste
        if row["food_group"] not in categoryCluster:
            categoryCluster[row["food_group"]] = {}

        #Definisco il sottogruppo se non esiste
        if row["food_subgroup"] not in categoryCluster[row["food_group"]]:
            categoryCluster[row["food_group"]][row["food_subgroup"]] = {"items":[]}

        categoryCluster[row["food_group"]][row["food_subgroup"]]["items"].append({
            "edamam_food_id": row["edamam_food_id"],
            "emissions": row["emissions"]
        })

    for group in categoryCluster.keys():
        for subgroup in categoryCluster[group]:
            maxVal = None
            minVal = None
            avg = 0
            for item in categoryCluster[group][subgroup]["items"]:
                emissionValue = 0 if item["emissions"] == ''else float(item["emissions"])
                if not maxVal or emissionValue > maxVal:
                    maxVal = emissionValue
                if not minVal or emissionValue < minVal:
                    minVal = emissionValue
                avg += emissionValue
            avg = avg/len(categoryCluster[group][subgroup]["items"])

            categoryCluster[group][subgroup]["max_value"] = maxVal
            categoryCluster[group][subgroup]["min_value"] = minVal
            categoryCluster[group][subgroup]["avg"] = avg


    json.dumps(categoryCluster)
    with open("data_mining/category_cluster.json", "w+") as f:
        json.dump(categoryCluster, f)