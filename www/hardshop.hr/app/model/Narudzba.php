<?php

class Narudzba
{
    public static function read()
    {

        $veza=DB::getInstance();
        $izraz=$veza->prepare('
        
        select
        a.sifra,
        a.brojnarudzbe,
        concat(c.ime, \' \', c.prezime) as kupac,
        b.vrstaplacanja,
        a.datumnarudzbe,
        a.datumisporuke,
        a.datumplacanja
        from narudzba a 
        left join placanje b on a.placanje=b.sifra 
        left join kupac c on a.kupac=c.sifra 
        group by 
        a.sifra,
        a.brojnarudzbe,
        concat(c.ime, \' \', c.prezime),
        b.vrstaplacanja,
        a.datumnarudzbe,
        a.datumisporuke,
        a.datumplacanja
        
        ');

        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function readOne($sifra)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('
        
        select * from narudzba where sifra=:sifra
        
        ');

        $izraz->execute([
            'sifra'=>$sifra
        ]);
        $narudzba=$izraz->fetch();
        return $narudzba;
        
    }

    public static function create($parametri)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('
        
        insert into narudzba(brojnarudzbe,datumnarudzbe,datumisporuke,datumplacanja,kupac,placanje)
        values (:brojnarudzbe,:datumnarudzbe,:datumisporuke,:datumplacanja,:kupac,:placanje);
        
        ');
        $izraz->execute($parametri);
        return $veza->lastInsertId();
    }

    public static function update($parametri)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('
        
        update narudzba set brojnarudzbe=:brojnarudzbe,datumnarudzbe=:datumnarudzbe,datumisporuke=:datumisporuke,datumplacanja=:datumplacanja,
        kupac=:kupac,placanje=:placanje where sifra=:sifra
        
        ');
        $izraz->execute($parametri);
    }

    public static function delete($sifra)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('
        
        delete from narudzba where sifra=:sifra
        
        ');
        $izraz->execute([
            'sifra'=>$sifra
        ]);
        $izraz->execute();
    }

}