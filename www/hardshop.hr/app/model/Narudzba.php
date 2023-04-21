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
        e.naziv,
        e.jedinicnacijena,
        b.vrstaplacanja,
        a.datumnarudzbe,
        a.datumisporuke,
        a.datumplacanja
        from narudzba a 
        left join placanje b on a.placanje=b.sifra 
        left join kupac c on a.kupac=c.sifra
        left join detaljinarudzbe d on d.narudzba=a.sifra
        left join proizvod e on d.proizvod=e.sifra
        group by 
        a.sifra,
        a.brojnarudzbe,
        concat(c.ime, \' \', c.prezime),
        e.naziv,
        e.jedinicnacijena,
        b.vrstaplacanja,
        a.datumnarudzbe,
        a.datumisporuke,
        a.datumplacanja
        
        ');

        $izraz->execute();
        $rez=$izraz->fetchAll();
        foreach($rez as $r){
            $r->proizvodi=Narudzba::detaljiNarudzbe($r->sifra);
        }
        return $rez;
    }

    public static function detaljiNarudzbe($sifra)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('
        
        select 
        a.sifra, a.naziv
        from proizvod a
        inner join detaljinarudzbe b on a.sifra=b.proizvod
        where b.narudzba=:sifra
        
        ');
        $izraz->execute([
            'sifra'=>$sifra
        ]);
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

        $izraz=$veza->prepare('
        
        select 
        a.sifra, a.naziv
        from proizvod a
        inner join detaljinarudzbe b on a.sifra=b.proizvod
        where b.narudzba=:sifra
        
        ');
        $izraz->execute([
            'sifra'=>$sifra
        ]);

        $narudzba->proizvodi=$izraz->fetchAll();

        return $narudzba;
        
    }

    public static function create($parametri)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('
        
        insert into narudzba (brojnarudzbe,datumnarudzbe,datumisporuke,datumplacanja,kupac,placanje)
        values (:brojnarudzbe,:datumnarudzbe,:datumisporuke,:datumplacanja,:kupac,:placanje);
        
        ');
        $izraz->execute($parametri);
        return $veza->lastInsertId();
    }

    public static function update($parametri)
    {
        unset($parametri['proizvodi']);
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
    }

    public static function dodajProizvodNarudzba($narudzba, $proizvod)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
           insert into detaljinarudzbe (narudzba,proizvod)
           values (:narudzba,:proizvod)
        
        ');
        $izraz->execute([
            'narudzba'=>$narudzba,
            'proizvod'=>$proizvod
        ]);
    }

    public static function postojiProizvodNarudzba($narudzba, $proizvod)
    {   
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
           select count(*) as ukupno 
           from detaljinarudzbe where narudzba=:narudzba 
           and proizvod=:proizvod
        
        ');
        $izraz->execute([
            'narudzba'=>$narudzba,
            'proizvod'=>$proizvod
        ]);
        $rez = (int)$izraz->fetchColumn();
        return $rez>0;
    }

    public static function obrisiProizvodNarudzba($narudzba, $proizvod)
    {   
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
           delete from detaljinarudzbe where narudzba=:narudzba
           and proizvod=:proizvod
        
        ');
        $izraz->execute([
            'narudzba'=>$narudzba,
            'proizvod'=>$proizvod
        ]);
    }

    public static function brojKupacaSaNarudzbom()
    {   
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        select
        concat(a.ime, \' \', a.prezime) as name, count(b.kupac) as y
        from kupac a
        inner join narudzba b on a.sifra=b.kupac
        group by a.ime order by y desc
        
        ');
        $izraz->execute();
        $rez = $izraz->fetchAll();
        return $rez;
    }

    public static function brojNarudzbi()
    {   
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        select
        count(brojnarudzbe) as y
        from narudzba
        
        ');
        $izraz->execute();
        $rez = $izraz->fetchAll();
        return $rez;
    }

    public static function postojiIstiBrojNarudzbe($brojnarudzbe,$sifra=0)
    {
        $veza=DB::getInstance();
        if($sifra>0){
            $izraz=$veza->prepare('
        
            select sifra from narudzba where brojnarudzbe=:brojnarudzbe 
            and sifra!=:sifra
            
            ');
        }
        $parametri=[];
        $parametri['brojnarudzbe']=$brojnarudzbe;

        if($sifra>0){
            $parametri['sifra']=$sifra;
        }
        $izraz->execute($parametri);
        $sifra=$izraz->fetchColumn();
        return $sifra==0;
    }

    public static function readExcel()
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        select
        a.brojnarudzbe,
        concat(c.ime, \' \', c.prezime) as kupac,
        e.naziv,
        e.jedinicnacijena as cijena,
        b.vrstaplacanja,
        a.datumnarudzbe
        from narudzba a 
        inner join placanje b on a.placanje=b.sifra 
        inner join kupac c on a.kupac=c.sifra
        inner join detaljinarudzbe d on d.narudzba=a.sifra
        inner join proizvod e on d.proizvod=e.sifra
        
        ');
        $izraz->execute();
        return $izraz->fetchAll();
    }
}