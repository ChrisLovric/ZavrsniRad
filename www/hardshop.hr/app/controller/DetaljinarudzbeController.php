<?php

class DetaljinarudzbeController extends AutorizacijaController implements ViewSucelje
{
    private $viewPutanja='privatno' . DIRECTORY_SEPARATOR . 'detaljinarudzbe' . DIRECTORY_SEPARATOR;
    private $nf;
    private $e;
    private $poruke=[];

    public function __construct()
    {
        parent::__construct();
        $this->nf=new NumberFormatter('hr-HR',NumberFormatter::DECIMAL);
        $this->nf->setPattern(App::config('formatBroja'));
    }

    public function index()    
    {

        $this->view->render($this->viewPutanja . 'index',[
            'podaci'=>$this->prilagodiPodatke(Detaljinarudzbe::read())
        ]);
        
    }

    private function prilagodiPodatke($detaljinarudzbe)
    {
        foreach($detaljinarudzbe as $dn){
            $dn->cijena=$this->formatIznosa($dn->cijena);
            $dn->kolicina;
            $dn->popust=$this->formatIznosa($dn->popust);
            $dn->brojnarudzbe;
            $dn->naziv;
            if($dn->popust==null){
                $dn->popust='Nije definirano';
            }
        }
        return $detaljinarudzbe;
    }

    public function novi()
    {
        $narudzbaSifra=Narudzba::prvaNarudzba();
        if($narudzbaSifra==0){
            header('location: ' . App::config('url') . 'narudzba?p=1');
        }

        $proizvodSifra=Proizvod::prviProizvod();
        if($proizvodSifra==0){
            header('location: ' . App::config('url') . 'proizvod?p=1');
        }

        $this->promjena(Detaljinarudzbe::create([
            'cijena'=>'',
            'kolicina'=>'',
            'popust'=>null,
            'narudzba'=>$narudzbaSifra,
            'proizvod'=>$proizvodSifra
        ]));
    }

    public function odustani($sifra='')
    {
        $e=Detaljinarudzbe::readOne($sifra);

        if(
        $e->popust==null){
        Detaljinarudzbe::delete($e->sifra);
        }
        header('location: ' . App::config('url') . 'detaljinarudzbe');
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
            Detaljinarudzbe::update((array)$this->e);
            header('location:' . App::config('url') . 'detaljinarudzbe');
        } catch (\Exception $th) {
            $this->view->render($this->viewPutanja . 'detalji',[
                'poruke'=>$this->poruke,
                'e'=>$this->e
            ]);
        }
    }

    private function promjena_GET($sifra)
    {
        $this->e=Detaljinarudzbe::readOne($sifra);
        $narudzbe=[];
        $n=new stdClass();
        $n->sifra=0;
        $n->brojnarudzbe='Nije odabrano';
        $narudzbe[]=$n;
        foreach(Narudzba::read() as $narudzba){
            $narudzbe[]=$narudzba;
        }

        $this->e=Detaljinarudzbe::readOne($sifra);
        $proizvodi=[];
        $p=new stdClass();
        $p->sifra=0;
        $p->naziv='Nije odabrano';
        $proizvodi[]=$p;
        foreach(Proizvod::read() as $proizvod){
            $proizvodi[]=$proizvod;
        }

        $this->view->render($this->viewPutanja . 'detalji',[
            'e'=>$this->e,
            'narudzbe'=>$narudzbe,
            'proizvodi'=>$proizvodi
        ]);
    }

    public function brisanje($sifra=0)
    {
        $sifra=(int)$sifra;
        if($sifra===0){
            header('location: ' . App::config('url') . 'index/odjava');
            return;
        }
        Detaljinarudzbe::delete($sifra);
        header('location: ' . App::config('url') . 'detaljinarudzbe/index');
    }

    private function kontrola()
    {

    }

    public function pripremiZaView()
    {

    }

    public function pripremiZaBazu()
    {
        if($this->e->popust==''){
            $this->e->popust=null;
        }
    }

    public function pocetniPodaci()
    {
        $e=new stdClass();
        $e->cijena='';
        $e->kolicina='';
        $e->popust='';
        $e->narudzba='';
        $e->proizvod='';
        return $e;
    }

    private function formatIznosa($broj)
    {
        if($broj==null){
            return $this->nf->format(0);
        }
            return $this->nf->format($broj);
    }

}