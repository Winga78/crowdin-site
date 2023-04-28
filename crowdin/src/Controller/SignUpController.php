<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\SignUpType;
use App\Repository\UserRepository; 


class SignUpController extends AbstractController
{

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('/inscription', name: 'app_sign_up')]
    public function index(Request $request,EntityManagerInterface $entityManager,  ManagerRegistry $doctrine,  UserPasswordHasherInterface $encoder): Response
    {


        $entityManager =   $doctrine->getManager();
        $user = new User();
        $form = $this->createForm(SignUpType::class,$user); 
        $form -> handleRequest( $request); 
       
        if($form->isSubmitted() && $form-> isValid()) {
            $user = $form->getData();
            $password= $encoder->hashPassword($user, $user->getPassword());
            $user_find= $doctrine->getRepository(User::class)-> findOneByEmail($user->getEmail()); 
           
                $user -> setUsername($user->getUsername()); 
                $user -> setEmail( $user->getEmail()); 
                $user ->setPassword( $password);
                $user -> setDescription($user->getDescription());
                $user->setRoles(['ROLE_USER']);
                $this->userRepository->save($user, true);
                return $this->redirectToRoute('app_sign_up');
        }

        return $this->render('sign_up/index.html.twig', [
            'controller_name' => 'SignUpController',
            'form'=> $form->createView()
        ]);
    }
}
