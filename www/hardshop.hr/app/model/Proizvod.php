<?php

class Proizvod
{
    public static function read($uvjet = '', $stranica = 1)
    {
        $uvjet = '%' . $uvjet . '%';
        $brps = App::config('brps');
        $pocetak = ($stranica * $brps) - $brps;

        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        select  a.sifra,
		        a.naziv,
		        a.proizvodjac,
		        a.jedinicnacijena,
		        a.opis,
		        a.dostupnost,
		        count(b.sifra) as detaljinarudzbe
        from proizvod a
        left join detaljinarudzbe b on a.sifra=b.proizvod
        where concat(a.naziv, \' \', a.proizvodjac, \' \', ifnull(a.jedinicnacijena,\'\'))
        like :uvjet
        group by 	a.naziv,
			        a.proizvodjac,
			        a.jedinicnacijena,
			        a.opis,
			        a.dostupnost
        order by a.naziv asc
        limit :pocetak, :brps
        
        ');

        $izraz->bindValue('pocetak', $pocetak, PDO::PARAM_INT);
        $izraz->bindValue('brps', $brps, PDO::PARAM_INT);
        $izraz->bindParam('uvjet', $uvjet);

        $izraz->execute();

        return $izraz->fetchAll();
    }

    public static function readOne($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        select * from proizvod where sifra=:sifra
        
        ');
        $izraz->execute([
            'sifra' => $sifra
        ]);
        return $izraz->fetch();
    }

    public static function ukupnoProizvoda($uvjet = '')
    {
        $uvjet = '%' . $uvjet . '%';
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        select count(*)
        from
        proizvod
        where concat(naziv, \' \', proizvodjac, \' \', ifnull(jedinicnacijena,\'\'))
        like :uvjet

        ');
        $izraz->execute([
            'uvjet' => $uvjet
        ]);
        return $izraz->fetchColumn();
    }

    public static function create($parametri)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        insert into proizvod(naziv,proizvodjac,jedinicnacijena,opis,dostupnost)
        values (:naziv,:proizvodjac,:jedinicnacijena,:opis,:dostupnost);

        ');
        $izraz->execute($parametri);
    }

    public static function update($parametri)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        update proizvod set naziv=:naziv,proizvodjac=:proizvodjac,
        jedinicnacijena=:jedinicnacijena,opis=:opis,dostupnost=:dostupnost where sifra=:sifra
        ');
        $izraz->execute($parametri);
    }

    public static function delete($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        delete from proizvod where sifra=:sifra
        
        ');
        $izraz->execute([
            'sifra' => $sifra
        ]);
        $izraz->execute();
    }

    public static function postojiIstiProizvodNovi($s)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        select sifra from proizvod where naziv=:naziv
        
        ');
        $izraz->execute([
            'naziv' => $s
        ]);
        $sifra = $izraz->fetchColumn();
        return $sifra > 0;
    }

    public static function postojiIstiProizvodPromjena($naziv, $sifra = 0)
    {
        $veza = DB::getInstance();
        if ($sifra > 0) {
            $izraz = $veza->prepare('
        
            select sifra from proizvod where naziv=:naziv 
            and sifra!=:sifra
            
            ');
        }
        $parametri = [];
        $parametri['naziv'] = $naziv;

        if ($sifra > 0) {
            $parametri['sifra'] = $sifra;
        }
        $izraz->execute($parametri);
        $sifra = $izraz->fetchColumn();
        return $sifra == 0;
    }

    public static function prviProizvod()
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        select sifra from proizvod
        order by sifra limit 1
        
        ');
        $izraz->execute();
        $sifra = $izraz->fetchColumn();
        return $sifra;
    }

    public static function traziProizvod($uvjet)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        select sifra, naziv from proizvod
        where naziv
        like :uvjet
        
        ');
        $izraz->execute(['uvjet' => '%' . $uvjet . '%']);
        return $izraz->fetchAll();
    }

    public static function brojProizvoda()
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        select
        count(sifra) as y
        from proizvod
        
        ');
        $izraz->execute();
        $rez = $izraz->fetchAll();
        return $rez;
    }

    public static function readExcelProizvod()
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        select
        naziv,proizvodjac,jedinicnacijena,opis,dostupnost
        from proizvod
        
        ');
        $izraz->execute();
        return $izraz->fetchAll();
    }
}
