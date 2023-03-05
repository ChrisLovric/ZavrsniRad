<?php

class Korisnik
{
    public static function autoriziraj($email,$password)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('select * from korisnik where email=:email');

        $izraz->execute([
            'email'=>$email
        ]);

        $korisnik=$izraz->fetch();

        if($korisnik==null){
            return null;
        }

        if(!password_verify($password,$korisnik->lozinka)){
            return null;
        }

        unset($korisnik->lozinka);

        return $korisnik;
    }
}