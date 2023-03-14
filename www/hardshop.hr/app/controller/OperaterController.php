<?php

class OperaterController extends AdminController
{
    private $viewPutanja='privatno' . DIRECTORY_SEPARATOR . 'operateri' . DIRECTORY_SEPARATOR;
    private $e;
    private $poruka='';

    public function index()
    {
        $operateri=Operater::read();
        foreach($operateri as $o){
            unset($o->lozinka);
        }

        $this->view->render($this->viewPutanja . 'index',[
            'podaci'=>$this->prilagodiPodatke(Operater::read()),
            'css'=>'operateri.css'
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
        Operater::create((array)$this->e);
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

        $this->e=Operater::readOne($sifra);

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
    Operater::update((array)$this->e);
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
        Operater::delete($sifra);
        header('location: ' . App::config('url') . 'operater/index');
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
        return $this->kontrolaIme() && $this->kontrolaPrezime() && $this->kontrolaEmail() && $this->kontrolaUloga();
    }

    private function kontrolaPromjena()
    {
        return $this->kontrolaIme() && $this->kontrolaPrezime() && $this->kontrolaEmailPromjena() && $this->kontrolaUloga();
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

        if(Operater::postojiIstiMailUBazi($s)){
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

    private function kontrolaUloga()
    {
        $s=$this->e->uloga;
        if(strlen(trim($s))===0){
            $this->poruka='Uloga obavezna';
            return false;
        }

        if(strlen(trim($s))>20){
            $this->poruka='Uloga ne smije imati više od 20 znakova';
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
        $e->uloga='';
        return $e;
    }

    private function prilagodiPodatke($operateri)
    {
        foreach($operateri as $o){
            $o->ime;
            $o->prezime;
            $o->email;
            $o->uloga;
            if(strlen($o->ime)>20){
                $o->ime=substr($o->ime,0,15) . '...' . substr($o->ime,strlen($o->ime)-5);
            }
        }
        return $operateri;
    }
}