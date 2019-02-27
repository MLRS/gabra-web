// Editing of lexemes
$(document).ready(function(){
    $('#add-field input').keydown(function (e) {
        if (e.keyCode == 13) {
            $('#add-field a').trigger('click');
            return false;
        }
    });

    $('#add-field a').click(function(){
        var name = $('#add-field input').val();
        if (!name) return false;
        var nameLC = name.toLowerCase();
        var nameSC = name.toProperCase();
        $('#add-field').before(
            $('<div>')
                .addClass("form-group")
                .append('<label for="Lexeme'+nameSC+'">'+nameSC+'</label>')
                .append('<input name="data[Lexeme]['+nameLC+']" type="text" id="Lexeme'+nameSC+'" class="form-control"/>')
        );
        $('#add-field input').val(null);
        return false;
    });

});
