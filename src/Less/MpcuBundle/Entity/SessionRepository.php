<?php

namespace Less\MpcuBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * SessionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SessionRepository extends EntityRepository
{
	/**
	 * @ORM\ManyToOne(targetEntity="Session", inversedBy="Action")
	 * @ORM\JoinColumn(name="session_id", referencedColumnName="id" 
	 * @var unknown
	 */
	protected $actions;
	
	public function __construct(){
		$this->actions = new ArrayCollection();
	}
	public function createSession($name, $password){
		$em = $this->getEntityManager();
		$session = new Session();
		$session->setName($name);
		$session->setPassword($password);
		$em->persist($session);
	}
}