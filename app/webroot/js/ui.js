Gabra.UI = {};

// Renders main search results
Gabra.UI.searchResult = function(result, match) {
    var lexeme = result.lexeme;
    var id = lexeme._id;
    var link = function(action) {
        return Gabra.base_url+'lexemes/'+action+'/'+id;
    }
    var link_api = function(action) {
        return Gabra.api_url+'lexemes/'+action+'/'+id;
    }
    var out = $('<div>').addClass('row').attr('id',id).append(
        $('<div>').addClass('col-sm-2').append(
            $('<span>').addClass('surface_form').append(
                $('<a>')
                    .attr('href',link('view'))
                    .html( Gabra.UI.highlight(lexeme.lemma, match) ),' ',
                Gabra.UI.alternatives(lexeme.alternatives)
            ),
            Gabra.user ? (
                $('<div>').addClass('small').append(
                    $('<a>')
                        .attr('href',Gabra.base_url+'lexemes?s='+lexeme.lemma)
                        .addClass('text-info text-nowrap')
                        .append(Gabra.UI.icon('search'),' ','Search'),
                    ' ',
                    $('<a>')
                        .attr('href',link_api('view'))
                        .addClass('text-warning text-nowrap')
                        .append(Gabra.UI.icon('pencil'),' ','Edit'),
                    ' ',
                    $('<a>')
                        .attr('href',link('delete'))
                        .addClass('text-danger text-nowrap')
                        .append(Gabra.UI.icon('remove'),' ','Delete')
                        .click(function(){return confirm('Are you sure you want to delete this entry?')})
                )
            ) : ''
        ),
        $('<div>').addClass('col-sm-2').append(
            Gabra.UI.posTag(lexeme.pos),' ',
            Gabra.UI.root(lexeme.root),' ',
            Gabra.UI.derivedForm(lexeme.derived_form),' ',
            Gabra.UI.booleanField(lexeme, 'transitive', 'trans.'),' ',
            Gabra.UI.booleanField(lexeme, 'intransitive', 'intrans.'),' ',
            Gabra.UI.booleanField(lexeme, 'ditransitive', 'ditrans.'),' ',
            Gabra.UI.booleanField(lexeme, 'hypothetical', 'hyp.'),' ',
            Gabra.UI.maybeField(lexeme, 'frequency')
        ),
        $('<div>').addClass('col-sm-4').append(
            Gabra.UI.gloss(lexeme, {match:match})
        ),
        $('<div>').addClass('col-sm-4 loading') // loaded by ajax
    );
    Gabra.loadWordForms(id, match, '#'+id+' > div:eq(3)', 5);
    return out;
};

// Renders root search results
Gabra.UI.searchResultRoot = function(item, match) {
    var root = item.root;
    var id = root._id;
    var viewlink = Gabra.base_url+'roots/view/'+root.radicals;
    if (root.variant) viewlink += '/' + root.variant;
    var out = $('<tr>').append(
        // Radicals
        $('<td>').append(
            Gabra.UI.root(root),
            ' ',
            Gabra.UI.alternatives(root.alternatives)
        ),
        // Type
        $('<td>').append(
            Gabra.i18n.localise(['root_types', root.type])
        )
    );
    var verbs = {}
    for (var i = 0; i < item.lexemes.length; i++) {
      var lex = item.lexemes[i];
      if (lex.hasOwnProperty('pos') && lex.pos === 'VERB' && lex.derived_form) {
        if (!verbs[lex.derived_form]) {
          verbs[lex.derived_form] = [];
        }
        verbs[lex.derived_form].push(lex);
      }
    }
    for (var i = 1; i <= 10 ; i++) {
        if (i==4) continue;
        var cell = $('<td>');
        if (verbs[i]) {
            for (var vf_ix in verbs[i]) {
                var vf = verbs[i][vf_ix];
                if (vf_ix > 0) cell.append(',');
                cell.append(
                    $('<span>').addClass('surface_form').append(
                        $('<a>')
                            .attr('href',Gabra.base_url+'lexemes/view/'+vf._id)
                            .addClass(vf.hypothetical ? 'hypothetical' : '')
                            .html(vf.lemma),
                        ' ',
                        Gabra.UI.alternatives(vf.alternatives)
                    )
                );
                cell.append(
                    $('<div>').addClass('features').append(
                        Gabra.UI.maybeField(vf, 'frequency'),
                        Gabra.UI.booleanField(vf, 'transitive', 'trans.'),
                        Gabra.UI.booleanField(vf, 'intransitive', 'intrans.'),
                        Gabra.UI.booleanField(vf, 'ditransitive', 'ditrans.'),
                        Gabra.UI.booleanField(vf, 'hypothetical', 'hyp.')
                    ),
                    Gabra.UI.gloss(vf)
                );
            }
        } else {
            cell.html('<span style="color:#ccc">-</span>');
        }
        out.append(cell);
    }
    return out;
};

Gabra.UI.lexeme = function(lexeme) {
    var out = $('<div>').addClass('lexeme').append(
        Gabra.UI.maybeField(lexeme, 'pos', "%s"),
        Gabra.UI.maybeField(lexeme, 'form', " Form %s")
    );

    if (lexeme['root']) {
        out.append(
            Gabra.UI.root(lexeme['root'])
        );
    }
    if (lexeme['gloss']) {
        out.append(
            Gabra.UI.gloss(lexeme, {inline:true})
        );
    }
    out.append(
        Gabra.UI.maybeField(lexeme, 'sources', "(%s)")
    );
    // if (lexeme.Wordforms) {
    //     out.append(
    //         lexeme.Wordforms.length + " wordforms"
    //     );
    // }
    return out;
};

Gabra.UI.wordForm = function(wordform, match) {
    var out = $('<div>').addClass('word_form').append(
        $('<span>')
            .addClass('surface_form')
            .html( Gabra.UI.highlight(wordform.surface_form, match) ),
        ' ',
        $('<span>')
            .addClass('features')
            .append(
                // Noun
                Gabra.UI.maybeField(wordform, 'number', "%s."),' ',
                Gabra.UI.maybeField(wordform, 'gender', "%s."),' ',
                Gabra.UI.maybeField(wordform, 'phonetic', "/%s/"),' ',
                Gabra.UI.maybeField(wordform, 'pattern'),' ',

                // Verb
                Gabra.UI.maybeField(wordform, 'aspect'),' ',
                Gabra.UI.maybeAgr(wordform, 'subject'),' ',
                Gabra.UI.maybeAgr(wordform, 'dir_obj', "&middot; dir: %s"),' ',
                Gabra.UI.maybeAgr(wordform, 'ind_obj', "&middot; ind: %s"),' ',
                Gabra.UI.maybeField(wordform, 'polarity', "&middot; %s")
            )
    );
    return out;
};

Gabra.UI.highlight = function(haystack, needle) {
    if (!haystack) return '';
    var re = new RegExp("("+needle+")","i");
    return (needle ? haystack.replace(re,"<mark>$1</mark>") : haystack);
};

Gabra.UI.maybeField = function(item, field, formatstring) {
    var formatstring = typeof formatstring !== 'undefined' ? formatstring : "%s";
    var out = '';
    if (item[field]) {
        out += formatstring.replace("%s", item[field]) + " ";
    }
    return out;
};

// Check if a boolean field exists and is true, and if so display its name
Gabra.UI.booleanField = function(item, field, iftrue) {
    if (item[field]) {
        return (typeof iftrue == 'undefined') ? field : iftrue ;
    } else {
        return '';
    }
};

Gabra.UI.maybeAgr = function(item, field, formatstring) {
    var formatstring = typeof formatstring !== 'undefined' ? formatstring : "%s";
    var out = '';
    if (item[field]) {
        var agr = item[field];
        if (agr.person) out += agr.person + ' ';
        if (agr.gender) out += agr.gender + '. ';
        if (agr.number) out += agr.number + '. ';
    }
    if (out)
        return formatstring.replace("%s", out);
    else
        return '';
};

Gabra.UI.posTag = function(tag, options) {
    var opts = $.extend({
        capitalise : false,
    }, options);
    var s = Gabra.i18n.localise(['pos', tag]);
    return opts.capitalise ? s.toProperCase() : s;
};

Gabra.UI.root = function(root, options) {
    var opts = $.extend({
        include_link : true,
        include_variant : true,
    }, options);
    if (!root) return '';
    var out;
    if (opts['include_link']) {
        var url = Gabra.base_url + 'roots/view/' + root['radicals'];
        if (root['variant']) url += '/'+root['variant'];
        out = $('<a>').attr('href',url).addClass('root');
    } else {
        out = $('<span>').addClass('root');
    }
    out.text(root['radicals']);
    if (opts['include_variant'] && root['variant']) {
        out.append(
            $('<sup>').text(root['variant'])
        );
    }
    return out;
};

// Derived form in Roman numerals
Gabra.UI.derivedForm = function(dform) {
    var forms = {
        1 : 'I',
        2 : 'II',
        3 : 'III',
        4 : 'IV',
        5 : 'V',
        6 : 'VI',
        7 : 'VII',
        8 : 'VIII',
        9 : 'IX',
        10 : 'X',
    };
    if (dform)
        return forms[dform];
    else
        return '';
};

Gabra.UI.alternatives = function(s) {
    if (!s) return '';
    if (Array.isArray(s)) {
        s = s.join(', ');
    }
    return $('<span>').addClass('alt').text('('+s+')');
};

Gabra.UI.gloss = function(lexeme, options) {
    var opts = $.extend({
        inline : false,
        match : null,
    }, options);
    var gloss = lexeme.gloss;
    if (!gloss) return '';
    if (opts.inline) {
        gloss = gloss.replace(/\n/g, ', ');
    } else {
        gloss = gloss.replace(/\n/g, '<br>');
    }
    if (opts.match) {
        gloss = Gabra.UI.highlight(gloss, opts.match);
    }
    var out = $('<div>').addClass('gloss').html(gloss);
    return out;
};

Gabra.UI.icon = function(name, title) {
    return $('<span>').attr({'title':title, 'class':'glyphicon glyphicon-'+name});
};

Gabra.UI.etymology = function(etym, langs) {
    var out = $('<div>').addClass('etymology');
    $('<p>').html(
        Gabra.i18n.localise('etymology.occurs_in', [etym.etymology.length, etym.senses.length])+':'
    ).appendTo(out);
    var ol = $('<ol>').appendTo(out);

    var getEtymLang = function (lang) {
        for (var x in etym.etymology) {
            if (etym.etymology[x].language === lang) {
                return etym.etymology[x];
            }
        }
        return null;
    }
    var getLangName = function (lang) {
        return (typeof langs !== 'undefined' && langs.hasOwnProperty(lang)) ? langs[lang] : lang;
    }

    for (var s in etym.senses) {
        var sense = etym.senses[s];
        var etyms = $('<div>').addClass('text-muted');
        for (var e in sense.etymologies) {
            var etymlang = getEtymLang(sense.etymologies[e]);
            if (etymlang) {
                var langname = getLangName(etymlang.language);
                var tooltip = langname + ': ' + etymlang.word; // + lang.reference;
                if (etymlang.word_native)
                    tooltip += ' (' + etymlang.word_native + ')';
                etyms.append($('<span>').text(etymlang.language).attr({
                    'data-toggle':'tooltip',
                    'data-placement':'bottom',
                    'title': tooltip
                }));
                if (e < sense.etymologies.length-1) {
                    etyms.append(', ');
                }
            }
        }
        ol.append(
            $('<li>')
                .append($('<div>').text(sense.description))
                .append(etyms)
            );
    }

    var link = Gabra.i18n.localise('etymology.more_link', [Gabra.minsel_url + '?s=' + etym.lemma]);
    $('<p>').html(decodeEntities(link)).appendTo(out);

    return out;
};
