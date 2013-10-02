<?php

namespace Less\MpcuBundle\Controller;

use Less\MpcuBundle\Form\Type\RegistrationType;

use Less\MpcuBundle\Form\Model\Registration;

use Symfony\Component\Security\Core\SecurityContext;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Less\MpcuBundle\Entity\UserSession;
use Less\MpcuBundle\Form;
use Less\MpcuBundle\Form\SessionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
	
    public function indexAction(Request $req)
    {
    	$session = $req->getSession();
    	
    	if($req->attributes->has(SecurityContext::AUTHENTICATION_ERROR)){
    		$error = $req->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
    	}else{
    		$error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
    		$session->remove(SecurityContext::AUTHENTICATION_ERROR);
    	}
        //TODO: add plausible error messages

        return $this->render('LessMpcuBundle:Default:index.html.twig', array(
        	'last_username' => $session->get(SecurityContext::LAST_USERNAME),
        	'error' => ''
        ));
        
    }
    public function registerAction(){
    	$registration = new Registration();
    	$form = $this->createForm(new RegistrationType(), $registration,
    			array('action' => $this->generateUrl('less_mpcu_register_create')));
    	return $this->render('LessMpcuBundle:Default:register.html.twig',
    			array('register_form' => $form->createView()));
    	
    }
    
    public function createAction(Request $req){
        $repo = $this->getDoctrine()->getRepository('LessMpcuBundle:UserSession');
        $form = $this->createForm(new RegistrationType(), new Registration());
    	$form->handleRequest($req);
        $user = $form->getData()->getUser();
        $exists = $repo->exists($user->getUsername());
        $isValid = $form->isValid();

    	if($isValid && !$exists){

    		$factory = $this->get('security.encoder_factory');

    		$encoder = $factory->getEncoder($user);
    		$password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
    		$user->setPassword($password);

    		$repo->saveSession($user);
    		
    		return $this->redirect($this->generateUrl('less_mpcu_home'));
    	}

        $error = "";

        if(!$isValid){
            $error .= "The form is not valid.";
        }
        if($exists){
            $error .= "The username you picked is already in use.";
        }

    	// TODO: add fail message
    	return $this->render('LessMpcuBundle:Default:register.html.twig',
    			array('register_form' => $form->createView(), 'error' => $error));
    }
}
