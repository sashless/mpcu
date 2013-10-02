<?php

namespace Less\MpcuBundle\Tests\Repositorys;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Less\MpcuBundle\LessMpcuBundle;
use Less\MpcuBundle\Entity\Action;
use Doctrine\Bundle\DoctrineBundle\Command\DropDatabaseDoctrineCommand;
use Doctrine;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand;
use Less\MpcuBundle\Entity\UserSession;
use Symfony\Component\Translation\Interval;

class ActionRepositoryTest extends WebTestCase{

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;
	private $action_rep;
	
	public function setup(){
		static::$kernel = static::createKernel();
		static::$kernel->boot();
		
		$this->em = static::$kernel->getContainer()
			->get('doctrine')
			->getManager();
		
		$this->action_rep = $this->em->getRepository('Less\MpcuBundle\Entity\Action'); 
	}
	public function testAddAction(){
		$session = $this->createTestSession("test1");

		$action = new Action();
		$action->setHandling('handling');
		$action->setSession($session);
		$action->setType('asdasd');
		$this->action_rep->saveAction($action);
		
		$action = $this->action_rep->findOneBy(array('session'=> $session->getId()));
		$this->assertEquals('Less\MpcuBundle\Entity\Action',get_class($action));

	}
    public function testAddUserSession(){
        $session = new UserSession();
        $session->setUsername("test2");
        $session->setPassword("test");
        $this->em->getRepository('Less\MpcuBundle\Entity\UserSession')->saveSession($session);
        $saved_session = $this->em->getRepository('Less\MpcuBundle\Entity\UserSession')->find($session->getId());
        $this->assertEquals('test2',$saved_session->getUsername());
    }
	private function createTestSession($name){
		$session = new UserSession();
		$session->setUsername($name);
		$session->setPassword("test");
		$this->em->getRepository('Less\MpcuBundle\Entity\UserSession')->saveSession($session);

		return $session;
	}
	public function testGetNextActions(){
		$session = $this->createTestSession("test3");

		$action = new Action();
		$action->setHandling('handling');
		$action->setSession($session);
		$action->setType('asdasd');
		$this->action_rep->saveAction($action);
		
		$action = new Action();
		$action->setHandling('handling');
		$action->setSession($session);
		$action->setType('asdasd');
		$this->action_rep->saveAction($action);
		
		$date = new \DateTime('now');
		$date->sub(new \DateInterval('P10D'));
		
		$actions = $this->action_rep->getNextActions($session, $date->getTimestamp());
		$this->assertCount(2, $actions );
	}
	protected function tearDown(){
		parent::tearDown();
		$this->em->clear();
	}
}