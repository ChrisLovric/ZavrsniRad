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



/////Highcharts

Highcharts.chart('container', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Kupci sa narudžbom',
        align: 'center'
    },
    subtitle: {
        align: 'center',
        text: 'Poveznica: <a href="http://polaznik09.edunova.hr/kupac/index" target="_blank">Kupci</a>'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.y}</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.y}'
            }
        }
    },
    series: [{
        name: 'Broj narudžbi',
        colorByPoint: true,
        data: podaci
    }]
});



///////Highcharts 2

Highcharts.chart('container2', {
    chart: {
        type: 'column'
    },
    title: {
        align: 'center',
        text: 'Pregled narudžbi, kupaca i proizvoda'
    },
    subtitle: {
        align: 'center',
        text: 'Poveznice:<br><a href="http://polaznik09.edunova.hr/narudzba/index" target="_blank">Narudžbe</a><br><a href="http://polaznik09.edunova.hr/kupac/index" target="_blank">Kupci</a><br><a href="http://polaznik09.edunova.hr/proizvod/index" target="_blank">Proizvodi</a>'
    },
    accessibility: {
        announceNewData: {
            enabled: true
        }
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: ''
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y}'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b><br/>'
    },

    series: [
        {
            name: 'Ukupan broj narudžbi',
            colorByPoint: true,
            data: podaci2
            },
        {
            name: 'Ukupan broj kupaca',
            colorByPoint: true,
            data: podaci3
            },
        {
            name: 'Ukupan broj proizvoda',
            colorByPoint: true,
            data: podaci4
            }
        ]
});
