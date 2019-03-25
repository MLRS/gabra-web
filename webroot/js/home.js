/* global Gabra $ */
$(document).ready(function () {
  function setCount (key, clas, count) {
    var p = $('.jumbotron p:first')
    var l = $('<div>').addClass('label label-' + clas).text(count)
    if (p) { p.html(p.html().replace(key, l.prop('outerHTML'))) }
  }
  $.ajax({
    method: 'GET',
    url: Gabra.api_url + 'lexemes/count',
    success: function (count) {
      setCount('{lexemes}', 'primary', count.toLocaleString())
    },
    error: function () {
      setCount('{lexemes}', 'primary', '18,900+')
    }
  })
  $.ajax({
    method: 'GET',
    url: Gabra.api_url + 'wordforms/count',
    success: function (count) {
      setCount('{wordforms}', 'info', count.toLocaleString())
    },
    error: function () {
      setCount('{wordforms}', 'info', '4,500,000+')
    }
  })
})
