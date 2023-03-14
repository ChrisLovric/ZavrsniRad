<?php

class KupacController extends AutorizacijaController
{
    private $viewPutanja='privatno' . DIRECTORY_SEPARATOR . 'kupci' . DIRECTORY_SEPARATOR;
    private $nf;
    private $e;
    private $poruka='';

    public function __construct()
    {
        parent::__construct();
        $this->nf=new NumberFormatter('hr-HR',NumberFormatter::DECIMAL);
        $this->nf->setPattern(App::config('formatBroja'));
    }

    public function index()    
    {
        $this->view->render($this->viewPutanja . 'index',[
            'podaci'=>$this->prilagodiPodatke(Kupac::read()),
            'css'=>'kupac.css'
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
        //$this->pripremiZaBazu();
        Kupac::create((array)$this->e);
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

        $this->e=Kupac::readOne($sifra);

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
    //$this->pripremiZaBazu();
    Kupac::update((array)$this->e);
    $this->view->render($this->viewPutanja . 'promjena',[
        'e'=>$this->e,
        'poruka'=>'Uspješna izmjena podataka'
    ]);

    }

    public function brisanje($sifra=0)
    {
        $sifra=(int)$sifra;
        if($sifra===0){
            header('location: ' . App::config('url') . 'index/odjava');
            return;
        }
        Kupac::delete($sifra);
        header('location: ' . App::config('url') . 'kupac/index');
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
        return $this->kontrolaIme() && $this->kontrolaPrezime() && $this->kontrolaEmail() && 
        $this->kontrolaAdresazaracun() && $this->kontrolaAdresazadostavu() && $this->kontrolaBrojtelefona();
    }

    private function kontrolaPromjena()
    {
        return $this->kontrolaIme() && $this->kontrolaPrezime() && $this->kontrolaEmailPromjena() && 
        $this->kontrolaAdresazaracun() && $this->kontrolaAdresazadostavu() && $this->kontrolaBrojtelefona();
    }

    private function kontrolaIme()
    {
        $s=$this->e->ime;
        if(strlen(trim($s))===0){
            $this->poruka='Ime obavezno';
            return false;
        }

        if(strlen(trim($s))>50){
            $this->poruka='Ime ne smije imati više od 50 znakova';
            return false;
        }

        return true;
    }

    private function kontrolaPrezime()
    {
        $s=$this->e->prezime;
        if(strlen(trim($s))===0){
            $this->poruka='Prezime obavezno';
            return false;
        }

        if(strlen(trim($s))>50){
            $this->poruka='Prezime ne smije imati više od 50 znakova';
            return false;
        }

        return true;
    }

    private function kontrolaEmail()
    {
        $s=$this->e->email;
        if(strlen(trim($s))===0){
            $this->poruka='Email obavezan';
            return false;
        }

        if(Kupac::postojiIstiMailUBazi($s)){
            $this->poruka='Unesena email adresa već postoji u bazi';
            return false;
        }

        if(strlen(trim($s))>50){
            $this->poruka='Email adresa ne smije imati više od 50 znakova';
            return false;
        }

        return true;
    }

    private function kontrolaEmailPromjena()
    {
        $s=$this->e->email;
        if(strlen(trim($s))===0){
            $this->poruka='Email obavezan';
            return false;
        }

        if(strlen(trim($s))>50){
            $this->poruka='Email adresa ne smije imati više od 50 znakova';
            return false;
        }

        return true;
    }

    private function kontrolaAdresazaracun()
    {
        $s=$this->e->adresazaracun;
        if(strlen(trim($s))===0){
            $this->poruka='Adresa za račun obavezna';
            return false;
        }

        if(strlen(trim($s))>100){
            $this->poruka='Adresa za račun ne smije imati više od 100 znakova';
            return false;
        }

        return true;
    }

    private function kontrolaAdresazadostavu()
    {
        $s=$this->e->adresazadostavu;
        if(strlen(trim($s))===0){
            $this->poruka='Adresa za dostavu obavezna';
            return false;
        }

        if(strlen(trim($s))>100){
            $this->poruka='Adresa za dostavu ne smije imati više od 100 znakova';
            return false;
        }

        return true;
    }

    private function kontrolaBrojtelefona()
    {
        $s=$this->e->brojtelefona;
        if(strlen(trim($s))===0){
            $this->poruka='Broj telefona obavezan';
            return false;
        }

        if(strlen(trim($s))>20){
            $this->poruka='Broj telefona ne smije imati više od 20 znakova';
            return false;
        }

        return true;
    }

    private function pocetniPodaci()
    {
        $e=new stdClass();
        $e->ime='';
        $e->prezime='';
        $e->email='';
        $e->adresazaracun='';
        $e->adresazadostavu='';
        $e->brojtelefona='';
        return $e;
    }

    private function prilagodiPodatke($kupci)
    {
        foreach($kupci as $k){
            $k->ime;
            $k->prezime;
            $k->email;
            $k->adresazaracun;
            $k->adresazadostavu;
            if(strlen($k->ime)>20){
                $k->ime=substr($k->ime,0,15) . '...' . substr($k->ime,strlen($k->ime)-5);
            }
        }
        return $kupci;
    }

}