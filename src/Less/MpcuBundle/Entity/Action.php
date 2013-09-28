<?php

namespace Less\MpcuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;

/**
 * Action
 *
 * @ORM\Table(name="T_Action")
 * @ORM\Entity(repositoryClass="Less\MpcuBundle\Entity\ActionRepository")
 */
class Action
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="integer")
     */
    private $time;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="handling", type="string", length=255)
     */
    private $handling;

    /**
     * 
     * 
     * @ORM\ManyToOne(targetEntity="Session", inversedBy="actions")
     * @ORM\JoinColumn(name="session_id", referencedColumnName="name")
     * @Exclude
     */
	protected $session;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set time
     *
     * @param integer $time
     * @return Action
     */
    public function setTime($time)
    {
        $this->time = $time;
    
        return $this;
    }

    /**
     * Get time
     *
     * @return integer
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Action
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set handling
     *
     * @param string $handling
     * @return Action
     */
    public function setHandling($handling)
    {
        $this->handling = $handling;
    
        return $this;
    }

    /**
     * Get handling
     *
     * @return string 
     */
    public function getHandling()
    {
        return $this->handling;
    }
    

    /**
     * Set session
     *
     * @param \Less\MpcuBundle\Entity\Session $session
     * @return Action
     */
    public function setSession(\Less\MpcuBundle\Entity\Session $session)
    {
        $this->session = $session;
    
        return $this;
    }

    /**
     * Get session
     *
     * @return \Less\MpcuBundle\Entity\Session 
     */
    public function getSession()
    {
        return $this->session;
    }
}