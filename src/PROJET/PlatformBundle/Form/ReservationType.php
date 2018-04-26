<?php

namespace PROJET\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReservationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date',        DateType::class, array(
                'label'  =>'Date de réservation',
                'widget' => 'choice',
                'years'  => range(date('Y')+1, date('Y'))))
            ->add('email',       TextType::class)
            ->add('tickets',     collectionType::class, array(
                'label'         => false,
                'entry_type'    => TicketType::class,
                'entry_options' => array('label' => false),
                'allow_add'     => true,
                'by_reference'  => false,
            ))
            ->add('save',        SubmitType::class, array(
                'attr' => array('class' => 'btn btn-warning'),
                'label' => 'Valider'));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PROJET\PlatformBundle\Entity\Reservation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'projet_platformbundle_reservation';
    }


}
