$('#uvjet').focus();

$('#uvjet').autocomplete({
    source: function(req,res){
        uvjet=req.term;
        $.ajax({
            url: url + 'proizvod/traziProizvod/' + uvjet,
            success:function(odgovor){
                res(odgovor);
            }
        });
    },
    minLength: 2,
    select:function(dogadjaj,ui){
        window.location.href=url + 'proizvod/promjena/' + ui.item.sifra;
    }
}).autocomplete('instance')._renderItem=function(ul, item){

    let sadrzaj = item.naziv;
    var querystr = uvjet;
    var output = sadrzaj;
    var reg = new RegExp(querystr, 'gi');
    var final_str = output.replace(reg, function(str) {return str.bold().fontcolor("Green")});
 
 
     return $( '<li>' )
       .append( '<div>' + final_str + '<div>')
       .appendTo( ul );
};