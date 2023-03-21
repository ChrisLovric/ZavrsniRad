<?php

class NarudzbaController extends AutorizacijaController implements ViewSucelje
{
    private $viewPutanja='privatno' . DIRECTORY_SEPARATOR . 'narudzbe' . DIRECTORY_SEPARATOR;
    private $e;
    private $poruke=[];

    public function __construct()
    {
        parent::__construct();
    }

    public function index()    
    {

        $this->view->render($this->viewPutanja . 'index',[
            'podaci'=>Narudzba::read(),
            'css'=>'narudzba.css'
        ]);
        
    }

    public function novi()
    {

    }

    public function promjena()
    {

    }

    public function brisanje($sifra=0)
    {
        $sifra=(int)$sifra;
        if($sifra===0){
            header('location: ' . App::config('url') . 'index/odjava');
            return;
        }
        Narudzba::delete($sifra);
        header('location: ' . App::config('url') . 'narudzba/index');
    }

    public function pripremiZaView()
    {

    }

    public function pripremiZaBazu()
    {
        
    }

    public function pocetniPodaci()
    {
        $e=new stdClass();
        $e->brojnarudzbe='';
        $e->datumnarudzbe='';
        $e->datumisporuke='';
        $e->datumplacanja='';
        $e->kupac=0;
        $e->placanje=0;
        return $e;
    }

}