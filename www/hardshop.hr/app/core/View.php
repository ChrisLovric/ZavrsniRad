<?php

class View
{
    private $predlozak;
    private $CSSdependency = null;
    private $JSdependency = null;

    public function __construct($predlozak = 'predlozak')
    {
        $this->predlozak = $predlozak;
    }

    public function render($phtmlStranica, $parametri = [])
    {
        $cssDatoteka = BP . 'public' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR .
            $phtmlStranica . '.css';
        if (file_exists($cssDatoteka)) {
            $css = str_replace('\\', '/', $phtmlStranica) . '.css';
        }

        $jsDatoteka = BP . 'public' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR .
            $phtmlStranica . '.js';
        if (file_exists($jsDatoteka)) {
            $js = str_replace('\\', '/', $phtmlStranica) . '.js';
        }

        if ($this->CSSdependency != null) {
            $cssdependency = $this->CSSdependency;
        }

        if ($this->JSdependency != null) {
            $jsdependency = $this->JSdependency;
        }

        $viewDatoteka = BP_APP . 'view' . DIRECTORY_SEPARATOR . $phtmlStranica . '.phtml';
        ob_start();
        extract($parametri);
        if (file_exists($viewDatoteka)) {
            include_once $viewDatoteka;
        } else {
            include_once BP_APP . 'view' . DIRECTORY_SEPARATOR . 'errorViewDatoteka.phtml';
        }
        $sadrzaj = ob_get_clean();
        include_once BP_APP . 'view' . DIRECTORY_SEPARATOR . $this->predlozak . '.phtml';
    }

    public function api($parametri)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($parametri, JSON_NUMERIC_CHECK);
    }

    public function setCSSdependency($dep)
    {
        $this->CSSdependency = $dep;
    }

    public function setJSdependency($dep)
    {
        $this->JSdependency = $dep;
    }
}
