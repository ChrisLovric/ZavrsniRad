<?php

class Kupac
{
    public static function read()
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('select * from kupac order by ime asc');

        $izraz->execute();
        return $izraz->fetchAll();
    }
}