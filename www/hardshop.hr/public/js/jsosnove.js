function primjerAJAX(){
    let xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange=function(){
        if(xmlhttp.readyState==XMLHttpRequest.DONE){
            if(xmlhttp.status==200){
                ispisiKupce(JSON.parse(xmlhttp.responseText));
            }
        }
    };

    xmlhttp.open('GET', '/kupac/v1/read', true);
    xmlhttp.send();
    return false;
}

function ispisiKupce(kupci){
    console.table(kupci);
    let ut=0;
    for(let i=0;i<kupci.length;i++){
        console.log(kupci[i].ime);
    }
}

$('li').click(function(){
    $('#paneli').html($(this).html());
});

$('#jQuery1').click(function(){
    $.get( '/kupac/v1/read', function(podaci){
        for (const k in podaci){
            const s=podaci[k];
            $('#lista').append('<li>' + s.ime + ' ' + s.prezime + '</li>')
        }
    });
    return false;
});