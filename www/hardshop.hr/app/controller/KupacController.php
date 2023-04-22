<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

        parent::setCSSdependency([
            '<link rel="stylesheet" href="' . App::config('url') . 'public/css/dependency/jquery-ui.css">',
            '<link rel="stylesheet" href="' . App::config('url') . 'public/css/dependency/cropper.css">'
         ]);
        parent::setJSdependency([
            '<script src="' . App::config('url') . 'public/js/dependency/jquery-ui.js"></script>',
            '<script src="' . App::config('url') . 'public/js/dependency/cropper.js"></script>',
            '<script>
                 let url=\'' . App::config('url') . '\';
             </script>'
         ]);

         $kupci=Kupac::read($uvjet,$stranica);
         foreach($kupci as $k){
            if(file_exists(BP . 'public' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'kupci' . 
            DIRECTORY_SEPARATOR . $k->sifra . '.png')){
                $k->slika=App::config('url') . 'public/img/kupci/' . $k->sifra . '.png';
            }else{
                $k->slika=App::config('url') . 'public/img/nepoznato.png';
            }
         }


        $this->view->render($this->viewPutanja . 'index',[
            'podaci'=>$kupci,
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
            $this->poruka='Broj telefona ne smije imati više od 20 znakova';
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

    public function traziKupca($uvjet)
    {
        $kupci=Kupac::traziKupca($uvjet);

        foreach($kupci as $k){
            if(file_exists(BP . 'public' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'kupci' . 
            DIRECTORY_SEPARATOR . $k->sifra . '.png')){
                $k->slika=App::config('url') . 'public/img/kupci/' . $k->sifra . '.png';
            }else{
                $k->slika=App::config('url') . 'public/img/nepoznato.png';
            }
        }
        $this->view->api($kupci);
    }

    public function spremiSliku()
    {
        $slika=$_POST['slika'];
        $slika=str_replace('data:image/png;base64,','',$slika);
        $slika=str_replace(' ','+',$slika);
        $data=base64_decode($slika);

        file_put_contents(BP . 'public' . DIRECTORY_SEPARATOR . 'img' . 
        DIRECTORY_SEPARATOR . 'kupci' . DIRECTORY_SEPARATOR . $_POST['id'] . 
        '.png', $data);

        $res=new stdClass();
        $res->error=false;
        $res->description='Uspješno spremljeno';
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($res);
    }

    public function excel()
    {

    // (B) CREATE A NEW SPREADSHEET
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle("Svi kupci");

    // (C) SET CELL VALUE

    $sheet->setCellValue('A1', 'Ime');
    $sheet->setCellValue('B1', 'Prezime');
    $sheet->setCellValue('C1', 'Email adresa');
    $sheet->setCellValue('D1', 'Adresa za račun');
    $sheet->setCellValue('E1', 'Adresa za dostavu');
    $sheet->setCellValue('F1', 'Broj telefona');

    $spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold( true );

    $redniBroj=2;

     foreach(Kupac::readExcelKupac() as $red){
         $sheet->setCellValue('A' . $redniBroj, $red->ime);
         $sheet->setCellValue('B' . $redniBroj, $red->prezime);
         $sheet->setCellValue('C' . $redniBroj, $red->email);
         $sheet->setCellValue('D' . $redniBroj, $red->adresazaracun);
         $sheet->setCellValue('E' . $redniBroj, $red->adresazadostavu);
         $sheet->setCellValue('F' . $redniBroj, $red->brojtelefona);
         $redniBroj++;
     }

     foreach ($sheet->getColumnIterator() as $column) {
        $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
     }

     $spreadsheet->getActiveSheet()
    ->getStyle('A1:F' . $redniBroj)
    ->getBorders()
    ->getOutline()
    ->setBorderStyle(PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
    

    // (D) SEND DOWNLOAD HEADERS
    // ob_clean();
    // ob_start();
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Kupci.xlsx"');
    header('Cache-Control: max-age=0');
    header('Expires: Fri, 11 Nov 2011 11:11:11 GMT');
    header('Last-Modified: '. gmdate('D, d M Y H:i:s') .' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');
    $writer->save('php://output');
    // ob_end_flush();

    }

}