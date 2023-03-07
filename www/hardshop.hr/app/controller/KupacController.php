<?php

class KupacController extends AutorizacijaController
{
    private $viewPutanja='privatno' . DIRECTORY_SEPARATOR . 'kupci' . DIRECTORY_SEPARATOR;
    private $nf;

    public function __construct()
    {
        parent::__construct();
        $this->nf=new NumberFormatter('hr-HR',NumberFormatter::DECIMAL);
        $this->nf->setPattern(App::config('formatBroja'));
    }

    public function index()    
    {
        $kupci=Kupac::read();
        foreach($kupci as $k){
            $k->ime;
            $k->prezime;
            $k->email;
            $k->adresazaracun;
            $k->adresazadostavu;
            $k->brojtelefona;
        }

        $this->view->render($this->viewPutanja . 'index',[
            'podaci'=>$kupci,
            'css'=>'kupac.css'
        ]);
        
    }




}