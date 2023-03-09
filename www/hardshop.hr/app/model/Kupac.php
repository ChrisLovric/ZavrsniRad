<?php

class Kupac
{
    public static function read()
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('select * from kupac order by ime asc');

        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function readOne($sifra)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('select * from kupac where sifra=:sifra');
        $izraz->execute([
            'sifra'=>$sifra
        ]);
        return $izraz->fetch();
    }

    public static function create($parametri)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('insert into kupac(ime,prezime,email,adresazaracun,adresazadostavu,brojtelefona)
        values (:ime,:prezime,:email,:adresazaracun,:adresazadostavu,:brojtelefona);
        ');
        $izraz->execute($parametri);
    }

    public static function update($parametri)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('update kupac set ime=:ime,prezime=:prezime,email=:email,adresazaracun=:adresazaracun,
        adresazadostavu=:adresazadostavu,brojtelefona=:brojtelefona where sifra=:sifra
        ');
        $izraz->execute($parametri);
    }

    public static function postojiIstiMailUBazi($s)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('select sifra from kupac where email=:email');
        $izraz->execute([
            'email'=>$s
        ]);
        $sifra=$izraz->fetchColumn();
        return $sifra>0;
    }
}