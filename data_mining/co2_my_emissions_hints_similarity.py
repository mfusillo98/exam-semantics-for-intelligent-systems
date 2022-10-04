import json
import os
import database.connection
import filemanager
from scipy import spatial


# Recupera un array di 0 e 1 che rappresentano la presenza dell'i-esimo edamam food id come hint di "type_id"
# Gli edamam_food_id sono ordinati alfabeticamente
def get_hints_feature_vector(cursor, type, type_id):
    sql = "SELECT f.edamam_food_id, IF(eh.type IS NULL, 0, 1) as hint FROM edamam_foods f " \
          "left join edamam_hints eh on f.edamam_food_id = eh.edamam_food_id AND eh.type = %s AND eh.type_id = %s " \
          "ORDER BY f.edamam_food_id"
    cursor.execute(sql, (type, type_id))
    return cursor.fetchall()


def get_me_food_emission(cursor, me_food_id):
    cursor.execute("SELECT * FROM me_foods WHERE me_food_id = %s", (me_food_id))
    food = cursor.fetchone()
    return float(food['emissions'])


def get_my_emissions_feature_vector(cursor):
    filedir = filemanager.get_abs_path('/my_emissions_edamam_hints_feature_vectors.json')
    if os.path.exists(filedir):
        with open(filedir) as f:
            return json.load(f)
    print("Getting My Emissions feature vectors");
    cursor.execute("SELECT me_food_id,edamam_food_id,emissions FROM me_foods where edamam_food_id IS NOT NULL")
    foods = cursor.fetchall()
    for i in range(0, len(foods)):
        print(f"> me_food_id = {foods[i]['me_food_id']}")
        foods[i]['features'] = [f['hint'] for f in get_hints_feature_vector(cursor, "me", foods[i]['me_food_id'])]
    with open(filedir, "w") as f:
        json.dump(foods, f)
    print(f"Comptued JSON file stored in {filedir}")
    return foods


'''
L'obiettivo è di stimare l'emissione di CO2 di un food di 1M Recipe basandosi sulla similarity degli hints
tra gli ingredienti di 1M Recipes e i food di my emissions. Per ogni ingrediente target calcolo la similarity con i 
food di my emissions e considero solo i food my emissions con similarity >= similarity_threshold. 
A questo punto ho un elenco di N possibili valori di CO2 da assegnare all'ingrediente e ho 2 alternative.
- basarmi sui 3 valori di emissione di CO2: min, max e mean.
- creare una distribuzione in (N/2.5) classi (sulla base di un intervallo di valori) e prendere il valore medio 
  rappresentativo della classe con frequenza più alta [Ampiezza intervallo = (vmax - vmin)/class num]
'''


def estimate_co2(cursor, my_emissions_food, type, type_id, similarity_thresholds):
    target_features = [f['hint'] for f in get_hints_feature_vector(cursor, type, type_id)]
    similar_foods = [[] for _ in similarity_thresholds]  # Vettore parallelo di similarity_thresholds
    max_similarity = 0
    print("> Computing similarities...")
    for f in my_emissions_food:
        similarity = 1 - spatial.distance.cosine(target_features, f['features'])
        max_similarity = max(similarity, max_similarity)
        for i in range(0, len(similarity_thresholds)):
            if similarity >= similarity_thresholds[i]:
                similar_foods[i].append(
                    {'me_food_id': f['me_food_id'], 'emissions': f['emissions'], 'similarity': similarity})

    for i in range(0, len(similarity_thresholds)):
        print(f"> Similarities threshold: {similarity_thresholds[i]} | # Similar foods: {len(similar_foods[i])} | Max. similarity: {max_similarity}")

    results = [{} for _ in similarity_thresholds]  # Vettore parallelo di similarity_thresholds
    for i in range(0, len(similarity_thresholds)):
        foods_emissions = [float(f['emissions']) for f in similar_foods[i]]
        if len(foods_emissions) == 0:
            results[i] = {
                'similar_foods': similar_foods[i],
                'min': None,
                'max': None,
                'mean': None,
                'classes': [],
                'mean_max_freq_class': None
            }
            continue

        # Stima attraverso classi di distribuzione
        most_frequent_class = None
        classes = []
        minV = min(foods_emissions)
        maxV = max(foods_emissions)
        if len(foods_emissions) > 2:
            distribution_classes_size = (maxV - minV) / (round(len(foods_emissions) / 2.5, 0))
            cmin = minV  # La prima classe ha estremo sinistro = minV
            while cmin + distribution_classes_size <= maxV:
                cmax = cmin + distribution_classes_size
                classes.append({
                    'min': cmin,
                    'max': cmax,
                    'label': f"{cmin} - {cmax}",
                    'values': [e for e in foods_emissions if cmax <= e <= cmax]
                })
            for c in classes:
                if most_frequent_class is None or len(most_frequent_class['values']) < len(c['values']):
                    most_frequent_class = c
        results[i] = {
            'similar_foods': similar_foods[i],
            'min': minV,
            'max': maxV,
            'mean': sum(foods_emissions) / len(foods_emissions),
            'classes': classes,
            'mean_max_freq_class': sum(most_frequent_class['values']) / len(
                most_frequent_class['values']) if most_frequent_class is not None else None
        }

    return results
