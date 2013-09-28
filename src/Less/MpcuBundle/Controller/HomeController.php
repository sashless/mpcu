<?php 

namespace Less\MpcuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Less\MpcuBundle\Entity\Session;
use Less\MpcuBundle\Form;
use Less\MpcuBundle\Form\SessionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class HomeController extends Controller{
	
	public function indexAction(){
		return $this->render('LessMpcuBundle:Home:index.html.twig',array(
				'form_choose_role' => $this->setupFormChooseRole()->createView()
		));
	}
	
	private function setupFormChooseRole(){
		$builder = $this->createFormBuilder();
		$builder->add('Sender', 'submit')
		->add('Reciever', 'submit')
		->setAction($this->generateUrl('less_mpcu_home_choose'));
		return $builder->getForm();
	}
	public function chooseAction(Request $req){
		$now = new \DateTime('now');
		
    	$form = $this->setupFormChooseRole();
    	$form->handleRequest($req);
    	
    	if($form->isValid()){
    		if($form->get('Sender')->isClicked() ){
    			return $this->render('LessMpcuBundle:Home:sender.html.twig', array(
    				'ajax_settings' => urlencode(json_encode(array(
    						'ajax' => array(
    								'method' => 'GET',
    								'url' => $this->generateUrl('less_mpcu_ajax_save')
    						)
    				)
    			))));
    		}else{
    			return $this->render('LessMpcuBundle:Home:reciever.html.twig', array(
    				'ajax_settings' => urlencode(json_encode(array(
    						'ajax' => array(
    								'method' => 'GET',
    								'url' => $this->generateUrl('less_mpcu_ajax_get_next')
    						),
    						'initTime' => $now->getTimestamp()
    				)
    			))));
    		}
    	}else{
    		return $this->redirect($this->generateUrl('less_mpcu_home'));
    	}
    }
    
}