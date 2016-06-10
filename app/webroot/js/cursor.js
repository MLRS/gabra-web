(function ($, undefined) {

    // Get cursor position
    // http://stackoverflow.com/a/1909997/98600
    // To use it on a text box, do the following:
    // $("#myTextBoxSelector").getCursorPosition();
    $.fn.getCursorPosition = function() {
        var el = $(this).get(0);
        var pos = 0;
        if('selectionStart' in el) {
            pos = el.selectionStart;
        } else if('selection' in document) {
            el.focus();
            var Sel = document.selection.createRange();
            var SelLength = document.selection.createRange().text.length;
            Sel.moveStart('character', -el.value.length);
            pos = Sel.text.length - SelLength;
        }
        return pos;
    };

    // http://stackoverflow.com/a/3651232/98600
    $.fn.setCursorPosition = function(pos) {
        this.each(function(index, elem) {
            if (elem.setSelectionRange) {
                elem.setSelectionRange(pos, pos);
            } else if (elem.createTextRange) {
                var range = elem.createTextRange();
                range.collapse(true);
                range.moveEnd('character', pos);
                range.moveStart('character', pos);
                range.select();
            }
        });
        return this;
    };

    // Insert text into box at current cursor location
    $.fn.insertAtCursor = function(s) {
        var el = $(this);
        var pos = $(el).getCursorPosition();
        var val = el.val();
        val = val.substring(0,pos) + s + val.substring(pos);
        el.val(val);
        el.setCursorPosition(pos+s.length);
        return this;
    };

})(jQuery);

