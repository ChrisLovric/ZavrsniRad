<?php

class KupacController extends AutorizacijaController implements ViewSucelje
{
    private $viewPutanja='privatno' . DIRECTORY_SEPARATOR . 'kupci' . DIRECTORY_SEPARATOR;
    private $e;
    private $poruka='';

    public function index()    
    {
        $poruka='';
        if(isset($_GET['p'])){
            switch ((int)$_GET['p']){
                case 1:
                    $poruka='Kreirajte kupca kako bi mogli kreirati narudžbu';
                    break;

                    default:
                    $poruka='';
                    break;
            }
        }

        if(isset($_GET['uvjet'])){
            $uvjet=trim($_GET['uvjet']);
        }else{
            $uvjet='';
        }

        if(isset($_GET['stranica'])){
            $stranica=(int)$_GET['stranica'];
            if($stranica<1){
                $stranica=1;
            }
        }else{
            $stranica=1;
        }

        $ukupnostr=Kupac::ukupnoKupaca($uvjet);

        $zadnjastr=(int)ceil($ukupnostr/App::config('brps'));

        $this->view->render($this->viewPutanja . 'index',[
            'podaci'=>Kupac::read($uvjet,$stranica),
            'uvjet'=>$uvjet,
            'stranica'=>$stranica,
            'zadnjastr'=>$zadnjastr,
            'ukupnostr'=>$ukupnostr,
            'poruka'=>$poruka
        ]);
        
    }

    public function novi()
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            $this->view->render($this->viewPutanja . 'detalji',[
                'legend'=>'Unos novog kupca',
                'akcija'=>'Spremi',
                'poruka'=>'',
                'e'=>$this->pocetniPodaci()
            ]);
            return;
        }
        $this->pripremiZaView();

        try {
            $this->kontrolaNovi();
            $this->pripremiZaBazu();
            Kupac::create((array)$this->e);
            header('location:' . App::config('url') . 'kupac');
        } catch (\Exception $th) {
            $this->view->render($this->viewPutanja . 'detalji',[
                'legend'=>'Unos novog kupca',
                'akcija'=>'Spremi',
                'poruka'=>$this->poruka,
                'e'=>$this->e
            ]);
        }
    }

    public function promjena($sifra=0)
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            $this->provjeraIntParametra($sifra);

            $this->e=Kupac::readOne($sifra);

            if($this->e==null){
                header('location:' . App::config('url') . 'index/odjava');
                return;
            }

            $this->view->render($this->viewPutanja . 'detalji',[
                'legend'=>'Izmjena detalja kupca',
                'akcija'=>'Spremi',
                'poruka'=>'',
                'e'=>$this->e
            ]);
        return;
    }

    $this->pripremiZaView();

    try {
        $this->e->sifra=$sifra;
        $this->kontrola();
        $this->pripremiZaBazu();
        Kupac::update((array)$this->e);
        header('location:' . App::config('url') . 'kupac');
    }catch (\Exception $th) {
        $this->view->render($this->viewPutanja . 'detalji',[
            'legend'=>'Izmjena detalja kupca',
            'akcija'=>'Spremi',
            'poruka'=>$this->poruka . ' ' . $th->getMessage(),
            'e'=>$this->e
        ]);
    }

    }


    public function kontrolaNovi()
    {
        $this->kontrolaIme();
        $this->kontrolaPrezime();
        $this->kontrolaEmailNovi();
        $this->kontrolaAdresaRacun();
        $this->kontrolaAdresaDostava();
        $this->kontrolaBrojTelefonaNovi();
        
    }

    public function kontrola()
    {
        $this->kontrolaIme();
        $this->kontrolaPrezime();
        $this->kontrolaEmailIsti();
        $this->kontrolaAdresaRacun();
        $this->kontrolaAdresaDostava();
        $this->kontrolaBrojTelefonaIsti();
    }

    private function kontrolaIme()
    {
        $s=$this->e->ime;
        if(strlen(trim($s))===0){
            $this->poruka='Ime obavezno';
            throw new Exception();
        }

        if(strlen(trim($s))>50){
            $this->poruka='Ime ne smije imati više od 50 znakova';
            throw new Exception();
        }
    }

    private function kontrolaPrezime()
    {
        $s=$this->e->prezime;
        if(strlen(trim($s))===0){
            $this->poruka='Prezime obavezno';
            throw new Exception();
        }

        if(strlen(trim($s))>50){
            $this->poruka='Prezime ne smije imati više od 50 znakova';
            throw new Exception();
        }
    }

    private function kontrolaEmailNovi()
    {
        $s=$this->e->email;
        if(strlen(trim($s))===0){
            $this->poruka='Email adresa obavezna';
            throw new Exception();
        }

        if(strlen(trim($s))>50){
            $this->poruka='Email adresa ne smije imati više od 50 znakova';
            throw new Exception();
        }

        if(Kupac::postojiIstiEmailUBazi($s)){
            $this->poruka='Email adresa već postoji u bazi';
            throw new Exception();
        }
    }

    private function kontrolaEmailIsti()
    {
        $s=$this->e->email;
        if(strlen(trim($s))===0){
            $this->poruka='Email obavezan';
            throw new Exception();
        }

        if(strlen(trim($s))>50){
            $this->poruka='Email ne smije imati više od 50 znakova';
            throw new Exception();
        }

        if(isset($this->e->sifra)){
            if(!Kupac::postojiIstiMail($this->e->email,$this->e->sifra)){
                $this->poruka='Unesena email adresa već postoji u bazi';
                throw new Exception();
            }
        }else{
            if(!Kupac::postojiIstiMail($this->e->email)){
                $this->poruka='Unesena email adresa već postoji u bazi';
                throw new Exception();
            }
        }
    }

    private function kontrolaAdresaRacun()
    {
        $s=$this->e->adresazaracun;
        if(strlen(trim($s))===0){
            $this->poruka='Adresa za račun obavezna';
            throw new Exception();
        }

        if(strlen(trim($s))>100){
            $this->poruka='Adresa za račun ne smije imati više od 100 znakova';
            throw new Exception();
        }
    }

    private function kontrolaAdresaDostava()
    {
        $s=$this->e->adresazadostavu;
        if(strlen(trim($s))===0){
            $this->poruka='Adresa za dostavu obavezna';
            throw new Exception();
        }

        if(strlen(trim($s))>100){
            $this->poruka='Adresa za dostavu ne smije imati više od 100 znakova';
            throw new Exception();
        }
    }

    private function kontrolaBrojTelefonaNovi()
    {
        $s=$this->e->brojtelefona;

        if(strlen(trim($s))>20){
            $this->poruka='Broj telefona ne smije imati više od 50 znakova';
            throw new Exception();
        }

        if(Kupac::postojiIstiBrojUBazi($s)){
            $this->poruka='Broj telefona već postoji u bazi';
            throw new Exception();
        }
    }

    private function kontrolaBrojTelefonaIsti()
    {
        $s=$this->e->brojtelefona;

        if(strlen(trim($s))>20){
            $this->poruka='Broj telefona ne smije imati više od 20 znakova';
            throw new Exception();
        }

        if(isset($this->e->brojtelefona)){
            if(!Kupac::postojiIstiBrojTelefona($this->e->brojtelefona,$this->e->sifra)){
                $this->poruka='Uneseni broj telefona već postoji u bazi';
                throw new Exception();
            }
        }else{
            if(!Kupac::postojiIstiBrojTelefona($this->e->brojtelefona)){
                $this->poruka='Uneseni broj telefona već postoji u bazi';
                throw new Exception();
            }
        }
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

    public function pripremiZaView()
    {
        $this->e=(object)$_POST;
    }

    public function pripremiZaBazu()
    {
        
    }

    public function pocetniPodaci()
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

    public function v1($ruta)
    {
        switch($ruta){
            case 'read':
                $this->view->api(Kupac::read());
            break;
        }
    }

    public function ajaxSearch($uvjet){
        $this->view->api(Kupac::read($uvjet));
    }

}