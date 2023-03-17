<?php

interface ViewSucelje
{
    public function index();
    public function novi();
    public function promjena();
    public function brisanje();
    public function pocetniPodaci();
    public function pripremiZaView();
    public function pripremiZaBazu();
}