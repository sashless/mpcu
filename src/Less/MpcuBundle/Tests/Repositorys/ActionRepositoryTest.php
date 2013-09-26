<?php

namespace Less\MpcuBundle\Tests\Repositorys;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Less\MpcuBundle\LessMpcuBundle;
use Less\MpcuBundle\Entity\Action;
use Doctrine\Bundle\DoctrineBundle\Command\DropDatabaseDoctrineCommand;
use Doctrine;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand;

class ActionRepositoryTest extends WebTestCase{

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;
	
	
	public function setup(){
		static::$kernel = static::createKernel();
		static::$kernel->boot();
		
		$this->em = static::$kernel->getContainer()
			->get('doctrine')
			->getManager();

	}
	public function testAddAction(){
		$action_rep = $this->em->getRepository('Less\MpcuBundle\Entity\Action');
		
		$handling = "handling";
		$type = "type";
		$session_id = 0;
		
		$action_rep->addAction($session_id,$type,$handling);
		
		$action = array($action_rep->findOneBy(array('session_id'=> $session_id)));
		
		$this->assertCount(1,$action);
	}
	public function testGetNextActions(){
		$action_rep = $this->em->getRepository('Less\MpcuBundle\Entity\Action');
		
		$handling = "handling";
		$type = "type";
		$session_id = 0;
		$date = new \DateTime('now');
		$actions = $action_rep->getNextActions($session_id, $date);
		$this->assertCount(1, $actions );
	}
	protected function tearDown(){
		parent::tearDown();
		
		$this->em->clear();
	}
}