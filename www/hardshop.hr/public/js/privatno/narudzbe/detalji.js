$( '#uvjet' ).autocomplete({
    source: function(req,res){
       $.ajax({
           url: url + 'proizvod/ajaxSearch/' + req.term,
           success:function(odgovor){
            res(odgovor);
            //console.log(odgovor);
        }
       }); 
    },
    minLength: 2,
    select:function(dogadaj,ui){
        //console.log(ui.item);
        spremi(ui.item);
    }
}).autocomplete( 'instance' )._renderItem = function( ul, item ) {
    return $( '<li>' )
      .append( '<div>' + item.naziv + '<div>')
      .appendTo( ul );
  };



  function spremi(proizvod){
    $.ajax({
        url: url + 'narudzba/dodajproizvod?narudzba=' + narudzbasifra + 
             '&proizvod=' + proizvod.sifra,
        success:function(odgovor){
            if(odgovor.error){
                $('#poruka').css('border','2px solid red','text-align center');
                $('#poruka').html(odgovor.description);
                $('#poruka').fadeIn();
                setTimeout(() => {
                    $('#poruka').css('border','0px');
                    $('#poruka').fadeOut();
                }, 3500);
                //alert(odgovor.description);
                return;
            }
            $('#poruka').html(odgovor.description);
            $('#poruka').fadeIn();
                setTimeout(() => {
                    $('#poruka').css('border','0px');
                    $('#poruka').fadeOut();
                }, 3500);
                //debugger;
           $('#podaci').append(
            '<tr>' + 
                '<td>' +
                    proizvod.naziv +
                '</td>' + 
                '<td>' +
                    '<a href="#" class="odabraniProizvod" id="p_' + 
                    proizvod.sifra
                    + '">' +
                    ' <i class="fi-trash"></i>' +
                    '</a>' +
                '</td>' + 
            '</tr>'
           );
           definirajBrisanje();

     }
    }); 

    
}

function definirajBrisanje(){
    $('.odabraniProizvod').click(function(){

        //console.log(narudzbasifra);
        //console.log($(this).attr('id').split('_')[1]);
        let element = $(this);
        $.ajax({
            url: url + 'narudzba/obrisiproizvod?narudzba=' + narudzbasifra + 
                 '&proizvod=' + element.attr('id').split('_')[1],
            success:function(odgovor){
               element.parent().parent().remove();
         }
        }); 
    
        return false;
    });
}
definirajBrisanje();
$('#poruka').fadeOut();

$('#uvjet').focus();