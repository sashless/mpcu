<?php

namespace Less\MpcuBundle\Controller;

use Less\MpcuBundle\Entity\Action;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Less\MpcuBundle\Entity\Session;
use Symfony\Component\HttpFoundation\Response;
use Less\MpcuBundle\LessMpcuBundle;
use Symfony\Component\HttpFoundation\ParameterBag;

class AjaxController extends Controller
{	
	private function setupDummySession(){
		$session = new Session();
		$session->setName('name');
		$session->setPassword('test');
		
		$session_repo = $this->getDoctrine()->getRepository('Less\MpcuBundle\Entity\Session');
		if( !$session_repo->exists($session) ){
			$session_repo->saveSession($session);
		}else{
			return $session_repo->find('name');
		}
		return $session;
	}
    public function saveAction(Request $request)
    {	
    	$now = new \DateTime('now');
    	$action = new Action();
    	$action->setTime($now->getTimestamp());
    	$action->setHandling($request->query->get('handling'));
    	$action->setType($request->query->get('type'));
    	$action->setSession($this->setupDummySession());
    	
    	$this->getDoctrine()->getRepository('Less\MpcuBundle\Entity\Action')->saveAction($action);
    	
    	return new JsonResponse(array('data' => '', 'status' => 'saved', 'message' => 'saved'));
    }

    public function getNextAction(Request $request)
    {
    	$actions = $this->getDoctrine()->getRepository('Less\MpcuBundle\Entity\Action')
    		->getNextActions($this->setupDummySession(), $request->query->get('time'));
    	
    	$status = 'ok';
    	$message = 'ok';
    	
    	if($actions === null ){
    		$status = 'error';
    		$message = 'Failed to get Actions';
    	}
    	
    	$serializer = $this->container->get('jms_serializer');
    	$serialized = $serializer->serialize(array(
    			'actions' => $actions,
    			'status' => $status,
    			'message' => $message 
    	), 'json');
    	
    	return new Response($serialized);
    }

}
