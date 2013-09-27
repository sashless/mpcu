<?php

namespace Less\MpcuBundle\Tests\Repositorys;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Less\MpcuBundle\LessMpcuBundle;
use Less\MpcuBundle\Entity\Action;
use Doctrine\Bundle\DoctrineBundle\Command\DropDatabaseDoctrineCommand;
use Doctrine;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand;
use Less\MpcuBundle\Entity\Session;
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
		
		$this->action_rep->addAction($session,"type","handling");
		$action = $this->action_rep->findOneBy(array('session'=> $session->getName()));
		$this->assertEquals(get_class($action),'Less\MpcuBundle\Entity\Action');

	}
	private function createTestSession($name){
		$session = new Session();
		$session->setName($name);
		$session->setPassword("test");
		$this->em->persist($session);
		$this->em->flush();
		return $session;
	}
	public function testGetNextActions(){
		$session = $this->createTestSession("test2");
		
		$this->action_rep->addAction($session,"type","handling");
		$this->action_rep->addAction($session,"type","handling");
		$this->action_rep->addAction($session,"type","handling");
		
		$date = new \DateTime('now');
		$date->add(new \DateInterval('P10D'));
		$actions = $this->action_rep->getNextActions($session, $date->getTimestamp());
		$this->assertCount(3, $actions );
	}
	protected function tearDown(){
		parent::tearDown();
		$this->em->clear();
	}
}