let uvjet='';
$( '#uvjet' ).autocomplete({
    source: function(req,res){
        uvjet = req.term;
       $.ajax({
           url: url + 'nadzornaploca/pretraga/' + uvjet,
           success:function(odgovor){
            res(odgovor);
        }
       }); 
    },
    minLength: 2,
    select:function(dogadaj,ui){
       window.location.href = url + ui.item.vrsta + '/promjena/' + ui.item.sifra;
    }
}).autocomplete( 'instance' )._renderItem = function( ul, item ) {
    let sadrzaj = item.tekst;
    var querystr = uvjet;
    var output = sadrzaj;
    var reg = new RegExp(querystr, 'gi');
    var final_str = output.replace(reg, function(str) {return str.bold().fontcolor("green")});


    return $( '<li>' )
      .append( '<div>' + final_str + ' (' + item.vrsta + ')<div>')
      .appendTo( ul );
  };

$('#uvjet').focus();

