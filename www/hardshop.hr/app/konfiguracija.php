<?php

$dev=$_SERVER['SERVER_ADDR']==='192.168.0.131' ? true : false;

if($dev){
return [
    'dev'=>$dev,
    'url'=>'http://hardshop.hr/',
    'nazivApp'=>'Hardshop',
    'baza'=>[
        'dsn'=>'mysql:host=localhost;dbname=webshop;charset=utf8mb4',
        'user'=>'root',
        'password'=>''
        ]
    ];
}else{
    return[
    'dev'=>$dev,
    'url'=>'http://polaznik37.edunova.hr/',
    'nazivApp'=>'Hardshop',
    'baza'=>[
        'dsn'=>'mysql:host=localhost;dbname=eskulap_webshop;charset=utf8mb4',
        'user'=>'eskulap_edunova',
        'password'=>'Eskulap09854'
    ]
];
}