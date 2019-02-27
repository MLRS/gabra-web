// Load stuff using AJAX

// Still used by duplicates
Gabra.loadLexeme = function(id, div) {
    $.ajax({
        url: Gabra.api_url+"lexemes/"+id,
        data: {},
        dataType: "json",
        type: "GET",
        success: function(data) {
            var out = Gabra.UI.lexeme(data);
            $(div).html(out);
            $(div).removeClass('loading');
        },
        error: function(err){
            console.log(err.responseJSON);
            $(div).removeClass('loading');
        }
    });
}

// Used in main search results
Gabra.loadWordForms = function(id, match, div, limit) {
    var process = function (data) {
        var match2 = decodeURIComponent(match);
        var out = [];
        var seen = 0;
        for (i in data) {
            var wf = data[i];
            out.push( Gabra.UI.wordForm(wf, match2) );
            seen += 1;
            if (seen >= limit) {
                var remaining = data.length - seen;
                if (remaining > 0)
                    out.push(
                        $('<a>')
                            .attr('href',Gabra.base_url+'lexemes/view/'+id)
                            .html( Gabra.i18n.localise('x_more', remaining)+'...' )
                    );
                break;
            }
        }
        $(div).append(out);
    }
    $.ajax({
        url: Gabra.api_url+"lexemes/wordforms/"+id,
        data: {}, // without match
        dataType: "json",
        type: "GET",
        success: function(data) {
            // If too many results, search again with match
            if (data.length > limit) {
                $.ajax({
                    url: Gabra.api_url+"lexemes/wordforms/"+id,
                    data: {}, // without match
                    dataType: "json",
                    type: "GET",
                    success: function(data) {
                        process(data)
                        $(div).removeClass('loading');
                    },
                    error: function(err){
                        console.log(err.responseJSON);
                        $(div).removeClass('loading');
                    }
                });
            // Just show all results
            } else {
                process(data)
                $(div).removeClass('loading');
            }
        },
        error: function(err){
            console.log(err.responseJSON);
            $(div).removeClass('loading');
        }
    });
}
