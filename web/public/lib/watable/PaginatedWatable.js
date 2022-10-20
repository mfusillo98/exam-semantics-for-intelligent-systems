/**
 * Permette di creare un wrapper attorno alla watable classica, nascondendo la paginazione di default e,
 * intercettando tutti gli eventi sugli elementi, simula una paginazione con gestione server side
 *
 *
 * */

function PaginatedWatable(element, options) {

    /** Additional syles */
    var css = '.__paginatedWatable tfoot { display:none; }' +
        '.__paginatedWatable { position:relative; }' +
        '.__paginatedWatable-loading{ position:fixed; left:0; top:0; right:0; bottom:0; background-color:rgba(255,255,255,0.7); }';
    var head = document.head || document.getElementsByTagName('head')[0];
    var style = document.createElement('style');
    head.appendChild(style);
    style.appendChild(document.createTextNode(css));
    /** Additional styles end */


    var watableOptions = Object.assign({}, options);
    watableOptions.url = null;
    watableOptions.urlData = null;
    watableOptions.tableCreated = bindEvents;

    var $watableContainer = $("<div class='table-responsive'>");
    var $paginationTopContainer = $('<div class="__watablePagination">');
    var $paginationBottomContainer = $('<div class="__watablePagination">');
    var $tableLoader = $("<div class='__paginatedWatable-loading d-flex align-items-center justify-content-center'><i class='fa fa-2x fa-pulse fa-spinner'></i></div>");

    $(element).append($paginationTopContainer);
    $(element).append($watableContainer);
    $(element).append($paginationBottomContainer);
    $(element).append($tableLoader);

    var watable = $($watableContainer).WATable(watableOptions).data("WATable");
    var $paginations = $(element).find('.__watablePagination');
    var lastData = null;

    element.classList.add('__paginatedWatable');

    var searchFilters = {};
    var orderFilters = {};
    var currentPage = 1;

    function setWatableData(data) {
        watable.setData(data, true);
        //Refresh dei valori delle ricerche
        var $tableHeadings = $(element).find('table .sort th');
        var $tableHeadingsFilters = $(element).find('table .filter th');
        __setSearchInputValues();
        __setOrderingColumnsIcon();

        function __setSearchInputValues() {
            for (var columnName in searchFilters) {
                if (searchFilters.hasOwnProperty(columnName) && data.cols.hasOwnProperty(columnName)) {
                    var friendly = data.cols[columnName].friendly; //Recupero l'intestazione della colonna
                    //Cerco fra tutte le intestazioni quelle con lo stesso testo
                    $tableHeadings.each(function () {
                        if ($(this).text() === friendly) {
                            $tableHeadingsFilters.eq($(this).index()).find('input').val(searchFilters[columnName]);
                        }
                    })
                }
            }
        }

        function __setOrderingColumnsIcon() {
            $tableHeadings.find('i').remove(); //Rimuovo tutte le icone

            console.log(orderFilters);

            for (var columnName in orderFilters) {
                if (orderFilters.hasOwnProperty(columnName) && data.cols.hasOwnProperty(columnName)) {
                    var friendly = data.cols[columnName].friendly; //Recupero l'intestazione della colonna
                    //Cerco fra tutte le intestazioni quelle con lo stesso testo
                    $tableHeadings.each(function () {
                        console.log($(this).text(), "===", friendly)
                        if ($(this).text() === friendly) {
                            $tableHeadings.eq($(this).index()).append(
                                orderFilters[columnName] === "ASC" ?
                                    "<i class='fa fa-sort-up ml-2'></i>" :
                                    "<i class='fa fa-sort-down ml-2'></i>"
                            );
                        }
                    })
                }
            }
        }
    }

    function bindEvents() {
        var typingInterval = null;
        /** Binding search input events */
        $(element).find('table thead input').off().on('keyup', function (event) {
            const elementInput = $(this).get(0);
            var $tableHeadings = $(element).find('table .sort th');
            if (typingInterval) {
                clearInterval(typingInterval);
            }
            var thIndex = $(event.target).parents('th').index();
            var friendlyText = $tableHeadings.eq(thIndex).text();
            var tableCols = watable.getData().cols;
            for (var key in tableCols) {
                if (tableCols.hasOwnProperty(key)) {
                    if (tableCols[key].friendly === friendlyText) {
                        searchFilters[key] = event.target.value;
                    }
                }
            }

            typingInterval = setTimeout(_ => {
                doSearch(_ => {
                    const input = $(element).find('table .filter th').eq(thIndex).find('input');
                    input.focus();
                });
            }, 300);

        });

        /** Binding ordering events */
        $(element).find('table thead a').off().on('click', function (event) {
            console.log('hey');
            event.preventDefault();
            var $tableHeadings = $(element).find('table .sort th');
            var thIndex = $(event.target).parents('th').index();
            var friendlyText = $tableHeadings.eq(thIndex).text();
            var tableCols = watable.getData().cols;
            for (var key in tableCols) {
                if (tableCols.hasOwnProperty(key)) {
                    if (tableCols[key].friendly === friendlyText) {
                        if (orderFilters[key] === undefined) {
                            orderFilters = {[key]: "ASC"};
                        } else {
                            orderFilters = {[key]: orderFilters[key] === "DESC" ? "ASC" : "DESC"};
                        }
                        doSearch();
                    }
                }
            }
        });
    }

    function doSearch(onFinish) {
        fetchData()
            .then(function (data) {
                setWatableData(data);
                renderPagination(data.total, options.pageSize);
                onFinish();
            });
    }

    function fetchData(page) {

        if (!page) {
            page = currentPage;
        }

        var getData = Object.assign({
            limit: options.pageSize,
            offset: (page - 1) * options.pageSize,
            searchFilters: Object.keys(searchFilters)
                .filter(function (column) {
                    return !!searchFilters[column];
                }).map(function (column) {
                    return column + ":=" + searchFilters[column]
                }),
            orderFilters: Object.keys(orderFilters).map(function (column) {
                return column + ":=" + orderFilters[column]
            })
        }, options.urlData);

        var url = new URL(options.url);
        for (var key in getData) {
            if (getData.hasOwnProperty(key)) {
                if (getData[key] !== null) {
                    if (typeof getData[key] === "object") {
                        for (var i in getData[key]) {
                            if (getData[key].hasOwnProperty(i)) {
                                url.searchParams.append(key + "[]", getData[key][i]);
                            }
                        }
                    } else {
                        url.searchParams.append(key, getData[key]);
                    }
                }
            }
        }

        //setWatableData({cols: [], rows: []});
        $tableLoader.removeClass("d-none").addClass("d-flex");

        return new Promise(function (resolve, reject) {
            fetch(url.toString())
                .then(function (response) {
                    if (!response.ok) {
                        console.error("Fetch request error");
                        reject();
                        return;
                    }
                    return response.json();
                })
                .then(function (data) {
                    lastData = data;
                    $tableLoader.addClass("d-none").removeClass("d-flex");
                    resolve(data);
                })
        });
    }

    function renderPagination(totalItems, pageSize) {
        const totalPages = Math.ceil(totalItems / pageSize);
        currentPage = 1;
        $paginations.empty();//remove child nodes
        $paginations.removeData('twbs-pagination');//remove viewstate
        $paginations.off('page');//unbind $(this) node from page
        if (!totalPages) {
            return;
        }
        $paginations.twbsPagination({
            totalPages: totalPages,
            visiblePages: 5,
            hideOnlyOnePage: true,
            first: '<span aria-hidden="true">&laquo;</span>',
            next: "Successivo",
            prev: "Precedente",
            last: '<span aria-hidden="true">&raquo;</span>',
            initiateStartPageClick: false,
            onPageClick: function (event, page) {
                currentPage = page;
                fetchData(page).then(setWatableData);
                __renderLabel();
            },
        });

        __renderLabel();

        function __renderLabel() {
            if (!$paginations.find('.pagination-label').length) {
                $paginations.append("<div class='pagination-label mb-2'>");
            }
            $paginations.find('.pagination-label').html("Pagina " + (currentPage) + " di " + (totalPages) + " (" + (totalItems) + " elementi)");
        }
    }

    function constructor() {
        fetchData(1)
            .then(function (data) {
                if (data.total) {
                    watable.setData(data);
                    renderPagination(data.total, options.pageSize);
                    bindEvents();
                } else {
                    $paginationBottomContainer.append('Non ci sono elementi da visualizzare');
                }
            });
    }

    constructor();

    return watable;
}
