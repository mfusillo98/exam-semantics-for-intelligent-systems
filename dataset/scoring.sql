-- Calcolo minimo e massimo di ogni categoria di emissione
SELECT emissions_category_letter, MIN(emissions), MAX(emissions)
FROM me_foods
GROUP BY emissions_category_letter

-- A = 0.0 : 0.1650
-- B = 0.1651 : 0.2750
-- C = 0.2751 : 0.3900
-- D = 0.3910 : 0.5000
-- E = 0.5000 : ∞

-- Calcolo dello score statico di ogni ricetta

-- PARAMETRI
-- Emissione media per ogni ingrediente della ricetta "emi" = 1/Nv • ∑emissions con Nv = numero di ingredienti di cui conosco l'emissione
-- Trustness della ricetta per CO2 (% ingredienti della ricetta con valore emissione espressa in intervallo 0÷1) "trust_co2" = Nv / N, Nv = numero di ingredienti di cui conosco l'emissione, N = numero di ingredienti della ricetta
-- Water footprint media per ogni ingrediente della ricetta "wmi" = 1/Nv • ∑emissions con Nv = numero di ingredienti di cui conosco wfp
-- Trustness della ricetta per WFP (% ingredienti della ricetta con valore wfp espressa in intervallo 0÷1) "trust_h2o" = Nv / N, Nv = numero di ingredienti di cui conosco wfp, N = numero di ingredienti della ricetta
-- is_meat_free = 1 : 0

-- Score (lower is better) = (emi / trust_co2) + (wmi / trust_h2o)