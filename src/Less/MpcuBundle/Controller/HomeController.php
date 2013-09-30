<?php

namespace Less\MpcuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Less\MpcuBundle\Entity\UserSession;
use Less\MpcuBundle\Form;
use Less\MpcuBundle\Form\SessionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller {
	public function indexAction() {
		return $this->render ( 'LessMpcuBundle:Home:index.html.twig', array(
				'form_sender' => $this->setupChooseSenderForm()->createView(),
				'form_reciever' => $this->setupChooseRecieverForm()->createView()));
	}
	private function setupChooseSenderForm(){
		$builder_sender = $this->createFormBuilder ();
		$builder_sender->add ( 'Sender', 'submit' )->setAction($this->generateUrl('less_mpcu_home_sender'));
		
		return $builder_sender->getForm();
	}
	private function setupChooseRecieverForm(){
		$builder_reciever = $this->createFormBuilder ();
		$builder_reciever->add ( 'Reciever', 'submit' )->setAction($this->generateUrl('less_mpcu_home_reciever'));
		
		return $builder_reciever->getForm();
	}
	
	public function senderAction(Request $req){
		$form = $this->setupChooseSenderForm();
		$form->handleRequest($req);
	
		if ($form->isValid ()) {
				return $this->render ( 'LessMpcuBundle:Home:sender.html.twig', array (
						'ajax_settings' => urlencode ( json_encode ( array (
								'ajax' => array (
										'method' => 'GET',
										'url' => $this->generateUrl ( 'less_mpcu_ajax_save' )
								)
				)))));
		}
	}
	public function recieverAction(Request $req){
		$form = $this->setupChooseRecieverForm();
		$form->handleRequest($req);
		$now = new \DateTime ( 'now' );
		if ($form->isValid ()) {
			return $this->render ( 'LessMpcuBundle:Home:reciever.html.twig', array (
					'ajax_settings' => urlencode ( json_encode ( array (
							'ajax' => array (
									'method' => 'GET',
									'url' => $this->generateUrl ( 'less_mpcu_ajax_get_next' )
							),
							'initTime' => $now->getTimestamp()
					)))));
		}			
	}
}