<style>
    .loader {
        margin: auto;
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #e92564; /* Primary */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

</style>


<div class="row mb-5">
    <div class="col-md-3 col-sm-0"></div>
    <div class="col-md-6 text-center">
        <h2 class="text-primary">Find recipes! </h2>
        <span class="text-muted">What you want to cook today? Find your favorite no-emission food!</span>
        <div class="input-group shadow rounded-3 mt-3">
            <input type="search" class="form-control px-4" id="searchBar" placeholder="Type an ingredient... (e.g. chicken, tomato)"
                   style="max-height: 3em !important;"/>
            <button type="button" class="btn btn-primary m-0">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <div class="d-flex align-items-center my-3">
            <b>
                More popular
            </b>
            <div class="flex-grow-1 mx-3">
                <input type="range" min="0" max="100" value="100" class="w-100" id="sustainabilityWeight"/>
            </div>
            <b>
                More sustainable
            </b>
        </div>
    </div>
    <div class="col-md-3 col-sm-0"></div>

    <!-- before research -->
    <div class="col-12 mt-4" id="before-search-container">
        <div class="card card-body shadow-sm text-center">
            <img src="<?= asset('img/searchIngredientsBlankImg.svg') ?>" width="250" style="margin: auto">
            <span class="text-muted mt-2">Here you will see results...</span>
        </div>
    </div>

    <!-- loader container -->
    <div class="col-12 mt-4 loader d-none" id="loader-container"></div>

    <!-- search container -->
    <div class="col-12 mt-4" id="results-container"></div>
</div>

<?= assetOnce('/lib/FuxFramework/AsyncCrud.js', "script") ?>
<?= assetOnce('/lib/FuxFramework/FuxUtility.js', "script") ?>
<?= assetOnce('/lib/FuxFramework/FuxHTTP.js', "script") ?>
<?= assetOnce('/lib/FuxFramework/FuxSwalUtility.js', "script") ?>
<?= assetOnce('/lib/FuxFramework/FuxCursorPaginator.js', "script") ?>
<?= assetOnce('/lib/moment/moment.js', "script") ?>

<script>

    let typingTimer = null
    let doneTypingTimer = 300
    const queryInput = document.getElementById('searchBar');
    const sustainabilityRange = document.getElementById('sustainabilityWeight');
    const container = document.getElementById('results-container');

    queryInput.addEventListener('keyup', handleSearchParamsChange);
    sustainabilityRange.addEventListener('change', handleSearchParamsChange);

    function handleSearchParamsChange() {
        clearTimeout(typingTimer);
        if (queryInput.value.length > 1) {
            typingTimer = setTimeout(_ => {
                showBeforeSearchContainer(false)
                doSearch(queryInput.value, parseInt(sustainabilityRange.value))
            }, doneTypingTimer);
        } else {
            container.innerHTML = "";
            showBeforeSearchContainer(true)
        }
    }

    function doSearch(query, sustainabilityWeight) {
        container.innerHTML = "";

        FuxCursorPaginator({
            container: container,
            onItemRender: function (recipes) {
                console.log(recipes)
                $("#loader-container").addClass("d-none")
                return printRecipesUtils(recipes)
            },
            onPageRequest: function (cursor) {
                $("#loader-container").removeClass("d-none")
                return new Promise((resolve, reject) => {
                    const url = `<?= routeFullUrl("/recipes-search/do-search") ?>`;
                    FuxHTTP.get(url, {
                        query: query,
                        sustainabilityWeight: sustainabilityWeight,
                        cursor: cursor,
                        useCfi: <?=$useCfi ?? 0?>
                    }, FuxHTTP.RESOLVE_DATA, FuxHTTP.REJECT_MESSAGE)
                        .then(paginationPage => resolve(paginationPage))
                        .catch(FuxSwalUtility.error);
                });
            },
            onEmptyPage: function () {
                $("#loader-container").addClass("d-none")
                const el = document.createElement('div');
                el.innerHTML = `
                    <div class="card shadow-sm text-center w-100 p-3 border-0">
                        <h5>We have not yet recipes with this ingredient 🤨</h5>
                        <h2 class="text-primary font-weight-bold">Try with some other (e.g. apple, cheese)! 💪🏼</h2>
                    </div>
                `;
                return el;
            }
        })
    }

    function showBeforeSearchContainer(show) {
        if (show) {
            $("#before-search-container").removeClass("d-none")
        } else {
            $("#before-search-container").addClass("d-none")
        }
    }

    function printRecipesUtils(recipe){
        const el = document.createElement('div');
        const createdAt = moment(recipe.created_at);

        el.innerHTML = `
                    <a class="card card-body shadow-sm border-0 my-2" style="cursor: pointer" href="${recipe.url}" target="_blank">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="font-weight-bold m-0" style="font-size: 25px">${recipe.title}</div>
                            <div>
                                <small>
                                    ${recipe.sustainability_score <= 0.10 ?
            `<span class="btn btn-success btn-sm">Very sustainable</span>` :
            recipe.sustainability_score <= 0.50 ?
                `<span class="btn btn-warning btn-sm">Sustainable</span>` :
                `<span class="btn btn-danger btn-sm">Not sustainable</b>`
        }
                                </small>
                                <small class="btn btn-info btn-sm"><i class='fas fa-star'></i> ${recipe.rating || "No rating"} (${recipe.rating_count || 0} reviews)</small>
                            </div>
                        </div>
                        <span class="mb-3">
                              ${
                                    recipe.ingredients_list.map(i =>{
                                        let color = i.carbon_foot_print >= 3 ? "text-danger" : i.carbon_foot_print <= 1 ? "text-success" : ""
                                        return `<span class="${color}">${i.name} </span>`
                                    })
                                }
                        </span>
                        <small class="text-muted" style="font-size: 10px">
                            Inserted at: ${createdAt.format('DD-MM-YYYY')}<br>
                            Taken from: ${recipe.url}
                        </small>
                    </a>
                `;
        return el;
    }

</script>
