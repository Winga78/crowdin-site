<?php

namespace App\Form;

use App\Entity\Langues;
use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\LanguesRepository;
class ProjectCreationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ["label"=> false])
            ->add('langue', EntityType::class, ["label"=> false,
            'class' => Langues::class,
            'choice_label' => 'name',
            'placeholder' => 'chosir une langue',
                'query_builder' => function (LanguesRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },

                
                ])


                -> add('submit', SubmitType:: class, [
                    'label' => "Enregistrer",
                    'attr'=> [
                        'class'=> 'btn-subscription', 
                        ]
                ])
    
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
