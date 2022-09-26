import os
import feature_extraction.recipe1m
import feature_extraction.my_emission
import feature_extraction.my_emission_edamam

os.environ['DB_USER'] = 'root'
os.environ['DB_PASSWORD'] = 'root'
os.environ['DB_HOST'] = 'localhost'
os.environ['DB_PORT'] = '8889'
os.environ['DB_DATABASE'] = 'semantics_our_schema'

# This is a sample Python script.
# Press ⌃R to execute it or replace it with your code.
# Press Double ⇧ to search everywhere for classes, files, tool windows, actions, and settings.

def main():
    #feature_extraction.recipe1m.import_1m_recipes()
    #feature_extraction.recipe1m.import_1m_recipes_ingredients()
    #feature_extraction.my_emission.import_my_emission()
    feature_extraction.my_emission_edamam.my_emission_edamam()
    pass


# Press the green button in the gutter to run the script.
if __name__ == '__main__':
    main()

# See PyCharm help at https://www.jetbrains.com/help/pycharm/
