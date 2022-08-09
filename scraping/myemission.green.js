const ingredients = Array.from($("#ingredients-list0").get(0).querySelectorAll('option')).map(o => {
    return {id: o.value, label: o.innerHTML};
});

ingredients.map((ingredient, i) => {
    fetch("https://app.myemissions.green/api/v1/calculator/", {
        "headers": {
            "accept": "*/*",
            "accept-language": "it,en-US;q=0.9,en;q=0.8",
            "content-type": "application/json",
        },
        "body": JSON.stringify({
            "ingredients": [{
                "food": ingredient.id,
                "unit": "17b6249c-cbda-4e59-b575-018f7781c68c",
                "amount": "100"
            }], "servings": 1
        }),
        "method": "POST",
        "mode": "cors",
    })
        .then(response => response.json())
        .then(json => {
            ingredients[i].emissionsData = json;
        });
})

