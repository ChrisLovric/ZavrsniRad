<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

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
                'poruka'=>'Email ili lozinka nije ispravan',
                'email'=>$_POST['email']
            ]);
            return;
        }

        $_SESSION['auth']=$operater;
        header('location:' . App::config('url')) . 'nadzornaploca/index';
    }

    public function registracija()
    {
        $p = (object)$_POST;

        $p->lozinka=password_hash($p->password,PASSWORD_BCRYPT);
        unset($p->password);
        $p->sessionid=uniqid();
        $p->uloga='Operater';

   //     Log::info($p);
        if(Operater::create((array)$p)){
            $mail = new PHPMailer(false);

            try {
                //Server settings
               // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->isSMTP();                                              //Send using SMTP
                $mail->Host       = 'mail.polaznik37.edunova.hr';             //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                     //Enable SMTP authentication
                $mail->Username   = '_mainaccount@polaznik37.edunova.hr';     //SMTP username
                $mail->Password   = 'Eskulap09854';                           //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;              //Enable implicit TLS encryption
                $mail->Port       = 465;                                      //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            
                //Recipients
                $mail->setFrom('eskulap@polaznik37.edunova.hr', 'Edunova Polaznik');
                $mail->addAddress($p->email, $p->ime . ' ' . $p->prezime);     //Add a recipient
               
            
                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'Registracija na HardShop';
                $mail->Body    = 'Klik na <a href="' . App::config('url') . 'prijava/potvrda?i=' . $p->sessionid . '">Potvrdi</a>';
                $mail->AltBody = 'Copy paste' . App::config('url') . 'prijava/potvrda?i=' . $p->sessionid;
                
                echo App::config('url') . 'prijava/potvrda?i=' . $p->sessionid;
                
                $mail->send();
                
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }else{
            echo 'Registracija uspješna. Potvrdite podatke putem linka u mailu kako bi se mogli prijaviti.<br><br>';
            echo 'Klik za povratak u aplikaciju na <a href="' . App::config('url') . 'index/prijava ">prijavu.</a>';
        }
    }

    public function potvrda()
    {
        Operater::potvrdi($_GET['i']);
        echo 'Logirajte se';
    }
    
}