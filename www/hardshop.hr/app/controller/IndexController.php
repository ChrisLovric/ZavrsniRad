<?php

class IndexController extends Controller
{
    public function index()
    {
        $this->view->render('index',[
            'iznos'=>10,
            'podaci'=>[
                11,12,13,14
            ]
        ]);
    }

    public function kontakt()
    {
        $this->view->render('kontakt');
    }

    public function api()
    {
        $this->view->api([
            'podaci'=>[
                'id'=>44,
                'osoba'=>[
                    'ime'=>'Mato',
                    'prezime'=>'Matic'
                ]
            ]
                ]);
    }
}