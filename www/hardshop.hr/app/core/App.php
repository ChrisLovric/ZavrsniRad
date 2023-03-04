<?php

class App
{

    public static function start(){

        $ruta=Request::getRuta();

        Log::info($ruta);

        $dijelovi=explode('/',substr($ruta,1));

        Log::info($dijelovi);

        $controller='';
        if(!isset($dijelovi[0]) || $dijelovi[0]===''){
            $controller='IndexController';
        }else{
            $controller=ucfirst($dijelovi[0]) . 'Controller';
        }

        Log::info($controller);

        $metoda='';
        if(!isset($dijelovi[1]) || $dijelovi[1]===''){
            $metoda='index';
        }else{
            $metoda=$dijelovi[1];
        }

        Log::info($metoda);

        if(!class_exists($controller) && method_exists($controller,$metoda)){
            echo 'Ne postoji ' . $controller . '-&gt;' . $metoda;
            return;
        }

            $instanca=new $controller();
            $instanca->$metoda();

    }
}