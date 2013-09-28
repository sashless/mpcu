<?php

namespace Less\MpcuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Less\MpcuBundle\Entity\Session;
use Less\MpcuBundle\Form;
use Less\MpcuBundle\Form\SessionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {	
    	/*
    	$form_login = $this->setupFormLogin();
    	$form_create = $this->setupFormCreate();
    	
        return $this->render('LessMpcuBundle:Default:index.html.twig', array(
        	'form_login' => $form_login->createView(),
        	'form_create' => $form_create->createView()
        ));
        */
    	return $this->redirect($this->generateUrl('less_mpcu_home'));
    }
    private function setupFormCreate(){
    	return $this->createForm(new SessionType(), new Session(), array(
    			'action' => $this->generateUrl('less_mpcu_form_create')) )
    		->add('Create','submit');
    }
    private function setupFormLogin(){
    	return $this->createForm(new SessionType(), new Session(), array(
    			'action' => $this->generateUrl('less_mpcu_form_login')) )
    		->add('Login','submit');
    }
    
    public function createAction(Request $req){
    	$session_repo = $this->getDoctrine()->getRepository('LessMpcuBundle:Session');
    	$form_create = $this->setupFormCreate();
    	$session = $form_create->getData();
    	$form_create->handleRequest($req);
    	if($form_create->isValid() && !$session_repo->exists($session) ){
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($session);
    		$em->flush();
    		return $this->redirect($this->generateUrl('less_mpcu_home'));
    	}
    	return $this->redirect($this->generateUrl('less_mpcu_index'));
    }
    
    public function loginAction(Request $req){
    	$form_login = $this->setupFormLogin();
    	$form_login->handleRequest($req);
    	if($form_login->isValid()){
    		// TODO: persist session in user context
    		$this->getDoctrine()->getManager()->persist($form_create->getData());
    		return $this->redirect($this->generateUrl('less_mpcu_home'));
    	}
    	return $this->redirect($this->generateUrl('less_mpcu_index'));
    }
}
