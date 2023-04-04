$('#uvjet').focus();

$('#uvjet').autocomplete({
    source: function(req,res){
        uvjet=req.term;
        $.ajax({
            url: url + 'kupac/traziKupca/' + uvjet,
            success:function(odgovor){
                res(odgovor);
            }
        });
    },
    minLength: 2,
    select:function(dogadjaj,ui){
        window.location.href=url + 'kupac/promjena/' + ui.item.sifra;
    }
}).autocomplete('instance')._renderItem=function(ul, item){

    let sadrzaj = item.ime + ' ' + item.prezime;
    var querystr = uvjet;
    var output = sadrzaj;
    var reg = new RegExp(querystr, 'gi');
    var final_str = output.replace(reg, function(str) {return str.bold().fontcolor("Green")});
 
 
     return $( '<li>' )
       .append( '<div>' + final_str + '<div>')
       .appendTo( ul );
};