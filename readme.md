- Sistema di regressione per calcolo emissioni Co2 degli ingredienti del dataset nel DB, 
  sulla base della categoria di appartenenza del cibo target usando come test set l'insieme dei
  cibi nella stessa categoria di cui conosciamo l'emissione
  

TODO per 05/10/2022
- Usare le categorie foodb per raggruppare i prodotti di 1M recipe (di cui conosciamo o meno l'emissione di CO2, ma di 
  cui conosciamo il food id edamam). Per ogni prodotto di cui non conosciamo l'emissione troviamo N prodotti più simili 
  di cui conosciamo l'emissione. La similarità è la cosine similarity sul query vector degli hints edamam. Con i valori
  dell'emissione degli N elementi possiamo recuperare 3 valore min, max e mean
  