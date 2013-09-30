<?php 

namespace Less\MpcuBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

use Less\MpcuBundle\Entity\UserSession;

class Registration
{
    /**
     * @Assert\Type(type="Less\MpcuBundle\Entity\UserSession")
     * @Assert\Valid()
     */
    protected $user;

    /**
     * @Assert\NotBlank()
     * @Assert\True()
     */
    protected $termsAccepted;

    public function setUser(UserSession $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getTermsAccepted()
    {
        return $this->termsAccepted;
    }

    public function setTermsAccepted($termsAccepted)
    {
        $this->termsAccepted = (Boolean) $termsAccepted;
    }
}