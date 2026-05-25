<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\Sanction;
use App\Entity\TypeSanction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SanctionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('employe', EntityType::class, [
                'class' => Employe::class,
                'choice_label' => function (Employe $employe) {
                    return $employe->getNom() . ' ' . $employe->getPrenom();
                },
                'placeholder' => 'Choisissez l\'employé à sanctionner',
                'required' => true,
                'expanded' => false,
            ])
            ->add('dateDebut',DateType::class,[
                'widget' => 'single_text', 
            ])
            ->add('dateFin',DateType::class,[
                'widget' => 'single_text', 
            ])
            ->add('typeSanction', ChoiceType::class, [
                'choices' => Sanction::type,
                'placeholder' => 'types de sanction *',
                'label' => false,
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sanction::class,
        ]);
    }
}
