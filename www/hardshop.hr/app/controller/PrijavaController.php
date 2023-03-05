<?php

class PrijavaController extends Controller
{
    public function autorizacija()
    {
        if(!isset($_POST['email']) || strlen(trim($_POST['email']))===0){
            $this->view->render('prijava',[
                'poruka'=>'Obavezno email',
                'email'=>''
            ]);
            return;
        }

        if(!isset($_POST['password']) || strlen(trim($_POST['password']))===0){
            $this->view->render('prijava',[
                'poruka'=>'Obavezno lozinka',
                'email'=>$_POST['email']
            ]);
            return;
        }

        $operater=Operater::autoriziraj($_POST['email'],$_POST['password']);

        if($operater==null){
            $this->view->render('prijava',[
                'poruka'=>'Kombinacija email i lozinka se ne podudaraju',
                'email'=>$_POST['email']
            ]);
            return;
        }

        $_SESSION['auth']=$operater;
        header('location:' . App::config('url')) . 'nadzornaploca/index';
    }
    
}