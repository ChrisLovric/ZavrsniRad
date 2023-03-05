<?php

class IndexController extends Controller
{
    public function index()
    {
        $this->view->render('index');
    }

    public function kontakt()
    {
        $this->view->render('kontakt');
    }

    public function prijava()
    {
        $this->view->render('prijava',[
            'poruka'=>'',
            'email'=>''
        ]);
    }

    public function odjava()
    {
        unset($_SESSION['auth']);
        session_destroy();
        header('location:' . App::config('url'));
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