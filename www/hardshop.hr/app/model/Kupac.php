<?php

class Kupac
{
    public static function read()
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('
        
        select	a.sifra,
		        a.ime,
		        a.prezime,
		        a.email,
		        a.lozinka,
		        a.adresazaracun,
		        a.adresazadostavu,
		        a.brojtelefona,
		        count(b.sifra) as narudzba
        from kupac a
        left join narudzba b on a.sifra=b.kupac
        group by 	a.ime,
		        	a.prezime,
			        a.email,
			        a.lozinka,
			        a.adresazaracun,
			        a.adresazadostavu,
			        a.brojtelefona
        order by a.ime asc;
        
        ');

        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function readOne($sifra)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('
        
        select * from kupac where sifra=:sifra');
        
        $izraz->execute([
            'sifra'=>$sifra
        ]);
        return $izraz->fetch();
    }

    public static function create($parametri)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('
        
        insert into kupac(ime,prezime,email,adresazaracun,adresazadostavu,brojtelefona)
        values (:ime,:prezime,:email,:adresazaracun,:adresazadostavu,:brojtelefona);
        
        ');
        $izraz->execute($parametri);
    }

    public static function update($parametri)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('
        
        update kupac set ime=:ime,prezime=:prezime,email=:email,adresazaracun=:adresazaracun,
        adresazadostavu=:adresazadostavu,brojtelefona=:brojtelefona where sifra=:sifra
        
        ');
        $izraz->execute($parametri);
    }

    public static function delete($sifra)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('
        
        delete from kupac where sifra=:sifra
        
        ');
        $izraz->execute([
            'sifra'=>$sifra
        ]);
        $izraz->execute();
    }

    public static function postojiIstiMail($email,$sifra=0)
    {
        $veza=DB::getInstance();
        if($sifra>0){
            $izraz=$veza->prepare('
        
            select sifra from kupac where email=:email 
            and sifra!=:sifra
            
            ');
        }
        $parametri=[];
        $parametri['email']=$email;

        if($sifra>0){
            $parametri['sifra']=$sifra;
        }
        $izraz->execute($parametri);
        $sifra=$izraz->fetchColumn();
        return $sifra==0;
    }

    public static function postojiIstiBrojTelefona($brojtelefona,$sifra=0)
    {
        $veza=DB::getInstance();
        if($sifra>0){
            $izraz=$veza->prepare('
        
            select sifra from kupac where brojtelefona=:brojtelefona 
            and sifra!=:sifra
            
            ');
        }
        $parametri=[];
        $parametri['brojtelefona']=$brojtelefona;

        if($sifra>0){
            $parametri['sifra']=$sifra;
        }
        $izraz->execute($parametri);
        $sifra=$izraz->fetchColumn();
        return $sifra==0;
    }

    public static function postojiIstiEmailUBazi($s)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('
        
        select sifra from kupac where email=:email
        
        ');
        $izraz->execute([
            'email'=>$s
        ]);
        $sifra=$izraz->fetchColumn();
        return $sifra>0;
    }

    public static function postojiIstiBrojUBazi($s)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('
        
        select sifra from kupac where brojtelefona=:brojtelefona
        
        ');
        $izraz->execute([
            'brojtelefona'=>$s
        ]);
        $sifra=$izraz->fetchColumn();
        return $sifra>0;
    }
}