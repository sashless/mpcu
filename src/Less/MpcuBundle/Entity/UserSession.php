<?php

namespace Less\MpcuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * /UserSession
 *
 * @ORM\Table(name="T_UserSession")
 * @ORM\Entity(repositoryClass="Less\MpcuBundle\Entity\UserSessionRepository")
 * 
 */
class UserSession implements UserInterface, \Serializable
{
	/**
	 * @ORM\OneToMany(targetEntity="Action", mappedBy="session")
	 * @var unknown
	 */
	protected $actions;
	
	/**
	 *@ORM\Column(type="string", length=32)
	 */
	private $salt;
	public function getSalt(){
		return $this->salt;
	}
	public function getRoles(){
		return array('ROLE_USER');
	}
	
	public function serialize(){
		return serialize(array(
				$this->id
		));
	}
	
	public function unserialize($serialized){
		list($this->id) = unserialize($serialized);
	}
	public function __toString(){
		return $this->getUsername();
	}
	
	public function __construct(){
		$this->actions = new ArrayCollection();
		$this->salt = md5(uniqid(null,true));
	}

	/**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	private $id;

	/**
	 * 
	 * @var string
	 * @ORM\Column(type="string", length=255, unique=true)
	 */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
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
    public function setUsername($name)
    {
        $this->username = $name;
    
        return $this;
    }

    public function eraseCredentials(){
    	
    }
    /**
     * Get name
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
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

    /**
     * Set salt
     *
     * @param string $salt
     * @return UserSession
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}