$('#uvjet').autocomplete({
    source: function (req, res) {
        uvjet = req.term;
        $.ajax({
            url: url + 'proizvod/traziProizvod/' + req.term,
            success: function (odgovor) {
                res(odgovor);
                //console.log(odgovor);
            }
        });
    },
    minLength: 2,
    select: function (dogadaj, ui) {
        //console.log(ui.item);
        spremi(ui.item);
    }
}).autocomplete('instance')._renderItem = function (ul, item) {

    let sadrzaj = item.naziv;
    var querystr = uvjet;
    var output = sadrzaj;
    var reg = new RegExp(querystr, 'gi');
    var final_str = output.replace(reg, function (str) { return str.bold().fontcolor("Green") });

    return $('<li>')
        .append('<div> <img style="height: 30px; width: 30px;" src="' + item.slika + '" />' + final_str + '<div>')
        .appendTo(ul);
};



function spremi(proizvod) {
    $.ajax({
        url: url + 'narudzba/dodajproizvod?narudzba=' + narudzbasifra +
            '&proizvod=' + proizvod.sifra,
        success: function (odgovor) {
            if (odgovor.error) {
                $('#poruka').css('text-align center');
                $('#poruka').html(odgovor.description);
                $('#poruka').fadeIn();
                setTimeout(() => {
                    $('#poruka').css('border', '0px');
                    $('#poruka').fadeOut();
                }, 3500);
                //alert(odgovor.description);
                return;
            }
            $('#poruka').html(odgovor.description);
            $('#poruka').fadeIn();
            setTimeout(() => {
                $('#poruka').css('border', '0px');
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

function definirajBrisanje() {
    $('.odabraniProizvod').click(function () {

        //console.log(narudzbasifra);
        //console.log($(this).attr('id').split('_')[1]);
        let element = $(this);
        $.ajax({
            url: url + 'narudzba/obrisiproizvod?narudzba=' + narudzbasifra +
                '&proizvod=' + element.attr('id').split('_')[1],
            success: function (odgovor) {
                element.parent().parent().remove();
            }
        });

        return false;
    });
}
definirajBrisanje();
$('#poruka').fadeOut();

$('#uvjet').focus();

/// pretraga kupaca
$('#uvjetkupac').autocomplete({
    source: function (req, res) {
        uvjet = req.term;
        $.ajax({
            url: url + 'kupac/traziKupca/' + req.term,
            success: function (odgovor) {


                res(odgovor);
                //console.log(odgovor);
            }
        });
    },
    minLength: 2,
    select: function (dogadaj, ui) {
        $('#kupac').val(ui.item.sifra);

        $('#kupacIme').html(ui.item.ime + ' ' + ui.item.prezime);

    }
}).autocomplete('instance')._renderItem = function (ul, item) {

    let sadrzaj = item.ime + ' ' + item.prezime;
    var querystr = uvjet;
    var output = sadrzaj;
    var reg = new RegExp(querystr, 'gi');
    var final_str = output.replace(reg, function (str) { return str.bold().fontcolor("Green") });

    return $('<li>')
        .append('<div> <img style="height: 30px; width: 30px;" src="' + item.slika + '" />' + final_str + '<div>')
        .appendTo(ul);
};