<?php
App::uses('AppController', 'Controller');
class TestController extends AppController
{

    public function index()
    {
        $this->set('test');
    }

}
