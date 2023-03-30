$('#uvjet').focus();

$('#uvjet').autocomplete({
    source: function(req,res){
        $.ajax({
            url: url + 'kupac/ajaxSearch/' + req.term,
            success:function(odgovor){
                res(odgovor);
            }
        });
    },
    minLength: 2,
    select:function(dogadjaj,ui){
        spremi(ui.item);
    }
}).autocomplete('instance')._renderItem=function(ul, item){
    return $('<li>')
    .append('<div>' + item.naziv + '</div>')
    .appendTo(ul);
};