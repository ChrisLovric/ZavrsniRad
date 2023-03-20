<?php

class TestController
{
    public function lozinka()
    {
        echo password_hash('password',PASSWORD_BCRYPT);
    }

    public function email()
    {
        echo Util::is_email('fbalen@gmail.com') ? 'OK' : 'NE';
    }
}