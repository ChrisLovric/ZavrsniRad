<?php

class Detaljinarudzbe
{
    public static function read()
    {

        $veza=DB::getInstance();
        $izraz=$veza->prepare('
        
        select
        a.sifra,
        b.brojnarudzbe,
        concat(d.ime, \' \',d.prezime) as kupac,
		c.naziv,
		a.kolicina,
		a.popust,
        a.cijena
        from detaljinarudzbe a 
        left join narudzba b on a.narudzba=b.sifra 
        left join proizvod c on a.proizvod=c.sifra
        left join kupac d on b.kupac=d.sifra
        group by 
        a.sifra,
        b.brojnarudzbe,
        concat(d.ime, \' \',d.prezime),
		c.naziv,
		a.kolicina,
		a.popust,
        a.cijena
        
        ');

        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function readOne($sifra)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('
        
        select * from detaljinarudzbe where sifra=:sifra');
        
        $izraz->execute([
            'sifra'=>$sifra
        ]);
        return $izraz->fetch();
    }

    public static function create($parametri)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('
        
        insert into detaljinarudzbe(cijena,kolicina,popust,narudzba,proizvod)
        values (:cijena,:kolicina,:popust,:narudzba,:proizvod);
        
        ');
        $izraz->execute($parametri);
    }

    public static function update($parametri)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('
        
        update detaljinarudzbe set cijena=:cijena,kolicina=:kolicina,popust=:popust,narudzba=:narudzba,
        proizvod=:proizvod where sifra=:sifra
        
        ');
        $izraz->execute($parametri);
    }

    public static function delete($sifra)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('
        
        delete from detaljinarudzbe where sifra=:sifra
        
        ');
        $izraz->execute([
            'sifra'=>$sifra
        ]);
        $izraz->execute();
    }

}