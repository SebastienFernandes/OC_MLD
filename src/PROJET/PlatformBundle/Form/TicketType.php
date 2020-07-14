<?php

namespace PROJET\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName',       TextType::class, array(
                'attr' => array('class' => 'lastName'),
                'label' => 'Nom de famille'))
            ->add('firstName',      TextType::class, array(
                'attr' => array('class' => 'firstName'),
                'label' => 'Prénom'))
            ->add('country',        TextType::class, array(
                'attr' => array('class' => 'country'),
                'label' => 'Pays'))
            ->add('birthDate',      DateType::class, array(
                'attr' => array('class' => 'birthDate'),
                'label' => 'Anniversaire',
                'widget' => 'choice',
                'years'  => range(date('Y'), date('Y')-100)))
            ->add('reducedPrice',   CheckboxType::class, array(
                'attr' => array('class' => 'reducedPrice'),                
                'label' => 'Billet tarif réduit  ->',
                'required' => false))
            ->add('supprimer', ButtonType::class, [
                'attr' => array('class' => 'remove-ticket btn btn-danger')]);

        
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PROJET\PlatformBundle\Entity\Ticket'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'projet_platformbundle_ticket';
    }


}
