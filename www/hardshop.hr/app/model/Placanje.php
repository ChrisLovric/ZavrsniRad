<?php

class Placanje
{
    public static function read()
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        select  a.sifra,
                a.vrstaplacanja,
                count(b.sifra) as narudzba
        from placanje a
        left join narudzba b on a.sifra=b.placanje
        group by	a.vrstaplacanja
        order by    a.vrstaplacanja asc;
        
        ');

        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function readOne($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        select * from placanje where sifra=:sifra
        
        ');
        $izraz->execute([
            'sifra' => $sifra
        ]);
        return $izraz->fetch();
    }

    public static function create($parametri)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        insert into placanje(vrstaplacanja)
        values (:vrstaplacanja);
        
        ');
        $izraz->execute($parametri);
    }

    public static function update($parametri)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        update placanje set vrstaplacanja=:vrstaplacanja where sifra=:sifra
        
        ');
        $izraz->execute($parametri);
    }

    public static function delete($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        delete from placanje where sifra=:sifra
        
        ');
        $izraz->execute([
            'sifra' => $sifra
        ]);
        $izraz->execute();
    }

    public static function postojiVrstaPlacanjaUBaziNovi($s)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        select sifra from placanje where vrstaplacanja=:vrstaplacanja
        
        ');
        $izraz->execute([
            'vrstaplacanja' => $s
        ]);
        $sifra = $izraz->fetchColumn();
        return $sifra > 0;
    }

    public static function postojiVrstaPlacanjaUBaziPromjena($vrstaplacanja, $sifra = 0)
    {
        $veza = DB::getInstance();
        if ($sifra > 0) {
            $izraz = $veza->prepare('
        
            select sifra from placanje where vrstaplacanja=:vrstaplacanja 
            and sifra!=:sifra
            
            ');
        }
        $parametri = [];
        $parametri['vrstaplacanja'] = $vrstaplacanja;

        if ($sifra > 0) {
            $parametri['sifra'] = $sifra;
        }
        $izraz->execute($parametri);
        $sifra = $izraz->fetchColumn();
        return $sifra == 0;
    }

    public static function prvoPlacanje()
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        select sifra from placanje
        order by sifra limit 1
        
        ');
        $izraz->execute();
        $sifra = $izraz->fetchColumn();
        return $sifra;
    }
}
