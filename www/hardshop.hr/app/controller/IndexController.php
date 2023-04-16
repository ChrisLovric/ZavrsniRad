<?php

class IndexController extends Controller
{
    public function index()
    {
        $this->view->render('index');
    }

    public function prijava()
    {
        if(App::auth()){
            $np=new NadzornaplocaController();
            $np->index();
            return;
        }

        $this->view->render('prijava',[
            'poruka'=>isset($_GET['poruka']) ? $_GET['poruka'] : '',
            'email'=>'',
        ]);
    }

    public function registracija()
    {
        $this->view->render('registracija');
    }

    public function odjava()
    {
        unset($_SESSION['auth']);
        session_destroy();
        header('location:' . App::config('url'));
    }

    public function kontakt()
    {
        $this->view->render('kontakt');
    }

    public function jsosnove()
    {
        $this->view->render('jsosnove');
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