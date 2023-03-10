<?php

class Placanje
{
    public static function read()
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('select * from placanje order by vrstaplacanja asc');

        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function readOne($sifra)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('select * from placanje where sifra=:sifra');
        $izraz->execute([
            'sifra'=>$sifra
        ]);
        return $izraz->fetch();
    }

    public static function create($parametri)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('insert into placanje(vrstaplacanja)
        values (:vrstaplacanja);
        ');
        $izraz->execute($parametri);
    }

    public static function update($parametri)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('update placanje set vrstaplacanja=:vrstaplacanja where sifra=:sifra
        ');
        $izraz->execute($parametri);
    }

    public static function postojiVrstaPlacanjaUBazi($s)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('select sifra from placanje where vrstaplacanja=:vrstaplacanja');
        $izraz->execute([
            'vrstaplacanja'=>$s
        ]);
        $sifra=$izraz->fetchColumn();
        return $sifra>0;
    }
}