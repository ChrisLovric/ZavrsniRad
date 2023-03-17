<?php

abstract class Controller
{
    protected $view;

    public function __construct()
    {
        $this->view=new View();
    }

    protected function provjeraIntParametra($parametar)
    {
        if(strlen(trim($parametar))===0){
            header('location: ' . App::config('url') . 'index/odjava');
            return;
        }

    $sifra=(int)$parametar;
    if($sifra===0){
        header('location: ' . App::config('url') . 'index/odjava');
        return;
    }
}
}