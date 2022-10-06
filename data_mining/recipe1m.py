import json
import os
import pymysql
import database.connection
import database.query
import data_mining.co2_my_emissions_hints_similarity


def estimate_co2_by_hints_similarity():
    connection = database.connection.get_connection(os.environ.get('DB_DATABASE'))
    unbuffered_cursor = connection.cursor(pymysql.cursors.SSDictCursor)

    connection2 = database.connection.new_connection(os.environ.get('DB_DATABASE'))
    cursor = connection2.cursor()

    print("Retrieving My emissions food's features vector")
    my_emissions_foods = data_mining.co2_my_emissions_hints_similarity.get_my_emissions_feature_vector(cursor)
    unbuffered_cursor.execute(
        'SELECT * FROM `1m_recipes_ingredients` WHERE valid = 1 AND edamam_food_id IS NOT NULL AND co2_raw_data_hints_similarity IS NULL GROUP BY text')
    counter = 1
    for row in unbuffered_cursor:
        print(f"{counter}) Estimating {row['text']}")
        results = data_mining.co2_my_emissions_hints_similarity.estimate_co2(cursor, my_emissions_foods, '1m',
                                                                             row['text'],
                                                                             [0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9])
        threshold_idx = 3
        database.query.update(cursor, '1m_recipes_ingredients', {
            'co2_min_by_hints_similarity': results[threshold_idx]['min'],
            'co2_max_by_hints_similarity': results[threshold_idx]['max'],
            'co2_mean_by_hints_similarity': results[threshold_idx]['mean'],
            'co2_mean_max_freq_class_by_hints_similarity': results[threshold_idx]['mean_max_freq_class'],
            'co2_raw_data_hints_similarity': json.dumps(results)
        }, {'text': row['text']})
        connection2.commit()
        counter = counter + 1
