<?php

class TestController
{
    public function lozinka()
    {
        echo password_hash('passwrod',PASSWORD_BCRYPT);
    }
}