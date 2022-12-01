drop database if exists webshop;
create database webshop default charset utf8;
use webshop;

create table kupac(
    sifra int not null primary key auto_increment,
    ime varchar(50) not null,
    prezime varchar(50) not null,
    email varchar(50) not null,
    lozinka varchar(50) not null,
    adresazaracun varchar(100) not null,
    adresazadostavu varchar(100) not null,
    brojtelefona varchar(20)
);

create table placanje(
    sifra int not null primary key auto_increment,
    vrstaplacanja varchar(50) not null
);

create table narudzba(
    sifra int not null primary key auto_increment,
    brojnarudzbe int not null,
    datumnarudzbe datetime not null,
    datumisporuke datetime,
    datumplacanja datetime,
    kupac int not null,
    placanje int not null
);

create table proizvod(
    sifra int not null primary key auto_increment,
    naziv varchar(50) not null,
    jedinicnacijena decimal(18,2),
    opis text,
    tezina int,
    dostupnot boolean not null
);

create table detaljinarudzbe(
    sifra int not null primary key auto_increment,
    cijena decimal(18,2) not null,
    kolicina int not null,
    popust decimal(18,2),
    narudzba int not null,
    proizvod int not null
);

alter table narudzba add foreign key (kupac) references kupac(sifra);
alter table narudzba add foreign key (placanje) references placanje(sifra);

alter table detaljinarudzbe add foreign key (narudzba) references narudzba(sifra);
alter table detaljinarudzbe add foreign key (proizvod) references proizvod(sifra);