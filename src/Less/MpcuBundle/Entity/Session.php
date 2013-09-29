<?php

namespace Less\MpcuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Session
 *
 * @ORM\Table(name="T_Session")
 * @ORM\Entity(repositoryClass="Less\MpcuBundle\Entity\SessionRepository")
 * 
 */
class Session
{
	/**
	 * @ORM\OneToMany(targetEntity="Action", mappedBy="session")
	 * @var unknown
	 */
	protected $actions;
	
	public function __construct(){
		$this->actions = new ArrayCollection();
	}

	/**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var integer
     *
     * @ORM\Column(name="createTime", type="integer")
     */
    private $createTime;

    /**
     * Set name
     *
     * @param string $name
     * @return Session
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Session
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Add actions
     *
     * @param \Less\MpcuBundle\Entity\Action $actions
     * @return Session
     */
    public function addAction(\Less\MpcuBundle\Entity\Action $actions)
    {
        $this->actions[] = $actions;
    
        return $this;
    }

    /**
     * Remove actions
     *
     * @param \Less\MpcuBundle\Entity\Action $actions
     */
    public function removeAction(\Less\MpcuBundle\Entity\Action $actions)
    {
        $this->actions->removeElement($actions);
    }

    /**
     * Get actions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Set createTime
     *
     * @param integer $createTime
     * @return Session
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;
    
        return $this;
    }

    /**
     * Get createTime
     *
     * @return integer 
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }
}