<?php

class Request
{

    public static function getRuta()
    {
        $ruta='';
        if(isset($_SERVER['REDIRECT_PATH_INFO'])){
            $ruta=$_SERVER['REDIRECT_PATH_INFO'];
        }else if(isset($_SERVER['REQUEST_URI'])){
            $ruta=$_SERVER['REQUEST_URI'];
        }

        if(strpos($ruta,'?')>0){
            $ruta=explode('?',$ruta[0]);
        }

        return $ruta;
    }
}