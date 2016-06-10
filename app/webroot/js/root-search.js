// This is laoded only on roots/index

function load_results_function(data, term) {
return function() {
    var button = $(this);
    var elem = button.parent(); // #load-more
    elem.addClass('loading');
    button.hide();
    $.ajax({
        url:Gabra.api_url+'roots/search',
        data:$.extend(data, {page:Gabra.page + 1}),
        success:function(data){
            var count = data.results.length;
            var total = data.query.result_count;
            if (count > 0) {
                $('#result-count').text(total);
                for (var i in data.results) {
                    var out = Gabra.UI.searchResultRoot(data.results[i], term);
                    $('#results').append(out);
                }
                if (data.query.page * data.query.page_size >= total) {
                    elem.remove();
                }
                Gabra.page = data.query.page;
            } else {
                elem.remove(); // no [more] results
                if (data.query.page === 1) {
                    $('#result-count').text("0");
                }
            }
        },
        complete:function(){
            elem.removeClass('loading');
            button.show();
        }
    });
}
}

// https://gist.github.com/toshimaru/6102647
$(window).on("scroll", function() {
  var scrollHeight = $(document).height();
  var scrollPosition = $(window).height() + $(window).scrollTop();
  if ((scrollHeight - scrollPosition) / scrollHeight === 0) {
    $('#load-more button').trigger('click');
  }
});
