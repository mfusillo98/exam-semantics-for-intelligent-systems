import os

import data_mining.category_foodb_cluster
import feature_extraction.recipe1m
import feature_extraction.foodb
import feature_extraction.my_emission
import feature_extraction.my_emission_edamam
import feature_extraction.healabel_water_foodprint
import data_mining.recipe1m
import feature_extraction.corgis
import data_mining.category_corgis_cluster

os.environ['DB_USER'] = 'root'
os.environ['DB_PASSWORD'] = 'root'
os.environ['DB_HOST'] = 'localhost'
os.environ['DB_PORT'] = '8889'
os.environ['DB_DATABASE'] = 'semantics_our_schema'


# This is a sample Python script.
# Press ⌃R to execute it or replace it with your code.
# Press Double ⇧ to search everywhere for classes, files, tool windows, actions, and settings.

def main():
    # FEATURE EXTRACTION
    # feature_extraction.recipe1m.import_1m_recipes()
    # feature_extraction.recipe1m.import_1m_recipes_ingredients()
    # feature_extraction.recipe1m.fetch_1m_recipes_edamam_foods()
    # feature_extraction.my_emission.import_my_emission()
    # feature_extraction.my_emission.fetch_me_food_edamam_foods()
    # feature_extraction.foodb.fetch_foodb_edamam_foods()
    # feature_extraction.healabel_water_foodprint.import_healabel_water_foodprint()
    feature_extraction.healabel_water_foodprint.fetch_healabel_edamam_foods()
    # feature_extraction.corgis.import_ingredients()
    # feature_extraction.corgis.fetch_foodb_edamam_foods()
    # DATA MINING
    # data_mining.recipe1m.estimate_co2_by_hints_similarity()
    # data_mining.category_foodb_cluster.create_category_cluster()
    # data_mining.category_foodb_cluster.save_category_cluster()
    # data_mining.category_corgis_cluster.create_co2_category_cluster()
    # data_mining.category_corgis_cluster.save_co2_category_cluster()
    # data_mining.category_corgis_cluster.create_h2o_category_cluster()
    # data_mining.category_corgis_cluster.save_h2o_category_cluster()
    pass


# Press the green button in the gutter to run the script.
if __name__ == '__main__':
    main()

# See PyCharm help at https://www.jetbrains.com/help/pycharm/
