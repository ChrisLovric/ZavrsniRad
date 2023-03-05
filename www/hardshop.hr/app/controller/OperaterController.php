<?php

class OperaterController extends AdminController
{
    private $viewPutanja='privatno' . DIRECTORY_SEPARATOR . 'operateri' . DIRECTORY_SEPARATOR;

    public function index()
    {
        $operateri=Operater::read();
        foreach($operateri as $o){
            unset($o->lozinka);
        }

        $this->view->render($this->viewPutanja . 'index',[
            'podaci'=>$operateri
        ]);
    }
}