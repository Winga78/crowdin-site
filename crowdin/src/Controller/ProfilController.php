<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\DisplayUserInfo;
use App\Service\UpdateProfil;
use App\Entity\User;
use App\Entity\UserLangue;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\UpdateInfoLangueUserType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProjectRepository;
use App\Repository\SourceRepository;
use App\Repository\UserRepository;
use App\Repository\TraductionTargetRepository;
use App\Form\UpdateInfoUserType;
class ProfilController extends AbstractController
{
    public function __construct(UpdateProfil $updateProfil, DisplayUserInfo $displayUserInfo)
    {
        $this->displayUserInfo = $displayUserInfo;
        $this->updateProfil = $updateProfil;
    }

    #[Route('/profil', name: 'app_profil')]
    public function index(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
    {
        $entityManager = $doctrine->getManager();
        $user = new User();
        $user_langue = new UserLangue();
        $form = $this->createForm(UpdateInfoUserType::class,$user); 
        $form_langue = $this->createForm(UpdateInfoLangueUserType::class,$user_langue); 
        $user = $entityManager->getRepository(User::class)->find($this ->getUser());
    
        $form -> handleRequest( $request); 
        $form_langue-> handleRequest( $request); 
        if($form->isSubmitted() && $form-> isValid()) {
            $user -> setDescription($form["description"]->getData());
            $this->updateProfil->updateDescription($user);
        }

        if($form_langue->isSubmitted() && $form_langue-> isValid()) {
            $user_langue = $form_langue->getData();
            $user_langue->setUser($this->getUser());
            $user_langue ->setLangue($form_langue["langue"]->getData());
            $this->updateProfil->updateLangue($this->getUser() , $user_langue);
        
        }
        
        $lestraductions = $this->displayUserInfo->userTraduction($this->getUser());
        $userlangues= $this->displayUserInfo->userLangues($this->getUser()->getId());
        $userdescription = $this->displayUserInfo->userDescription($this->getUser()->getId())->getDescription(); 

        // dd($nbLangue);
        // $this->updateProfil()
        // dd( $userdescription);
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
            'lestraductions' => $lestraductions,
            'form'=> $form->createView(),
            'form_langue'=> $form_langue->createView(),
            'userlangues' => $userlangues,
            "userdescription" => $userdescription
        ]);
    }
}
