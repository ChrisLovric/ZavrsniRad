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
            'podaci'=>$this->prilagodiPodatke(Detaljinarudzbe::read()),
            'css'=>'detaljinarudzbe.css'
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
        Detaljinarudzbe::delete($sifra);
        header('location: ' . App::config('url') . 'detaljinarudzbe/index');
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
        $e->cijena='';
        $e->kolicina='';
        $e->popust='';
        $e->narudzba='';
        $e->proizvod='';
        return $e;
    }

    private function prilagodiPodatke($detaljinarudzbe)
    {
        foreach($detaljinarudzbe as $dn){
            $dn->cijena=$this->formatIznosa($dn->cijena);
            $dn->kolicina;
            $dn->popust=$this->formatIznosa($dn->popust);
            $dn->brojnarudzbe;
            $dn->naziv;
        }
        return $detaljinarudzbe;
    }

    private function formatIznosa($broj)
    {
        if($broj==null){
            return $this->nf->format(0);
        }
            return $this->nf->format($broj);
    }

}