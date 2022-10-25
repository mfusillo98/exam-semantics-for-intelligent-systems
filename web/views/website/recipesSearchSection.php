<div class="row mb-5">
    <div class="col-md-3 col-sm-0"></div>
    <div class="col-md-6 text-center">
        <h2 class="text-primary">Find recipes! </h2>
        <span class="text-muted">What you want to cook today? Find your favorite no-emission food!</span>
        <div class="input-group shadow rounded-3 mt-3">
            <input type="search" class="form-control px-4" id="searchBar" placeholder="Type an ingredient..." style="max-height: 3em !important;"/>
            <button type="button" class="btn btn-primary m-0">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
    <div class="col-md-3 col-sm-0"></div>

    <!-- before research -->
    <div class="col-12 mt-4">
        <div class="card card-body shadow-sm text-center">
            <img src="<?= asset('img/searchIngredientsBlankImg.svg') ?>" width="250" style="margin: auto">
            <span class="text-muted mt-2">Here you will see your results...</span>
        </div>
    </div>

    <!-- search container -->
    <div class="col-12" id="results-container">

    </div>
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

    //Gestisce il timeout della ricerca
    $('#searchBar').on('keyup', function () {
        clearTimeout(typingTimer);
        if (this.value.length > 1) {
            typingTimer = setTimeout(_ => {
                showWelcomeContainer(false)
                doSearch(this.value)
            }, doneTypingTimer);
        }else {
            let container = document.getElementById('results-container')
            container.innerHTML = "";
            showWelcomeContainer(true)
        }
    });

    function doSearch(query) {
        let container = document.getElementById('results-container')
        container.innerHTML = "";

        FuxCursorPaginator({
            container: container,
            onItemRender: function (course) {
                console.log(course)
                const el = document.createElement('div');
                const startDate = moment(course.start_date);
                const endDate = moment(course.end_date);
                el.innerHTML = `
                    <a class="list-group-item mb-3 list-group-item-action shadow-sm" data-toggle="collapse" data-target="#collapse-${course.course_id}" aria-expanded="false" aria-controls="collapse-${course.course_id}" style="cursor: pointer">
                        <h3 class="font-weight-bold m-0">
                            ${course.title}
                        </h3>
                        <div class="mb-3">${course.description}</div>
                        <div id="collapse-${course.course_id}" class="collapse">
                            <div>${course.program}</div>
                        </div>
                        <i class="fas fa-caret-down"></i>
                        <span class="text-muted">
                            Data di rilascio: ${startDate.format('DD-MM-YYYY')}
                        </small>
                    </a>
                `;
                return el;
            },
            onPageRequest: function (cursor) {
                return new Promise((resolve, reject) => {
                    const url = `<?= routeFullUrl("/recipes-search/do-search") ?>`;
                    FuxHTTP.get(url, {
                        query: query,
                        cursor: cursor
                    }, FuxHTTP.RESOLVE_DATA, FuxHTTP.REJECT_MESSAGE)
                        .then(paginationPage => resolve(paginationPage))
                        .catch(FuxSwalUtility.error);
                });
            },
            onEmptyPage: function () {
                const el = document.createElement('div');
                el.innerHTML = `
                    <div class="card shadow-sm text-center w-100 p-3 border-0">
                        <h5>Non ci sono corsi con questo nome 🤨</h5>
                        <h2 class="text-primary font-weight-bold">Cerca ancora! 💪🏼</h2>
                    </div>
                `;
                return el;
            }
        })
    }

    function showWelcomeContainer(show){
        if(show){
            $("#search-welcome-container").removeClass("d-none")
        }else {
            $("#search-welcome-container").addClass("d-none")
        }
    }

</script>
