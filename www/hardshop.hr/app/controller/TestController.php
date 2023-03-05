<?php

class TestController
{
    public function lozinka()
    {
        echo password_hash('password',PASSWORD_BCRYPT);
    }
}