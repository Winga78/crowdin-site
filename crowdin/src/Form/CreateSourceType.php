<?php

namespace App\Form;

use App\Entity\Source;
use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\ProjectRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
class CreateSourceType extends AbstractType
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       
        $builder
            ->add('content', TextareaType::class, ["label"=> false])
            ->add('project', EntityType::class, ["label"=> false, 
            'class' => Project::class,
            'query_builder' => function (ProjectRepository $er) {
                $user = $this->tokenStorage->getToken()->getUser()->getId();
                return $er->createQueryBuilder('u')
                    ->andWhere('u.user = :val')
                    ->setParameter('val', $user)
                    ->orderBy('u.name', 'ASC');
            },
            'choice_label' => 'name',
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
            'data_class' => Source::class,
        ]);
    }
}
