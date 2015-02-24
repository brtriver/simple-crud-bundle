<?php
namespace Brtriver\SimpleCrudBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DemoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('email')
            ->add('birthday')
            ->add('save', 'submit');
    }

    public function getName()
    {
        return 'user';
    }
}