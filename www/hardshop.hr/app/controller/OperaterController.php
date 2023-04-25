<?php

class OperaterController extends AdminController implements ViewSucelje
{
    private $viewPutanja = 'privatno' . DIRECTORY_SEPARATOR . 'operateri' . DIRECTORY_SEPARATOR;
    private $e;
    private $poruka = '';

    public function index()
    {
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

        $operateri = Operater::read();
        foreach ($operateri as $o) {
            unset($o->lozinka);
            if (file_exists(BP . 'public' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'operateri' .
                DIRECTORY_SEPARATOR . $o->sifra . '.png')) {
                $o->slika = App::config('url') . 'public/img/operateri/' . $o->sifra . '.png';
            } else {
                $o->slika = App::config('url') . 'public/img/nepoznato.png';
            }
        }

        $this->view->render($this->viewPutanja . 'index', [
            'podaci' => $operateri
        ]);
    }

    public function novi()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->view->render($this->viewPutanja . 'novi', [
                'poruka' => '',
                'e' => $this->pocetniPodaci()
            ]);
            return;
        }
        $this->pripremiZaView();

        try {
            $this->kontrolaNovi();
            $this->pripremiZaBazu();
            Operater::createNovi((array)$this->e);
            header('location:' . App::config('url') . 'operater');
        } catch (\Exception $th) {
            $this->view->render($this->viewPutanja . 'novi', [
                'poruka' => $this->poruka,
                'e' => $this->e
            ]);
        }
    }

    public function promjena($sifra = 0)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->provjeraIntParametra($sifra);

            $this->e = Operater::readOne($sifra);

            if ($this->e == null) {
                header('location:' . App::config('url') . 'index/odjava');
                return;
            }

            $this->view->render($this->viewPutanja . 'promjena', [
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
            Operater::update((array)$this->e);
            header('location:' . App::config('url') . 'operater');
        } catch (\Exception $th) {
            $this->view->render($this->viewPutanja . 'promjena', [
                'poruka' => $this->poruka . ' ' . $th->getMessage(),
                'e' => $this->e
            ]);
        }
    }

    public function brisanje($sifra = 0)
    {
        $sifra = (int)$sifra;
        if ($sifra === 0) {
            header('location: ' . App::config('url') . 'index/odjava');
            return;
        }
        Operater::delete($sifra);
        header('location: ' . App::config('url') . 'operater/index');
    }

    public function pripremiZaView()
    {
        $this->e = (object)$_POST;
    }

    public function pripremiZaBazu()
    {
    }

    private function kontrolaNovi()
    {
        $this->kontrolaIme();
        $this->kontrolaPrezime();
        $this->kontrolaEmailNovi();
        $this->kontrolaUloga();
    }

    private function kontrolaPromjena()
    {
        $this->kontrolaIme();
        $this->kontrolaPrezime();
        $this->kontrolaEmailPromjena();
        $this->kontrolaUloga();
    }

    private function kontrolaIme()
    {
        $s = $this->e->ime;
        if (strlen(trim($s)) === 0) {
            $this->poruka = 'Ime obavezno';
            throw new Exception();
        }

        if (strlen(trim($s)) > 50) {
            $this->poruka = 'Ime ne smije imati više od 50 znakova';
            throw new Exception();
        }
    }

    private function kontrolaPrezime()
    {
        $s = $this->e->prezime;
        if (strlen(trim($s)) === 0) {
            $this->poruka = 'Prezime obavezno';
            throw new Exception();
        }

        if (strlen(trim($s)) > 50) {
            $this->poruka = 'Prezime ne smije imati više od 50 znakova';
            throw new Exception();
        }
    }

    private function kontrolaEmailNovi()
    {
        $s = $this->e->email;
        if (strlen(trim($s)) === 0) {
            $this->poruka = 'Email obavezan';
            throw new Exception();
        }

        if (Operater::postojiIstiMailUBazi($s)) {
            $this->poruka = 'Unesena email adresa već postoji u bazi';
            throw new Exception();
        }

        if (strlen(trim($s)) > 50) {
            $this->poruka = 'Email adresa ne smije imati više od 50 znakova';
            throw new Exception();
        }
    }

    private function kontrolaEmailPromjena()
    {
        $s = $this->e->email;
        if (strlen(trim($s)) === 0) {
            $this->poruka = 'Email obavezan';
            throw new Exception();
        }

        if (strlen(trim($s)) > 50) {
            $this->poruka = 'Email adresa ne smije imati više od 50 znakova';
            throw new Exception();
        }

        if (isset($this->e->sifra)) {
            if (!Operater::postojiIstiMailPromjena($this->e->email, $this->e->sifra)) {
                $this->poruka = 'Unesena email adresa već postoji u bazi';
                throw new Exception();
            }
        } else {
            if (!Operater::postojiIstiMailPromjena($this->e->email)) {
                $this->poruka = 'Unesena email adresa već postoji u bazi';
                throw new Exception();
            }
        }
    }

    private function kontrolaUloga()
    {
        $s = $this->e->uloga;
        if (strlen(trim($s)) === 0) {
            $this->poruka = 'Uloga obavezna';
            throw new Exception();
        }

        if (strlen(trim($s)) > 20) {
            $this->poruka = 'Uloga ne smije imati više od 20 znakova';
            throw new Exception();
        }
    }

    public function pocetniPodaci()
    {
        $e = new stdClass();
        $e->ime = '';
        $e->prezime = '';
        $e->email = '';
        $e->uloga = '';
        return $e;
    }

    public function ajaxSearch()
    {
        $operateri = Operater::read();

        foreach ($operateri as $o) {
            if (file_exists(BP . 'public' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'operateri' .
                DIRECTORY_SEPARATOR . $o->sifra . '.png')) {
                $o->slika = App::config('url') . 'public/img/operateri/' . $o->sifra . '.png';
            } else {
                $o->slika = App::config('url') . 'public/img/nepoznato.png';
            }
        }
        $this->view->api($operateri);
    }

    public function spremiSliku()
    {
        $slika = $_POST['slika'];
        $slika = str_replace('data:image/png;base64,', '', $slika);
        $slika = str_replace(' ', '+', $slika);
        $data = base64_decode($slika);

        file_put_contents(BP . 'public' . DIRECTORY_SEPARATOR . 'img' .
            DIRECTORY_SEPARATOR . 'operateri' . DIRECTORY_SEPARATOR . $_POST['id'] .
            '.png', $data);

        $res = new stdClass();
        $res->error = false;
        $res->description = 'Uspješno spremljeno';
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($res);
    }
}
