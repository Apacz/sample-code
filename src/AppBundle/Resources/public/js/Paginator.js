const EVENT_FINISH_LOAD_PAGINATION = 'pagination:finish_load';
const EVENT_LOAD_ITEM_PAGINATION = 'pagination:load_item';

var Paginator = function (prototype, urlList) {
    var $dataContainer = $('.posts-container');
    var $list = $dataContainer.find('#list');
    var $imageLoader = $('.posts-container #list');

    var init = function(urlList) {
        $dataContainer.pagination({
            dataSource: urlList,
            locator: 'items',
            totalNumberLocator: function(response) {
                return response.count;
            } ,
            pageSize: 20,
            callback: function (data, pagination) {
                $list.html('');
                for (let i = 0; i < data.length; i++) {
                    let d = data[i];
                    let prototypeObject = prototype.clone();
                    prototypeObject.removeClass('hidden');
                    var row = prototypeObject[0].outerHTML;
                    row = row.replace(new RegExp('__id__', 'gi'), data.id);
                    $list.trigger(EVENT_LOAD_ITEM_PAGINATION, {
                        'item': prototypeObject[0],
                        'data': data[i]
                    });
                    // $list.append(prototypeObject[0].outerHTML);
                }
                $list.trigger(EVENT_FINISH_LOAD_PAGINATION);
            }

        });

        $list.on(EVENT_FINISH_LOAD_PAGINATION, function() {
            $imageLoader.imagesLoaded(function(){
                $imageLoader.isotope({
                    itemSelector: '.post-item',
                    transitionDuration: '0.1s',
                    layoutMode: 'masonry',
                    masonry: { columnWidth: $imageLoader.width() / 12 }
                }); // isotope
            });

            $(window).resize(function() {
                $imageLoader.isotope({
                    masonry: { columnWidth: $imageLoader.width() / 12 }
                });
            }); // relayout
            $dataContainer.css('height', '100%');
        });
    };


    var setUrlList = function (url) {
        $dataContainer.pagination().destroy();
        init(url);
    };

    init(urlList);
};