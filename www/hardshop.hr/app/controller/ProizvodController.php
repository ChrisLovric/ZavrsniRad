<?php

class ProizvodController extends AutorizacijaController
{
    private $viewPutanja='privatno' . DIRECTORY_SEPARATOR . 'proizvodi' . DIRECTORY_SEPARATOR;
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
            'podaci'=>$this->prilagodiPodatke(Proizvod::read()),
            'css'=>'proizvod.css'
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
        $this->pripremiZaBazu();
        Proizvod::create((array)$this->e);
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

        $this->e=Proizvod::readOne($sifra);

        if($this->e==null){
            header('location: ' . App::config('url') . 'index/odjava');
            return;
        }

        $this->e->jedinicnacijena=$this->nf->format($this->e->jedinicnacijena);

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
    $this->pripremiZaBazu();
    Proizvod::update((array)$this->e);
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
        Proizvod::delete($sifra);
        header('location: ' . App::config('url') . 'proizvod/index');
    }

    private function pozoviView($parametri)
    {
        $this->view->render($this->viewPutanja . 'novi', $parametri);
    }

    private function pripremiZaView()
    {
        $this->e=(object)$_POST;
        $this->e->dostupnost=$this->e->dostupnost==='true' ? true : false;
    }

    private function pripremiZaBazu()
    {
        $this->e->jedinicnacijena=$this->nf->parse($this->e->jedinicnacijena);
    }

    private function kontrolaNovi()
    {
        return $this->kontrolaNaziv() && $this->kontrolaProizvodjac() && $this->kontrolaJedinicnacijena() && 
        $this->kontrolaOpis();
    }

    private function kontrolaPromjena()
    {
        return $this->kontrolaNazivPromjena() && $this->kontrolaProizvodjac() && $this->kontrolaJedinicnacijena() && 
        $this->kontrolaOpis();
    }

    private function kontrolaNaziv()
    {
        $s=$this->e->naziv;
        if(strlen(trim($s))===0){
            $this->poruka='Naziv proizvoda obavezan';
            return false;
        }

        if(strlen(trim($s))>50){
            $this->poruka='Naziv proizvoda ne smije imati više od 50 znakova';
            return false;
        }

        if(Proizvod::postojiIstiProizvodUBazi($s)){
            $this->poruka='Proizvod već postoji u bazi';
            return false;
        }

        return true;
    }

    private function kontrolaNazivPromjena()
    {
        $s=$this->e->naziv;
        if(strlen(trim($s))===0){
            $this->poruka='Naziv proizvoda obavezan';
            return false;
        }

        if(strlen(trim($s))>50){
            $this->poruka='Naziv proizvoda ne smije imati više od 50 znakova';
            return false;
        }

        return true;
    }

    private function kontrolaProizvodjac()
    {
        $s=$this->e->proizvodjac;
        if(strlen(trim($s))===0){
            $this->poruka='Ime proizvođača obavezno';
            return false;
        }

        if(strlen(trim($s))>50){
            $this->poruka='Ime proizvođača ne smije imati više od 50 znakova';
            return false;
        }

        return true;
    }

    private function kontrolaJedinicnacijena()
    {
        
        if(strlen(trim($this->e->jedinicnacijena))===0){
            $this->poruka='Jedinična cijena obavezna';
            return false;
        }

        $jedinicnacijena=$this->nf->parse($this->e->jedinicnacijena);
        if(!$jedinicnacijena){
            $this->poruka='Jedinična cijena nije u dobrom formatu (Format mora biti 0.000,00)';
            return false;
        }

        if($jedinicnacijena<0){
            $this->poruka='Jedinična cijena morat biti veća od 0,00';
            return false;
        }

        if($jedinicnacijena>5000){
            $this->poruka='Jedinična cijena ne smije biti veća od 3.000,00';
            return false;
        }

        return true;
    }

    private function kontrolaOpis()
    {
        $s=$this->e->opis;
        if(strlen(trim($s))===0){
            $this->poruka='Opis obavezan';
            return false;
        }
        return true;
    }

    private function pocetniPodaci()
    {
        $e=new stdClass();
        $e->naziv='';
        $e->proizvodjac='';
        $e->jedinicnacijena='';
        $e->opis='';
        $e->dostupnost=false;
        return $e;
    }

    private function prilagodiPodatke($proizvodi)
    {
        foreach($proizvodi as $p){
            $p->naziv;
            $p->proizvodjac;
            $p->jedinicnacijena=$this->formatIznosa($p->jedinicnacijena);
            $p->dostupnost=$p->dostupnost ? 'checkbox' : 'minus';
        }
        return $proizvodi;
    }

    private function formatIznosa($broj)
    {
        if($broj==null){
            return $this->nf->format(0);
        }
            return $this->nf->format($broj);
    }

}