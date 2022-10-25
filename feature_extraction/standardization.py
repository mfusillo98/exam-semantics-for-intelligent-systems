import json
import os
import time

import pymysql

import scraping.edamam
import database.connection
import database.query


def populate_recipes_ingredients_pivot():
    connection = database.connection.get_connection('food_print')
    cursor = connection.cursor()
    cursor.execute('SELECT * FROM food_print.ingredients')
    ingredients = cursor.fetchall()
    for ingredient in ingredients:
        ingredient_recipes = json.loads(ingredient['vendor_recipe_ids'])
        for r in ingredient_recipes:
            cursor.execute('SELECT * FROM food_print.recipes WHERE vendor_id = %s', (r['recipe_id']))
            recipe = cursor.fetchone()
            data = {
                'recipe_id': recipe['recipe_id'],
                'ingredient_id': ingredient['ingredient_id'],
                'ingredient_index': r['idx']
            }
            database.query.insert(cursor, 'food_print.ingredients_recipes', data, True)
            print(data)
            connection.commit()
