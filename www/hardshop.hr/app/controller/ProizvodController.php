<?php

class ProizvodController extends AutorizacijaController implements ViewSucelje
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
        parent::setJSdependency([
            '<script src="' . App::config('url') . 'public/js/dependency/jquery-ui.js"></script>',
            '<script>
                 let url=\'' . App::config('url') . '\';
             </script>'
         ]);

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

        $ukupnostr=Proizvod::ukupnoProizvoda($uvjet);

        $zadnjastr=(int)ceil($ukupnostr/App::config('brps'));

        $this->view->render($this->viewPutanja . 'index',[
            'podaci'=>$this->prilagodiPodatke(Proizvod::read($uvjet,$stranica)),
            'uvjet'=>$uvjet,
            'stranica'=>$stranica,
            'zadnjastr'=>$zadnjastr,
            'ukupnostr'=>$ukupnostr
        ]);
        
    }

    public function novi()
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            $this->view->render($this->viewPutanja . 'detalji',[
                'legend'=>'Unos novog proizvoda',
                'poruka'=>'',
                'e'=>$this->pocetniPodaci()
            ]);
            return;
        }
        $this->pripremiZaView();

        try {
            $this->kontrolaNovi();
            $this->pripremiZaBazu();
            Proizvod::create((array)$this->e);
            header('location:' . App::config('url') . 'proizvod');
        } catch (\Exception $th) {
            $this->view->render($this->viewPutanja . 'detalji',[
                'legend'=>'Unos novog proizvoda',
                'poruka'=>$this->poruka,
                'e'=>$this->e
            ]);
        }
    }

    public function promjena($sifra=0)
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            $this->provjeraIntParametra($sifra);

            $this->e=Proizvod::readOne($sifra);

            if($this->e==null){
                header('location:' . App::config('url') . 'index/odjava');
                return;
            }

            $this->view->render($this->viewPutanja . 'detalji',[
                'legend'=>'Izmjena podataka o proizvodu',
                'poruka'=>'',
                'e'=>$this->e
            ]);
        return;
    }

    $this->pripremiZaView();

    try {
        $this->e->sifra=$sifra;
        $this->kontrolaPromjena();
        $this->pripremiZaBazu();
        Proizvod::update((array)$this->e);
        header('location:' . App::config('url') . 'proizvod');
    }catch (\Exception $th) {
        $this->view->render($this->viewPutanja . 'detalji',[
            'legend'=>'Izmjena podataka o proizvodu',
            'poruka'=>$this->poruka . ' ' . $th->getMessage(),
            'e'=>$this->e
        ]);
    }

    }

    public function kontrolaNovi()
    {
        $this->kontrolaNazivNovi();
        $this->kontrolaProizvodjac();
        $this->kontrolaJedinicnacijena();
        $this->kontrolaOpis();
    }

    public function kontrolaPromjena()
    {
        $this->kontrolaNazivIsti();
        $this->kontrolaProizvodjac();
        $this->kontrolaJedinicnacijena();
        $this->kontrolaOpis();
    }

    private function kontrolaNazivNovi()
    {
        $s=$this->e->naziv;
        if(strlen(trim($s))===0){
            $this->poruka='Naziv proizvoda obavezan';
            throw new Exception();
        }

        if(strlen(trim($s))>100){
            $this->poruka='Naziv proizvoda ne smije imati više od 50 znakova';
            throw new Exception();
        }

        if(Proizvod::postojiIstiProizvodNovi($s)){
            $this->poruka='Proizvod već postoji u bazi';
            throw new Exception();
        }
    }

    private function kontrolaNazivIsti()
    {
        $s=$this->e->naziv;
        if(strlen(trim($s))===0){
            $this->poruka='Naziv proizvoda obavezan';
            throw new Exception();
        }

        if(strlen(trim($s))>100){
            $this->poruka='Naziv proizvoda ne smije imati više od 50 znakova';
            throw new Exception();
        }

        if(isset($this->e->sifra)){
            if(!Proizvod::postojiIstiProizvodPromjena($this->e->naziv,$this->e->sifra)){
                $this->poruka='Uneseni proizvod već postoji u bazi';
                throw new Exception();
            }
        }else{
            if(!Proizvod::postojiIstiProizvodPromjena($this->e->naziv)){
                $this->poruka='Uneseni proizvod već postoji u bazi';
                throw new Exception();
            }
        }
    }

    private function kontrolaProizvodjac()
    {
        $s=$this->e->proizvodjac;
        if(strlen(trim($s))===0){
            $this->poruka='Ime proizvođača obavezno';
            throw new Exception();
        }

        if(strlen(trim($s))>50){
            $this->poruka='Ime proizvođača ne smije imati više od 50 znakova';
            throw new Exception();
        }
    }

    private function kontrolaJedinicnacijena()
    {
        
        if(strlen(trim($this->e->jedinicnacijena))===0){
            $this->poruka='Jedinična cijena obavezna';
            throw new Exception();
        }

        $jedinicnacijena=$this->nf->parse($this->e->jedinicnacijena);
        if(!$jedinicnacijena){
            $this->poruka='Jedinična cijena nije u dobrom formatu (Format mora biti 0.000,00)';
            throw new Exception();
        }

        if($jedinicnacijena<0){
            $this->poruka='Jedinična cijena morat biti veća od 0,00';
            throw new Exception();
        }

        if($jedinicnacijena>5000){
            $this->poruka='Jedinična cijena ne smije biti veća od 3.000,00';
            throw new Exception();
        }
    }

    private function kontrolaOpis()
    {
        $s=$this->e->opis;
        if(strlen(trim($s))===0){
            $this->poruka='Opis obavezan';
            throw new Exception();
        }
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
        
    }

    public function pripremiZaView()
    {
        $this->e=(object)$_POST;
        $this->e->dostupnost=$this->e->dostupnost==='true' ? true : false;
    }

    public function pripremiZaBazu()
    {
        $this->e->jedinicnacijena=$this->nf->parse($this->e->jedinicnacijena);
    }

    public function pocetniPodaci()
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

    public function ajaxSearch($uvjet){
        $this->view->api(Proizvod::read($uvjet));
    }

}