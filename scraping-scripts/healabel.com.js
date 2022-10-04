
let ingredients = Array.from(document.querySelectorAll("ul a[data-lasso-id]")).map(el => {
    return {
        lasso_id: el.getAttribute('data-lasso-id'),
        url: el.href,
        name: el.innerHTML,
        liters_per_kg: el.parentNode.querySelector('strong').innerHTML.replace('liters','').replace(',','.').trim()
    }
})