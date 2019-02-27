// This is laoded only on lexemes/index

function load_search_suggest(q) {
  $.ajax({
    url: Gabra.api_url + 'lexemes/search_suggest',
    data: {s: q},
    success: function (data){
      var elem = $('#search-suggestions')
      if (data.results.length==0) return
      elem.append(Gabra.i18n.did_you_mean + ' ')
      var lemmas = []
      data.results.forEach(function (item) {
        if (lemmas.indexOf(item.lemma)===-1) {
          lemmas.push(item.lemma)
        }
      })
      lemmas.forEach(function (lemma,ix) {
        $("<a>").attr('href','?s='+lemma).text(lemma).appendTo(elem)
        if (ix < lemmas.length-1) elem.append(", ")
      })
      elem.append("?")
    },
    error:function(){
    }
  })
}

function load_results_function(data, term) {
return function() {
  var button = $(this)
  var elem = button.parent() // #load-more
  elem.addClass('loading')
  button.hide()
  $.ajax({
    url:Gabra.api_url+'lexemes/search',
    data:$.extend(data, {page: Gabra.page + 1}),
    success:function(data){
      var count = data.results.length
      var total = data.query.result_count
      if (count > 0) {
          $('#result-count').text(total)
          for (var i in data['results']) {
              var out = Gabra.UI.searchResult(data['results'][i], term)
              $('#results').append(out)
          }
          if (data.query.page * data.query.page_size >= total) {
              elem.remove()
          }
          Gabra.page = data.query.page
          button.show()
      } else {
          elem.remove() // no [more] results
          if (data.query.page === 1) {
            $('#result-count').text("0")
            var msg = Gabra.i18n.suggest.link.replace('%s', '<em>'+term+'</em>')
            var anchor = $('<a>').attr({'href': '#'}).html(msg)
            anchor.insertAfter('#search-suggestions')
            var form = $('<div>').addClass('').append(
                $('<form>').addClass('form-horizontal').attr({'id':'suggest'}).append(
                  $('<div>').addClass('form-group').append(
                    $('<label>').addClass('col-md-4 control-label').attr('for', 'suggest_lemma').text(Gabra.i18n.suggest.lemma),
                    $('<div>').addClass('col-md-8').append(
                      $('<input>').addClass('form-control').attr({'type':'text','id':'suggest_lemma','value':term}),
                      $('<span>').addClass('help-block').text(Gabra.i18n.suggest.lemma_help)
                    )
                  ),
                  $('<div>').addClass('form-group').append(
                    $('<label>').addClass('col-md-4 control-label').attr('for', 'suggest_gloss').text(Gabra.i18n.suggest.gloss),
                    $('<div>').addClass('col-md-8').append(
                      $('<input>').addClass('form-control').attr({'type':'text','id':'suggest_gloss'}),
                      $('<span>').addClass('help-block').text(Gabra.i18n.suggest.gloss_help)
                    )
                  ),
                  $('<div>').addClass('form-group').append(
                    $('<label>').addClass('col-md-4 control-label').attr('for', 'suggest_pos').text(Gabra.i18n.suggest.pos),
                    $('<div>').addClass('col-md-8').append(
                      $('<select>').addClass('form-control').attr({'id':'suggest_pos'}).append(
                        $('<option>').attr({'value':''}).text(''),
                        $('<option>').attr({'value':'NOUN'}).text(Gabra.i18n.pos.NOUN),
                        $('<option>').attr({'value':'VERB'}).text(Gabra.i18n.pos.VERB),
                        $('<option>').attr({'value':'ADJ'}).text(Gabra.i18n.pos.ADJ),
                        $('<option>').attr({'value':'X'}).text(Gabra.i18n.pos.X)
                      ),
                      $('<span>').addClass('help-block').text(Gabra.i18n.suggest.pos_help)
                    )
                  )
                )
              )

            anchor.click(function () {
              bootbox.dialog({
                title: Gabra.i18n.suggest.dialog_title,
                message: form.html(),
                onEscape: function() {},
                animate: false,
                buttons: {
                  success: {
                    label: Gabra.i18n.suggest.submit,
                    className: 'btn-primary',
                    callback: function() {
                      var errored = false
                      $('#suggest_lemma, #suggest_gloss, #suggest_pos').each(function () {
                        var obj = $(this)
                        if (obj.val() === '') {
                          obj.parent().parent().addClass('has-error')
                          errored = true
                        } else {
                          obj.parent().parent().removeClass('has-error')
                        }
                      })
                      if (errored) return false
                      $.ajax({
                        url: Gabra.api_url + 'feedback/suggest',
                        dataType: 'json',
                        type: 'POST',
                        data: {
                          lemma: $('#suggest_lemma').val(),
                          gloss: $('#suggest_gloss').val(),
                          pos:   $('#suggest_pos').val()
                        },
                        success: function(data) {
                          anchor.hide()
                          $('div.lexemes.index').prepend(
                            $('<div>').addClass('alert alert-success').attr('role', 'alert').text(Gabra.i18n.suggest.added)
                          )
                        },
                        error: function(err) {
                          if (err.status === 200) {
                            anchor.hide()
                            $('div.lexemes.index').prepend(
                              $('<div>').addClass('alert alert-success').attr('role', 'alert').text(Gabra.i18n.suggest.added)
                            )
                          } else {
                            $('div.lexemes.index').prepend(
                              $('<div>').addClass('alert alert-danger').attr('role', 'alert').text(Gabra.i18n.error_occurred)
                            )
                          }
                        }
                      })
                    }
                  },
                  cancel: {
                    label: Gabra.i18n.suggest.cancel,
                    className: "btn-default"
                  }
                }
              })
              return false
            })
          }
      }
    },
    complete:function(){
      elem.removeClass('loading')
    },
    error:function(){
      $('div.lexemes.index').prepend(
        $('<div>').addClass('alert alert-danger').attr('role', 'alert').text(Gabra.i18n.error_occurred)
      )
    }
  })
}
}

// https://gist.github.com/toshimaru/6102647
$(window).on("scroll", function () {
  var scrollHeight = $(document).height()
  var scrollPosition = $(window).height() + $(window).scrollTop()
  if ((scrollHeight - scrollPosition) / scrollHeight === 0) {
    $('#load-more button').trigger('click')
  }
})
