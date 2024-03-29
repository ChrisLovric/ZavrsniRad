<?php

class Operater
{
    public static function read()
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        select * from operater order by ime asc
        
        ');

        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function autoriziraj($email, $password)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        select * from operater where email=:email and sessionid is null
        
        ');

        $izraz->execute([
            'email' => $email
        ]);

        $operater = $izraz->fetch();

        if ($operater == null) {
            return null;
        }

        if (!password_verify($password, $operater->lozinka)) {
            return null;
        }

        unset($operater->lozinka);

        return $operater;
    }

    public static function readOne($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        select * from operater where sifra=:sifra
        
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
        
        insert into operater (ime,prezime,email,lozinka,uloga,sessionid)
        values (:ime,:prezime,:email,:lozinka,:uloga,:sessionid);

        ');
        $izraz->execute($parametri);
    }

    public static function createNovi($parametri)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        insert into operater (ime,prezime,email,uloga)
        values (:ime,:prezime,:email,:uloga);

        ');
        $izraz->execute($parametri);
    }

    public static function potvrdi($id)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            update operater set sessionid=null
            where sessionid=:id
        
        ');
        return $izraz->execute(['id' => $id]);
    }

    public static function update($parametri)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        update operater set ime=:ime,prezime=:prezime,email=:email,uloga=:uloga 
        where sifra=:sifra

        ');
        $izraz->execute($parametri);
    }

    public static function delete($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        delete from operater where sifra=:sifra
        
        ');
        $izraz->execute([
            'sifra' => $sifra
        ]);
        $izraz->execute();
    }

    public static function postojiIstiMailUBazi($s)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        select sifra from operater where email=:email
        
        ');
        $izraz->execute([
            'email' => $s
        ]);
        $sifra = $izraz->fetchColumn();
        return $sifra > 0;
    }

    public static function postojiIstiMailPromjena($email, $sifra = 0)
    {
        $veza = DB::getInstance();
        if ($sifra > 0) {
            $izraz = $veza->prepare('
        
            select sifra from operater where email=:email 
            and sifra!=:sifra
            
            ');
        }
        $parametri = [];
        $parametri['email'] = $email;

        if ($sifra > 0) {
            $parametri['sifra'] = $sifra;
        }
        $izraz->execute($parametri);
        $sifra = $izraz->fetchColumn();
        return $sifra == 0;
    }
}
