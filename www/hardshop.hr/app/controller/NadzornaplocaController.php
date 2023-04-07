<?php

class NadzornaplocaController extends AutorizacijaController
{
    public function index()
    {
        $statistika = Narudzba::brojKupacaSaNarudzbom();

        $brojnarudzbi=Narudzba::brojNarudzbi();

        $brojkupaca=Kupac::brojKupaca();

        $brojproizvoda=Proizvod::brojProizvoda();

        parent::setCSSdependency([
            '<link rel="stylesheet" href="' . App::config('url') . 'public/css/dependency/jquery-ui.css">',
         ]);
        parent::setJSdependency([
            '<script src="' . App::config('url') . 'public/js/dependency/highcharts.js"></script>',
            '<script src="' . App::config('url') . 'public/js/dependency/exporting.js"></script>',
            '<script src="' . App::config('url') . 'public/js/dependency/export-data.js"></script>',
            '<script src="' . App::config('url') . 'public/js/dependency/accessibility.js"></script>',
            '<script src="' . App::config('url') . 'public/js/dependency/jquery-ui.js"></script>',
            '<script>
                 let url=\'' . App::config('url') . '\';
                 let podaci = JSON.parse(\'' . json_encode($statistika, JSON_NUMERIC_CHECK) .'\');
                 let podaci2 = JSON.parse(\'' . json_encode($brojnarudzbi, JSON_NUMERIC_CHECK) .'\');
                 let podaci3 = JSON.parse(\'' . json_encode($brojkupaca, JSON_NUMERIC_CHECK) .'\');
                 let podaci4 = JSON.parse(\'' . json_encode($brojproizvoda, JSON_NUMERIC_CHECK) .'\');
             </script>'
         ]);
        $this->view->render('privatno' . DIRECTORY_SEPARATOR . 'nadzornaPloca');
    }

    public function pretraga($uvjet)
    {
        $rez=NadzornaPloca::pretraga($uvjet);

        foreach($rez as $r){
            if(file_exists(BP . 'public' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'proizvodi' . 
            DIRECTORY_SEPARATOR . $r->sifra . '.png')){
                $r->slika=App::config('url') . 'public/img/proizvodi/' . $r->sifra . '.png';
            }
            elseif(file_exists(BP . 'public' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'kupci' . 
            DIRECTORY_SEPARATOR . $r->sifra . '.png')){
                $r->slika=App::config('url') . 'public/img/kupci/' . $r->sifra . '.png';
            }else{
                $r->slika=App::config('url') . 'public/img/nepoznato.png';
            }
        }

        usort($rez, function($a, $b) {return strcmp($a->tekst, $b->tekst);});

        $this->view->api($rez);
    }
}