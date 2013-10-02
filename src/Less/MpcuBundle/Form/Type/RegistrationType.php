<?php 

namespace Less\MpcuBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('user', new UserSessionType());
		$builder->add('register','submit');
		$builder->add(
				'terms',
				'checkbox',
				array('property_path' => 'termsAccepted')
		);
	}

	public function getName()
	{
		return 'registration';
	}
}