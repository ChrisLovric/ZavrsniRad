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
            'podaci'=>$this->prilagodiPodatke(Narudzba::read()),
            'css'=>'narudzba.css'
        ]);
        
    }

    private function prilagodiPodatke($narudzbe)
    {
        foreach($narudzbe as $n){
            if($n->datumnarudzbe==null){
                $n->datumnarudzbe='Nije definirano';
            }else{
                $n->datumnarudzbe=date('d.m.Y.',strtotime($n->datumnarudzbe));
            }
            if($n->datumisporuke==null){
                $n->datumisporuke='Nije definirano';
            }else{
                $n->datumisporuke=date('d.m.Y.',strtotime($n->datumisporuke));
            }
            if($n->datumplacanja==null){
                $n->datumplacanja='Nije definirano';
            }else{
                $n->datumplacanja=date('d.m.Y.',strtotime($n->datumplacanja));
            }
            if($n->kupac==null){
                $n->kupac='Nije definirano';
            }
            if($n->vrstaplacanja==null){
                $n->vrstaplacanja='Nije definirano';
            }
        }
        return $narudzbe;
    }

    public function novi()
    {
        $kupacSifra=Kupac::prviKupac();
        if($kupacSifra==0){
            header('location: ' . App::config('url') . 'kupac?p=1');
        }

        $placanjeSifra=Placanje::prvoPlacanje();
        if($placanjeSifra==0){
            header('location: ' . App::config('url') . 'placanje?p=1');
        }

        $this->promjena(Narudzba::create([
            'brojnarudzbe'=>'',
            'datumnarudzbe'=>'',
            'datumisporuke'=>null,
            'datumplacanja'=>null,
            'kupac'=>$kupacSifra,
            'placanje'=>$placanjeSifra
        ]));
    }

    public function odustani($sifra='')
    {
        $e=Narudzba::readOne($sifra);

        if(
        $e->datumisporuke==null &&
        $e->datumplacanja==null){
        Narudzba::delete($e->sifra);
        }
        header('location: ' . App::config('url') . 'narudzba');
    }

    public function promjena($sifra='')
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            $this->promjena_GET($sifra);
            return;
        }

        $this->e=(object)$_POST;

        try {
            $this->e->sifra=$sifra;
            $this->kontrola();
            $this->pripremiZaBazu();
            Narudzba::update((array)$this->e);
            header('location:' . App::config('url') . 'narudzba');
        } catch (\Exception $th) {
            $this->view->render($this->viewPutanja . 'detalji',[
                'poruke'=>$this->poruke,
                'e'=>$this->e
            ]);
        }
    }

    private function kontrola()
    {

    }

    private function promjena_GET($sifra)
    {
        $this->e=Narudzba::readOne($sifra);
        $kupci=[];
        $k=new stdClass();
        $k->sifra=0;
        $k->ime='Nije';
        $k->prezime='Odabrano';
        $kupci[]=$k;
        foreach(Kupac::read() as $kupac){
            $kupci[]=$kupac;
        }

        $this->e=Narudzba::readOne($sifra);
        $placanja=[];
        $p=new stdClass();
        $p->sifra=0;
        $p->vrstaplacanja='Nije odabrano';
        $placanja[]=$p;
        foreach(Placanje::read() as $placanje){
            $placanja[]=$placanje;
        }


        if($this->e->datumnarudzbe!=null){
            $this->e->datumnarudzbe=date('Y-m-d',strtotime($this->e->datumnarudzbe));
        }
        if($this->e->datumisporuke!=null){
            $this->e->datumisporuke=date('Y-m-d',strtotime($this->e->datumisporuke));
        }
        if($this->e->datumplacanja!=null){
            $this->e->datumplacanja=date('Y-m-d',strtotime($this->e->datumplacanja));
        }

        $this->view->render($this->viewPutanja . 'detalji',[
            'e'=>$this->e,
            'kupci'=>$kupci,
            'placanja'=>$placanja
        ]);
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
        if($this->e->kupac==0){
            $this->e->kupac=null;
        }
        if($this->e->datumnarudzbe==''){
            $this->e->datumnarudzbe=null;
        }
        if($this->e->datumplacanja==''){
            $this->e->datumplacanja=null;
        }
        if($this->e->datumisporuke==''){
            $this->e->datumisporuke=null;
        }
    }

    public function pocetniPodaci()
    {
        $e=new stdClass();
        $e->brojnarudzbe='';
        $e->datumnarudzbe='';
        $e->datumisporuke=null;
        $e->datumplacanja=null;
        $e->kupac=null;
        $e->placanje=null;
        return $e;
    }

}