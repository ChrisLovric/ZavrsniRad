<?php

$dev=$_SERVER['SERVER_ADDR']==='192.168.0.131' ? true : false;

if($dev){
return [
    'dev'=>$dev,
    'formatBroja'=>'###,##0.00',
    'url'=>'http://hardshop.hr/',
    'nazivApp'=>'Hardshop',
    'brps'=>12,
    'baza'=>[
        'dsn'=>'mysql:host=localhost;dbname=webshop;charset=utf8mb4',
        'user'=>'root',
        'password'=>''
        ]
    ];
}else{
    return[
    'dev'=>$dev,
    'formatBroja'=>'###,##0.00',
    'url'=>'http://polaznik37.edunova.hr/',
    'nazivApp'=>'Hardshop',
    'brps'=>12,
    'baza'=>[
        'dsn'=>'mysql:host=localhost;dbname=eskulap_webshop;charset=utf8mb4',
        'user'=>'eskulap_edunova',
        'password'=>'Eskulap09854'
    ]
];
}