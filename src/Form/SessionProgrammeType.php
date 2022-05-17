<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Session;
use App\Entity\SessionProgramme;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessionProgrammeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nb_jours_cours', NumberType::class)
            ->add('session', EntityType::class, [
                'class' => Session::class,
                'choice_label' => 'intitule_session', ])
            ->add('cours', EntityType::class, [
                'class' => Cours::class,
                'choice_label' => 'nom_cours', ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SessionProgramme::class,
        ]);
    }
}
