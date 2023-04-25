<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProizvodController extends AutorizacijaController implements ViewSucelje
{
    private $viewPutanja = 'privatno' . DIRECTORY_SEPARATOR . 'proizvodi' . DIRECTORY_SEPARATOR;
    private $nf;
    private $e;
    private $poruka = '';

    public function __construct()
    {
        parent::__construct();
        $this->nf = new NumberFormatter('hr-HR', NumberFormatter::DECIMAL);
        $this->nf->setPattern(App::config('formatBroja'));
    }

    public function index()
    {
        if (isset($_GET['uvjet'])) {
            $uvjet = trim($_GET['uvjet']);
        } else {
            $uvjet = '';
        }

        if (isset($_GET['stranica'])) {
            $stranica = (int)$_GET['stranica'];
            if ($stranica < 1) {
                $stranica = 1;
            }
        } else {
            $stranica = 1;
        }


        $ukupnostr = Proizvod::ukupnoProizvoda($uvjet);

        $zadnjastr = (int)ceil($ukupnostr / App::config('brps'));

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

        $proizvodi = Proizvod::read($uvjet, $stranica);
        foreach ($proizvodi as $p) {
            if (file_exists(BP . 'public' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'proizvodi' .
                DIRECTORY_SEPARATOR . $p->sifra . '.png')) {
                $p->slika = App::config('url') . 'public/img/proizvodi/' . $p->sifra . '.png';
            } else {
                $p->slika = App::config('url') . 'public/img/nepoznato2.png';
            }
            $p->dostupnost = $p->dostupnost ? 'checkbox' : 'minus';
        }

        $this->view->render($this->viewPutanja . 'index', [
            'podaci' => $proizvodi,
            'uvjet' => $uvjet,
            'stranica' => $stranica,
            'zadnjastr' => $zadnjastr,
            'ukupnostr' => $ukupnostr
        ]);
    }

    public function novi()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->view->render($this->viewPutanja . 'detalji', [
                'legend' => 'Unos novog proizvoda',
                'poruka' => '',
                'e' => $this->pocetniPodaci()
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
            $this->view->render($this->viewPutanja . 'detalji', [
                'legend' => 'Unos novog proizvoda',
                'poruka' => $this->poruka,
                'e' => $this->e
            ]);
        }
    }

    public function promjena($sifra = 0)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->provjeraIntParametra($sifra);

            $this->e = Proizvod::readOne($sifra);

            if ($this->e == null) {
                header('location:' . App::config('url') . 'index/odjava');
                return;
            }

            $this->view->render($this->viewPutanja . 'detalji', [
                'legend' => 'Izmjena podataka o proizvodu',
                'poruka' => '',
                'e' => $this->e
            ]);
            return;
        }

        $this->pripremiZaView();

        try {
            $this->e->sifra = $sifra;
            $this->kontrolaPromjena();
            $this->pripremiZaBazu();
            Proizvod::update((array)$this->e);
            header('location:' . App::config('url') . 'proizvod');
        } catch (\Exception $th) {
            $this->view->render($this->viewPutanja . 'detalji', [
                'legend' => 'Izmjena podataka o proizvodu',
                'poruka' => $this->poruka . ' ' . $th->getMessage(),
                'e' => $this->e
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
        $s = $this->e->naziv;
        if (strlen(trim($s)) === 0) {
            $this->poruka = 'Naziv proizvoda obavezan';
            throw new Exception();
        }

        if (strlen(trim($s)) > 100) {
            $this->poruka = 'Naziv proizvoda ne smije imati više od 100 znakova';
            throw new Exception();
        }

        if (Proizvod::postojiIstiProizvodNovi($s)) {
            $this->poruka = 'Proizvod već postoji u bazi';
            throw new Exception();
        }
    }

    private function kontrolaNazivIsti()
    {
        $s = $this->e->naziv;
        if (strlen(trim($s)) === 0) {
            $this->poruka = 'Naziv proizvoda obavezan';
            throw new Exception();
        }

        if (strlen(trim($s)) > 100) {
            $this->poruka = 'Naziv proizvoda ne smije imati više od 100 znakova';
            throw new Exception();
        }

        if (isset($this->e->sifra)) {
            if (!Proizvod::postojiIstiProizvodPromjena($this->e->naziv, $this->e->sifra)) {
                $this->poruka = 'Uneseni proizvod već postoji u bazi';
                throw new Exception();
            }
        } else {
            if (!Proizvod::postojiIstiProizvodPromjena($this->e->naziv)) {
                $this->poruka = 'Uneseni proizvod već postoji u bazi';
                throw new Exception();
            }
        }
    }

    private function kontrolaProizvodjac()
    {
        $s = $this->e->proizvodjac;
        if (strlen(trim($s)) === 0) {
            $this->poruka = 'Ime proizvođača obavezno';
            throw new Exception();
        }

        if (strlen(trim($s)) > 50) {
            $this->poruka = 'Ime proizvođača ne smije imati više od 50 znakova';
            throw new Exception();
        }
    }

    private function kontrolaJedinicnacijena()
    {

        if (strlen(trim($this->e->jedinicnacijena)) === 0) {
            $this->poruka = 'Jedinična cijena obavezna';
            throw new Exception();
        }

        $jedinicnacijena = $this->nf->parse($this->e->jedinicnacijena);
        if (!$jedinicnacijena) {
            $this->poruka = 'Jedinična cijena nije u dobrom formatu (Format mora biti 0.000,00)';
            throw new Exception();
        }

        if ($jedinicnacijena < 0) {
            $this->poruka = 'Jedinična cijena mora biti veća od 0,00';
            throw new Exception();
        }

        if ($jedinicnacijena > 5000) {
            $this->poruka = 'Jedinična cijena ne smije biti veća od 3.000,00';
            throw new Exception();
        }
    }

    private function kontrolaOpis()
    {
        $s = $this->e->opis;
        if (strlen(trim($s)) === 0) {
            $this->poruka = 'Opis obavezan';
            throw new Exception();
        }
    }

    public function brisanje($sifra = 0)
    {
        $sifra = (int)$sifra;
        if ($sifra === 0) {
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
        $this->e = (object)$_POST;
        $this->e->dostupnost = $this->e->dostupnost === 'true' ? true : false;
    }

    public function pripremiZaBazu()
    {
        $this->e->jedinicnacijena = $this->nf->parse($this->e->jedinicnacijena);
    }

    public function pocetniPodaci()
    {
        $e = new stdClass();
        $e->naziv = '';
        $e->proizvodjac = '';
        $e->jedinicnacijena = '';
        $e->opis = '';
        $e->dostupnost = false;
        return $e;
    }

    private function prilagodiPodatke($proizvodi)
    {
        foreach ($proizvodi as $p) {
            $p->naziv;
            $p->proizvodjac;
            $p->jedinicnacijena = $this->formatIznosa($p->jedinicnacijena);
            $p->dostupnost = $p->dostupnost ? 'checkbox' : 'minus';
        }
        return $proizvodi;
    }

    private function formatIznosa($broj)
    {
        if ($broj == null) {
            return $this->nf->format(0);
        }
        return $this->nf->format($broj);
    }

    public function traziProizvod($uvjet)
    {
        $proizvodi = Proizvod::traziProizvod($uvjet);

        foreach ($proizvodi as $p) {
            if (file_exists(BP . 'public' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'proizvodi' .
                DIRECTORY_SEPARATOR . $p->sifra . '.png')) {
                $p->slika = App::config('url') . 'public/img/proizvodi/' . $p->sifra . '.png';
            } else {
                $p->slika = App::config('url') . 'public/img/nepoznato2.png';
            }
        }

        $this->view->api($proizvodi);
    }

    public function spremiSliku()
    {
        $slika = $_POST['slika'];
        $slika = str_replace('data:image/png;base64,', '', $slika);
        $slika = str_replace(' ', '+', $slika);
        $data = base64_decode($slika);

        file_put_contents(BP . 'public' . DIRECTORY_SEPARATOR . 'img' .
            DIRECTORY_SEPARATOR . 'proizvodi' . DIRECTORY_SEPARATOR . $_POST['id'] .
            '.png', $data);

        $res = new stdClass();
        $res->error = false;
        $res->description = 'Uspješno spremljeno';
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($res);
    }

    public function excel()
    {

        // (B) CREATE A NEW SPREADSHEET
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Sve narudžbe");

        // (C) SET CELL VALUE

        $sheet->setCellValue('A1', 'Naziv proizvoda');
        $sheet->setCellValue('B1', 'Proizvođač');
        $sheet->setCellValue('C1', 'Cijena proizvoda');
        $sheet->setCellValue('D1', 'Opis');
        $sheet->setCellValue('E1', 'Dostupnost');

        $spreadsheet->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);

        $redniBroj = 2;

        foreach (Proizvod::readExcelProizvod() as $red) {
            $sheet->setCellValue('A' . $redniBroj, $red->naziv);
            $sheet->setCellValue('B' . $redniBroj, $red->proizvodjac);
            $sheet->setCellValue('C' . $redniBroj, $red->jedinicnacijena);
            $sheet->setCellValue('D' . $redniBroj, $red->opis);
            $sheet->setCellValue('E' . $redniBroj, $red->dostupnost);
            $redniBroj++;
        }

        foreach ($sheet->getColumnIterator() as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }

        $spreadsheet->getActiveSheet()
            ->getStyle('C2:C' . $redniBroj)
            ->getNumberFormat()
            ->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_EUR);
        $spreadsheet->getActiveSheet()
            ->getStyle('A1:E' . $redniBroj)
            ->getBorders()
            ->getOutline()
            ->setBorderStyle(PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);


        // (D) SEND DOWNLOAD HEADERS
        // ob_clean();
        // ob_start();
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Proizvodi.xlsx"');
        header('Cache-Control: max-age=0');
        header('Expires: Fri, 11 Nov 2011 11:11:11 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');
        // ob_end_flush();

    }
}
