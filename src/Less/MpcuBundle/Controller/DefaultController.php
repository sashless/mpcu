<?php

namespace Less\MpcuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {	
        return $this->render('LessMpcuBundle:Default:index.html.twig');
    }
}
