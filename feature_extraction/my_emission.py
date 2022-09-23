import json
import pymysql

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
