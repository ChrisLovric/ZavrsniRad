<?php

class NadzornaplocaController extends AutorizacijaController
{
    public function index()
    {
        parent::setCSSdependency([
            '<link rel="stylesheet" href="' . App::config('url') . 'public/css/dependency/jquery-ui.css">',
         ]);
        parent::setJSdependency([
            '<script src="' . App::config('url') . 'public/js/dependency/jquery-ui.js"></script>',
            '<script>
                 let url=\'' . App::config('url') . '\';
             </script>'
         ]);
        $this->view->render('privatno' . DIRECTORY_SEPARATOR . 'nadzornaPloca');
    }

    public function pretraga($uvjet)
    {
        $rez=NadzornaPloca::pretraga($uvjet);
        usort($rez, function($a, $b) {return strcmp($a->tekst, $b->tekst);});
        $this->view->api($rez);
    }
}