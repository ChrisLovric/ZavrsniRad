<?php

class PlacanjeController extends AutorizacijaController implements ViewSucelje
{
    private $viewPutanja='privatno' . DIRECTORY_SEPARATOR . 'placanje' . DIRECTORY_SEPARATOR;
    private $e;
    private $poruka='';

    public function index()    
    {
        $this->view->render($this->viewPutanja . 'index',[
            'podaci'=>Placanje::read(),
            'css'=>'placanje.css'
        ]);
        
    }

    public function novi()
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            $this->view->render($this->viewPutanja . 'detalji',[
                'legend'=>'Unos nove vrste plaćanja',
                'poruka'=>'',
                'e'=>$this->pocetniPodaci()
            ]);
            return;
        }
        $this->pripremiZaView();

        try {
            $this->kontrolaNovi();
            $this->pripremiZaBazu();
            Placanje::create((array)$this->e);
            header('location:' . App::config('url') . 'placanje');
        } catch (\Exception $th) {
            $this->view->render($this->viewPutanja . 'detalji',[
                'legend'=>'Unos nove vrste plaćanja',
                'poruka'=>$this->poruka,
                'e'=>$this->e
            ]);
        }
    }

    public function promjena($sifra=0)
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            $this->provjeraIntParametra($sifra);

            $this->e=Placanje::readOne($sifra);

            if($this->e==null){
                header('location:' . App::config('url') . 'index/odjava');
                return;
            }

            $this->view->render($this->viewPutanja . 'detalji',[
                'legend'=>'Izmjena vrste plaćanja',
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
        Placanje::update((array)$this->e);
        header('location:' . App::config('url') . 'placanje');
    }catch (\Exception $th) {
        $this->view->render($this->viewPutanja . 'detalji',[
            'legend'=>'Izmjena vrste plaćanja',
            'poruka'=>$this->poruka . ' ' . $th->getMessage(),
            'e'=>$this->e
        ]);
    }

    }

    public function brisanje($sifra=0)
    {
        $sifra=(int)$sifra;
        if($sifra===0){
            header('location: ' . App::config('url') . 'index/odjava');
            return;
        }
        Placanje::delete($sifra);
        header('location: ' . App::config('url') . 'placanje/index');
    }

    public function pripremiZaView()
    {
        $this->e=(object)$_POST;
    }

    public function pripremiZaBazu()
    {
        
    }

    private function kontrolaNovi()
    {
        return $this->kontrolaVrstaplacanjaNovi();
    }

    private function kontrolaPromjena()
    {
        return $this->kontrolaVrstaplacanjaPromjena();
    }

    private function kontrolaVrstaplacanjaNovi()
    {
        $s=$this->e->vrstaplacanja;
        if(strlen(trim($s))===0){
            $this->poruka='Vrsta plaćanja obavezna';
            throw new Exception();
        }

        if(strlen(trim($s))>50){
            $this->poruka='Vrsta plaćanja ne smije imati više od 50 znakova';
            throw new Exception();
        }

        if(Placanje::postojiVrstaPlacanjaUBaziNovi($s)){
            $this->poruka='Vrsta plaćanja već postoji u bazi';
            throw new Exception();
        }
    }

    private function kontrolaVrstaplacanjaPromjena()
    {
        $s=$this->e->vrstaplacanja;
        if(strlen(trim($s))===0){
            $this->poruka='Vrsta plaćanja obavezna';
            throw new Exception();
        }

        if(strlen(trim($s))>50){
            $this->poruka='Vrsta plaćanja ne smije imati više od 50 znakova';
            throw new Exception();
        }

        if(isset($this->e->sifra)){
            if(!Placanje::postojiVrstaPlacanjaUBaziPromjena($this->e->vrstaplacanja,$this->e->sifra)){
                $this->poruka='Unesena vrsta plaćanja već postoji u bazi';
                throw new Exception();
            }
        }else{
            if(!Placanje::postojiVrstaPlacanjaUBaziPromjena($this->e->vrstaplacanja)){
                $this->poruka='Unesena vrsta plaćanja već postoji u bazi';
                throw new Exception();
            }
        }
    }

    public function pocetniPodaci()
    {
        $e=new stdClass();
        $e->vrstaplacanja='';
        return $e;
    }

}