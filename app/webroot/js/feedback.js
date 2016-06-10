// Handler for feedback
$(document).ready(function(){

    // Handler
    var handler = function() {
        var anchor = $(this);
        var container = anchor.parent();
        var msg = container.find('.message');
        bootbox.prompt(Gabra.i18n.feedback_dialog_title, function(result) {
            if (!result) { // null or empty
                return;
            }
            anchor.hide();
            msg.empty();
            container.addClass('loading right');
            $.ajax({
                url: anchor.attr('href'),
                data: {
                    message: result
                },
                dataType: "json",
                type: "GET",
                success: function(data) {
                    container.removeClass('loading right');
                    if (container.hasClass('lexeme')) {
                        // lemma
                        // $('.bigword').addClass('incorrect');
                        msg.text(data.message);
                        // container.fadeOut(5000);
                    } else {
                        // word form
                        // container.parent().find('.surface_form').addClass('incorrect');
                        container.hide();
                    }
                },
                error: function(err){
                    container.removeClass('loading right');
                    msg.text(err.responseJSON.name);
                    anchor.show();
                }
            });
        });
        return false;
    };

    // Wordforms
    // $('.word_form .feedback a').click(handler);

    // Lexemes
    $('.lexeme.feedback a').click(handler);

});
