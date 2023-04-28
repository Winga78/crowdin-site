<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\TraducteSourceType;
use App\Entity\TraductionTarget;
use App\Repository\ProjectRepository;
use App\Repository\TraductionTargetRepository;
use App\Repository\SourceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class TraducteSourceController extends AbstractController
{
    public function __construct(ProjectRepository $projectRepository, TraductionTargetRepository $traductionTargetRepository , SourceRepository $sourceRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->traductionTargetRepository = $traductionTargetRepository;
        $this->sourceRepository= $sourceRepository;
    }

    #[Route('/traductesource/{id}', name: 'app_traducte_source')]
    public function index($id , ManagerRegistry $doctrine,Request $request): Response
    {
      
        $entityManager = $doctrine->getManager();
        $traduction = new TraductionTarget();
        $form = $this->createForm(TraducteSourceType::class,$traduction); 
        $traducte_projet = $this->projectRepository->find($id);
        $form -> handleRequest( $request); 
        //$access = $this->denyAccessUnlessGranted('POST_EDIT', $this->getUser());
        
        if (!$this->isGranted('ROLE_ADMIN')) {
          
            return $this->redirectToRoute('app_home');
        }

        if($form->isSubmitted() && $form-> isValid()) {
            $traduction = $form->getData();
          
                $traduction -> setUser($this->getUser()); 
                $traduction -> setText( $traduction->getText()); 
                $traduction ->setSource($traduction->getSource());
                $traduction -> setLangue($traduction->getLangue());
                $this->traductionTargetRepository->save($traduction, true);
                return $this->redirectToRoute('app_home');
        }

        return $this->render('traducte_source/index.html.twig', [
            'controller_name' => 'TraducteSourceController',
            'form'=> $form->createView(),
            'traducte_projet' => $traducte_projet
        ]);
    }
}
