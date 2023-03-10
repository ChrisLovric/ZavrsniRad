<?php

class PlacanjeController extends AutorizacijaController
{
    private $viewPutanja='privatno' . DIRECTORY_SEPARATOR . 'placanje' . DIRECTORY_SEPARATOR;
    private $e;
    private $poruka='';

    public function index()    
    {
        $this->view->render($this->viewPutanja . 'index',[
            'podaci'=>$this->prilagodiPodatke(Placanje::read()),
            'css'=>'placanje.css'
        ]);
        
    }

    public function novi()
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            $this->pozoviView([
                'e'=>$this->pocetniPodaci(),
                'poruka'=>$this->poruka
            ]);
            return;
        }
        $this->pripremiZaView();
        if(!$this->kontrolaNovi()){
            $this->pozoviView([
                'e'=>$this->e,
                'poruka'=>$this->poruka
            ]);
            return;
        }
        Placanje::create((array)$this->e);
        $this->pozoviView([
            'e'=>$this->pocetniPodaci(),
            'poruka'=>'Uspješno spremljeno'
        ]);
    }

    public function promjena($sifra='')
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            if(strlen(trim($sifra))===0){
                header('location: ' . App::config('url') . 'index/odjava');
                return;
            }

        $sifra=(int)$sifra;
        if($sifra===0){
            header('location: ' . App::config('url') . 'index/odjava');
            return;
        }

        $this->e=Placanje::readOne($sifra);

        if($this->e==null){
            header('location: ' . App::config('url') . 'index/odjava');
            return;
        }

        $this->view->render($this->viewPutanja . 'promjena',[
            'e'=>$this->e,
            'poruka'=>'Izmijenite podatke po želji'
        ]);
        return;
    }

    $this->pripremiZaView();
    if(!$this->kontrolaPromjena()){
        $this->view->render($this->viewPutanja . 'promjena',[
            'e'=>$this->e,
            'poruka'=>$this->poruka
        ]);
        return;
    }

    $this->e->sifra=$sifra;
    Placanje::update((array)$this->e);
    $this->view->render($this->viewPutanja . 'promjena',[
        'e'=>$this->e,
        'poruka'=>'Uspješna izmjena podataka'
    ]);

    }

    private function pozoviView($parametri)
    {
        $this->view->render($this->viewPutanja . 'novi', $parametri);
    }

    private function pripremiZaView()
    {
        $this->e=(object)$_POST;
    }

    private function kontrolaNovi()
    {
        return $this->kontrolaVrstaplacanja();
    }

    private function kontrolaPromjena()
    {
        return $this->kontrolaVrstaplacanja();
    }

    private function kontrolaVrstaplacanja()
    {
        $s=$this->e->vrstaplacanja;
        if(strlen(trim($s))===0){
            $this->poruka='Vrsta plaćanja obavezna';
            return false;
        }

        if(strlen(trim($s))>50){
            $this->poruka='Vrsta plaćanja ne smije imati više od 50 znakova';
            return false;
        }

        if(Placanje::postojiVrstaPlacanjaUBazi($s)){
            $this->poruka='Vrsta plaćanja već postoji u bazi';
            return false;
        }

        return true;
    }

    private function pocetniPodaci()
    {
        $e=new stdClass();
        $e->vrstaplacanja='';
        return $e;
    }

    private function prilagodiPodatke($placanje)
    {
        foreach($placanje as $p){
            $p->vrstaplacanja;
        }
        return $placanje;
    }

}