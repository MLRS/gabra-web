/* global google Gabra $ */
google.charts.load('current', { 'packages': ['corechart'] })
google.charts.setOnLoadCallback(function () {
  $.ajax({
    method: 'GET',
    url: Gabra.api_url + 'logs/chart',
    success: function (data) {
      var table = new google.visualization.DataTable()
      table.addColumn('date', 'Date')

      // Just totals
      var empty = true
      table.addColumn('number', Gabra.i18n.updates)
      for (var day in data) {
        if (data[day]['total'] > 0) empty = false
        table.addRow([new Date(day), data[day]['total']])
      }
      if (empty) return

      var options = {
        legend: {
          position: 'none'
        },
        hAxis: {
          textPosition: 'none',
          gridlines: {
            count: 0
          }
        },
        vAxis: {
          textPosition: 'none',
          gridlines: {
            count: 4,
            color: '#eeeeee'
          }
        },
        height: 100,
        chartArea: {
          width: '100%',
          height: '90%'
        }
      }
      var chart = new google.visualization.AreaChart(document.getElementById('log-chart'))
      chart.draw(table, options)
    },
    complete: function () {
    }
  })
})
