<?php

namespace App\Form;
use App\Entity\Source;
use App\Entity\UserLangue;
use App\Entity\TraductionTarget;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Repository\SourceRepository;
use App\Repository\UserLangueRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
class TraducteSourceType extends AbstractType
{
    private $requestStack;
    private $tokenStorage;
    public function __construct(RequestStack $requestStack, TokenStorageInterface $tokenStorage)
    {
        $this->requestStack = $requestStack;
        $this->tokenStorage = $tokenStorage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
      
        $builder
            ->add('langue', EntityType::class, ["label"=> false, 
            'class' => UserLangue::class,
            'choice_label' => 'langue',
            'query_builder' => function (UserLangueRepository $er) {
                $user = $this->tokenStorage->getToken()->getUser();
                return $er->findByUserID($user);
            },
            
           ])

            ->add('text', TextareaType::class, ["label"=> false])
            ->add('source', EntityType::class, ["label"=> false, 
            'class' => Source::class,
            'query_builder' => function (SourceRepository $er) {
                $request = $this->requestStack->getCurrentRequest();
                $projectId = $request->attributes->get('id');
                return $er->montest($projectId);
            },
            'choice_label' => 'content',
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
            'data_class' => TraductionTarget::class,
        ]);
    }
}
