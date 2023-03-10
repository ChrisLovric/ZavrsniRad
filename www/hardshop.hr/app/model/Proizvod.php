<?php

class Proizvod
{
    public static function read()
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('select * from proizvod order by naziv asc');

        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function readOne($sifra)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('select * from proizvod where sifra=:sifra');
        $izraz->execute([
            'sifra'=>$sifra
        ]);
        return $izraz->fetch();
    }

    public static function create($parametri)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('insert into proizvod(naziv,proizvodjac,jedinicnacijena,opis,dostupnost)
        values (:naziv,:proizvodjac,:jedinicnacijena,:opis,:dostupnost);
        ');
        $izraz->execute($parametri);
    }

    public static function update($parametri)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('update proizvod set naziv=:naziv,proizvodjac=:proizvodjac,
        jedinicnacijena=:jedinicnacijena,opis=:opis,dostupnost=:dostupnost where sifra=:sifra
        ');
        $izraz->execute($parametri);
    }

    public static function postojiIstiProizvodUBazi($s)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('select sifra from proizvod where naziv=:naziv');
        $izraz->execute([
            'naziv'=>$s
        ]);
        $sifra=$izraz->fetchColumn();
        return $sifra>0;
    }
}