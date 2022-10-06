SELECT r.1m_recipe_id, i.*
FROM 1m_recipe r
LEFT JOIN 1m_recipes_ingredients i ON r.1m_recipe_id = i.1m_recipe_id;