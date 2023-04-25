<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class NarudzbaController extends AutorizacijaController implements ViewSucelje
{
    private $viewPutanja = 'privatno' . DIRECTORY_SEPARATOR . 'narudzbe' . DIRECTORY_SEPARATOR;
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

        $this->view->render($this->viewPutanja . 'index', [
            'podaci' => $this->prilagodiPodatke(Narudzba::read())
        ]);
    }

    private function prilagodiPodatke($narudzbe)
    {
        foreach ($narudzbe as $n) {
            if ($n->datumnarudzbe == null) {
                $n->datumnarudzbe = 'Nije definirano';
            } else {
                $n->datumnarudzbe = date('d.m.Y.', strtotime($n->datumnarudzbe));
            }
            if ($n->datumisporuke == null) {
                $n->datumisporuke = 'Nije definirano';
            } else {
                $n->datumisporuke = date('d.m.Y.', strtotime($n->datumisporuke));
            }
            if ($n->datumplacanja == null) {
                $n->datumplacanja = 'Nije definirano';
            } else {
                $n->datumplacanja = date('d.m.Y.', strtotime($n->datumplacanja));
            }
            if ($n->kupac == null) {
                $n->kupac = 'Nije definirano';
            }
            if ($n->vrstaplacanja == null) {
                $n->vrstaplacanja = 'Nije definirano';
            }
        }
        return $narudzbe;
    }

    public function novi()
    {
        $kupacSifra = Kupac::prviKupac();
        if ($kupacSifra == 0) {
            header('location: ' . App::config('url') . 'kupac?p=1');
        }

        $placanjeSifra = Placanje::prvoPlacanje();
        if ($placanjeSifra == 0) {
            header('location: ' . App::config('url') . 'placanje?p=1');
        }

        $this->promjena(Narudzba::create([
            'brojnarudzbe' => '',
            'datumnarudzbe' => '',
            'datumisporuke' => null,
            'datumplacanja' => null,
            'kupac' => $kupacSifra,
            'placanje' => $placanjeSifra
        ]));
    }

    public function odustani($sifra = '')
    {
        $e = Narudzba::readOne($sifra);

        if (
            $e->datumisporuke == null &&
            $e->datumplacanja == null &&
            $e->proizvod = Narudzba::detaljiNarudzbe($sifra) == null
        ) {
            Narudzba::delete($e->sifra);
        }
        header('location: ' . App::config('url') . 'narudzba');
    }

    public function promjena($sifra = '')
    {
        parent::setCSSdependency([
            '<link rel="stylesheet" href="' . App::config('url') . 'public/css/dependency/jquery-ui.css">'
        ]);
        parent::setJSdependency([
            '<script src="' . App::config('url') . 'public/js/dependency/jquery-ui.js"></script>',
            '<script>
                let url=\'' . App::config('url') . '\';
                let narudzbasifra=' . $sifra . ';
            </script>'
        ]);

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->promjena_GET($sifra);
            return;
        }

        $this->e = (object)$_POST;
        $this->e->proizvodi = Narudzba::detaljiNarudzbe($sifra);

        try {
            $odabraniKupac = Kupac::readOne($this->e->kupac);
            $this->e->sifra = $sifra;
            $this->kontrola();
            $this->pripremiZaBazu();
            Narudzba::update((array)$this->e);
            header('location:' . App::config('url') . 'narudzba');
        } catch (\Exception $th) {
            $this->view->render($this->viewPutanja . 'detalji', [
                'legend' => 'Unos nove narudzbe',
                'poruka' => $this->poruka,
                'kupac' => $odabraniKupac,
                'e' => $this->e,
                'placanja' => Placanje::read()
            ]);
        }
    }

    public function kontrola()
    {
        $this->kontrolaBrojNarudzbe();
        $this->kontrolaDatumNarudzbe();
    }

    private function kontrolaBrojNarudzbe()
    {
        $s = $this->e->brojnarudzbe;

        if (strlen(trim($s)) === 0) {
            $this->poruka = 'Broj narudžbe obavezan';
            throw new Exception();
        }

        $brojnarudzbe = $this->nf->parse($this->e->brojnarudzbe);
        if ($brojnarudzbe <= 0) {
            $this->poruka = 'Broj narudžbe mora biti veći od 0';
            throw new Exception();
        }

        if (isset($this->e->sifra)) {
            if (!Narudzba::postojiIstiBrojNarudzbe($this->e->brojnarudzbe, $this->e->sifra)) {
                $this->poruka = 'Broj narudžbe već postoji u bazi';
                throw new Exception();
            }
        } else {
            if (!Narudzba::postojiIstiBrojNarudzbe($this->e->brojnarudzbe)) {
                $this->poruka = 'Broj narudžbe već postoji u bazi';
                throw new Exception();
            }
        }
    }

    private function kontrolaDatumNarudzbe()
    {
        $s = $this->e->datumnarudzbe;

        if (strlen(trim($s)) === 0) {
            $this->poruka = 'Datum narudžbe obavezan';
            throw new Exception();
        }
    }

    private function promjena_GET($sifra)
    {
        $this->e = Narudzba::readOne($sifra);
        $placanja = [];
        $p = new stdClass();
        $p->sifra = 0;
        $p->vrstaplacanja = 'Nije odabrano';
        $placanja[] = $p;
        foreach (Placanje::read() as $placanje) {
            $placanja[] = $placanje;
        }

        if ($this->e->datumnarudzbe != null) {
            $this->e->datumnarudzbe = date('Y-m-d', strtotime($this->e->datumnarudzbe));
        }
        if ($this->e->datumisporuke != null) {
            $this->e->datumisporuke = date('Y-m-d', strtotime($this->e->datumisporuke));
        }
        if ($this->e->datumplacanja != null) {
            $this->e->datumplacanja = date('Y-m-d', strtotime($this->e->datumplacanja));
        }

        $this->view->render($this->viewPutanja . 'detalji', [
            'e' => $this->e,
            'kupac' => Kupac::readOne($this->e->kupac),
            'placanja' => $placanja,
            'legend' => '',
            'poruka' => $this->poruka
        ]);
    }

    public function brisanje($sifra = 0)
    {
        $sifra = (int)$sifra;
        if ($sifra === 0) {
            header('location: ' . App::config('url') . 'index/odjava');
            return;
        }
        Narudzba::delete($sifra);
        header('location: ' . App::config('url') . 'narudzba/index');
    }

    public function pripremiZaView()
    {
    }

    public function pripremiZaBazu()
    {
        if ($this->e->kupac == 0) {
            $this->e->kupac = null;
        }
        if ($this->e->datumnarudzbe == '') {
            $this->e->datumnarudzbe = null;
        }
        if ($this->e->datumplacanja == '') {
            $this->e->datumplacanja = null;
        }
        if ($this->e->datumisporuke == '') {
            $this->e->datumisporuke = null;
        }
    }

    public function pocetniPodaci()
    {
        $e = new stdClass();
        $e->brojnarudzbe = '';
        $e->datumnarudzbe = '';
        $e->datumisporuke = null;
        $e->datumplacanja = null;
        $e->kupac = 0;
        $e->placanje = null;
        return $e;
    }

    private function formatIznosa($broj)
    {
        if ($broj == null) {
            return $this->nf->format(0);
        }
        return $this->nf->format($broj);
    }

    public function dodajproizvod()
    {
        $res = new stdClass();
        if (!Narudzba::postojiProizvodNarudzba(
            $_GET['narudzba'],
            $_GET['proizvod']
        )) {
            Narudzba::dodajProizvodNarudzba(
                $_GET['narudzba'],
                $_GET['proizvod']
            );
            $res->error = false;
            $res->description = 'Uspješno dodano';
        } else {
            $res->error = true;
            $res->description = 'Proizvod već postoji u narudžbi';
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($res, JSON_NUMERIC_CHECK);
    }

    public function obrisiproizvod()
    {
        Narudzba::obrisiProizvodNarudzba(
            $_GET['narudzba'],
            $_GET['proizvod']
        );
    }

    public function excel()
    {

        // (B) CREATE A NEW SPREADSHEET
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Sve narudžbe");

        // (C) SET CELL VALUE

        $sheet->setCellValue('A1', 'Broj narudžbe');
        $sheet->setCellValue('B1', 'Kupac');
        $sheet->setCellValue('C1', 'Datum narudžbe');
        $sheet->setCellValue('D1', 'Vrsta plaćanja');
        $sheet->setCellValue('E1', 'Naziv proizvoda');
        $sheet->setCellValue('F1', 'Cijena proizvoda');


        $spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);

        $redniBroj = 2;

        foreach (Narudzba::readExcelNarudzba() as $red) {
            $sheet->setCellValue('A' . $redniBroj, $red->brojnarudzbe);
            $sheet->setCellValue('B' . $redniBroj, $red->kupac);
            $sheet->setCellValue('C' . $redniBroj, $red->datumnarudzbe);
            $sheet->setCellValue('D' . $redniBroj, $red->vrstaplacanja);
            $sheet->setCellValue('E' . $redniBroj, $red->naziv);
            $sheet->setCellValue('F' . $redniBroj, $red->cijena);

            $redniBroj++;
        }
        $sheet->setCellValue('F' . $redniBroj, '=sum(F2:F' . ($redniBroj - 1) . ')');

        foreach ($sheet->getColumnIterator() as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }

        $spreadsheet->getActiveSheet()
            ->getStyle('F2:F' . $redniBroj)
            ->getNumberFormat()
            ->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_EUR);
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
        header('Content-Disposition: attachment;filename="Narudžbe.xlsx"');
        header('Cache-Control: max-age=0');
        header('Expires: Fri, 11 Nov 2011 11:11:11 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');
        // ob_end_flush();

    }
}
