<?php

class App
{

    public static function start()
    {

        $ruta=Request::getRuta();
        $dijelovi=explode('/',substr($ruta,1));
        $controller='';
        if(!isset($dijelovi[0]) || $dijelovi[0]===''){
            $controller='IndexController';
        }else{
            $controller=ucfirst($dijelovi[0]) . 'Controller';
        }
        $metoda='';
        if(!isset($dijelovi[1]) || $dijelovi[1]===''){
            $metoda='index';
        }else{
            $metoda=$dijelovi[1];
        }
        if(!(class_exists($controller) && method_exists($controller,$metoda))){
            echo 'Ne postoji ' . $controller . '-&gt;' . $metoda;
            return;
        }

            $instanca=new $controller();
            $instanca->$metoda();
    }

    public static function config($kljuc)
    {
        $configFile=BP_APP . 'konfiguracija.php';

        if(!file_exists($configFile)){
            return 'Konfiguracijska datoteka ne postoji';
        }

        $config=require $configFile;

        if(!isset($config[$kljuc])){
            return 'Kljuc ' . $kljuc . ' nije postavljen u konfiguraciji';
        }

        return $config[$kljuc];
    }
}