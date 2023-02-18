<?php

if($_POST['email']==='user@gmail.com' && $_POST['lozinka']==='lozinka'){
    session_start();
    $_SESSION['auth']=true;
    setcookie('email',$_POST['email']);
    header('location: zasticeno.php');
}else{
    header('location: index.php?email=' . $_POST['email']);
}