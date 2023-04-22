<?php

class Kupac
{
    public static function read($uvjet='',$stranica=1)
    {
        $uvjet='%' . $uvjet . '%';
        $brps=App::config('brps');
        $pocetak=($stranica * $brps) - $brps;

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
        where concat(a.ime, \' \', a.prezime)
        like :uvjet
        group by 	a.ime,
		        	a.prezime,
			        a.email,
			        a.lozinka,
			        a.adresazaracun,
			        a.adresazadostavu,
			        a.brojtelefona
        order by a.ime asc
        limit :pocetak, :brps
        
        ');

        $izraz->bindValue('pocetak',$pocetak,PDO::PARAM_INT);
        $izraz->bindValue('brps',$brps,PDO::PARAM_INT);
        $izraz->bindParam('uvjet',$uvjet);

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

    public static function ukupnoKupaca($uvjet='')
    {
        $uvjet='%' . $uvjet . '%';
        $veza=DB::getInstance();
        $izraz=$veza->prepare('
        
        select count(*)
        from
        kupac
        where concat(ime, \' \', prezime, \' \')
        like :uvjet

        ');
        $izraz->execute([
            'uvjet'=>$uvjet
        ]);
        return $izraz->fetchColumn();
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

    public static function prviKupac()
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('
        
        select sifra from kupac
        order by sifra limit 1
        
        ');
        $izraz->execute();
        $sifra=$izraz->fetchColumn();
        return $sifra;
    }

    public static function traziKupca($uvjet)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('
        
        select sifra, ime, prezime from kupac
        where concat(ime, \' \', prezime)
        like :uvjet
        
        ');
        $izraz->execute(['uvjet'=>'%' . $uvjet . '%']);
        return $izraz->fetchAll();
    }

    public static function brojKupaca()
    {   
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        select
        count(sifra) as y
        from kupac
        
        ');
        $izraz->execute();
        $rez = $izraz->fetchAll();
        return $rez;
    }

    public static function readExcelKupac()
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        select
        ime,prezime,email,adresazaracun,adresazadostavu,brojtelefona
        from kupac
        
        ');
        $izraz->execute();
        return $izraz->fetchAll();
    }
}