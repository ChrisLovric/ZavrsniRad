# c:\Games\xampp\mysql\bin\mysql -uroot --default_character_set=utf8 < C:\Users\klovr\Documents\EdunovaPP26\SQL\ZavrsniRad\www\hardshop.hr\ZavrsniRad.sql

drop database if exists webshop;
create database webshop default charset utf8;
use webshop;

create table operater(
    sifra int not null primary key auto_increment,
    ime varchar(50) not null,
    prezime varchar(50) not null,
    email varchar(50) not null,
    lozinka char(61) not null,
    uloga varchar(20) not null,
    sessionid varchar(255)
);

insert into operater (ime,prezime,email,lozinka,uloga)
values
('Hardshop','Operater','operater@hardshop.hr','$2a$12$L7EtqiUPKWRr3GhrjRcm0ezDAblEeeZIZBuc.T41xFI6vCnPACMR6','Operater'),
('Admin','Operater','admin@hardshop.hr','$2a$12$L7EtqiUPKWRr3GhrjRcm0ezDAblEeeZIZBuc.T41xFI6vCnPACMR6','Admin');

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
    naziv varchar(100) not null,
    proizvodjac varchar(50) not null,
    jedinicnacijena decimal(18,2) not null,
    opis text,
    dostupnost boolean not null
);

create table detaljinarudzbe(
    sifra int not null primary key auto_increment,
    narudzba int not null,
    proizvod int not null
);

alter table narudzba add foreign key (kupac) references kupac(sifra);
alter table narudzba add foreign key (placanje) references placanje(sifra);

alter table detaljinarudzbe add foreign key (narudzba) references narudzba(sifra);
alter table detaljinarudzbe add foreign key (proizvod) references proizvod(sifra);


insert into kupac (ime,prezime,email,lozinka,adresazaracun,adresazadostavu,brojtelefona)
values
('Renato','Jukić','rjukic@gmail.com','QuRMf4#rB5buCt#I','Fiorello la Guardia 14, 51000 Rijeka','Fiorello la Guardia 14, 51000 Rijeka','+385 51 211835'),
('Franjo','Balen','fbalen@gmail.com','f!LQHs+h5UT6af+B','Industrijska 28, 34000 Požega','Industrijska 28, 34000 Požega','034 27 34 65');


insert into placanje (vrstaplacanja)
values
('Gotovina'),
('Kartica');


insert into proizvod (naziv,proizvodjac,jedinicnacijena,opis,dostupnost)
values
('Grafička kartica GeForce RTX 3060 Ghost LHR, 12GB GDDR6','Gainward',464.99,
'Sučelje: PCI-E
Radni takt GPU [MHz]: 1777
Vrsta memorije: GDDR6
Radna memorija (RAM): 12GB
Memorijsko sučelje [bit]: 192
D-SUB: n/a
HDMI: 1
DisplayPort: 3
DVI: n/a
Proizvođač čipa: nVidia
LHR',
true),
('Grafička kartica Radeon RX6800XT Gaming OC, 16GB GDDR6','Gigabyte',1499.99,
'Sučelje: PCI-E
Radni takt GPU [MHz]: 2285
Vrsta memorije: GDDR6
Radna memorija (RAM): 16 GB
Memorijsko sučelje [bit]: 256
D-SUB: n/a
HDMI: 2
DisplayPort: 2
USB-C: n/a
DVI: n/a
Proizvođač čipa: AMD',
true);


insert into narudzba (brojnarudzbe,datumnarudzbe,datumisporuke,datumplacanja,kupac,placanje)
values
(1,'2022-11-30 15:24:36',null,null,1,1),
(2,'2022-12-05 08:11:52','2022-12-08 09:45:12','2022-12-05 08:13:47',2,2);


insert into detaljinarudzbe (narudzba,proizvod)
values
(1,1),
(2,2);