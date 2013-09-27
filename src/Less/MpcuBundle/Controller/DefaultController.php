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
    	
    	$form_login = $this->setupFormLogin();
    	$form_create = $this->setupFormCreate();
    	
        return $this->render('LessMpcuBundle:Default:index.html.twig', array(
        	'form_login' => $form_login->createView(),
        	'form_create' => $form_create->createView()
        ));
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
    private function setupFormChooseRole(){
    	$builder = $this->createFormBuilder();
    	$builder->add('Sender', 'submit')
    		->add('Reciever', 'submit')
    		->setAction($this->generateUrl('less_mpcu_choose_role'));
    	return $builder->getForm();
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
    		return $this->render('LessMpcuBundle:Default:home.html.twig',array(
    			'form_choose_role' => $this->setupFormChooseRole()->createView()
    		));
    	}
    	return $this->redirect($this->generateUrl('less_mpcu_homepage'));
    }
    public function chooseAction(Request $req){
    	$form = $this->setupFormChooseRole();
    	$form->handleRequest($req);
    	if($form->isValid()){
    		if($form->get('Sender')->isClicked() ){
    			return $this->render('LessMpcuBundle:Default:sender.html.twig');
    		}else{
    			return $this->render('LessMpcuBundle:Default:reciever.html.twig');
    		}
    	}
    }
    public function loginAction(Request $req){
    	$form_login = $this->setupFormLogin();
    	$form_login->handleRequest($req);
    	if($form_login->isValid()){
    		$this->getDoctrine()->getManager()->persist($form_create->getData());
    		return $this->render('login successful');
    	}
    	return $this->redirect($this->generateUrl('less_mpcu_homepage'));
    }
}
