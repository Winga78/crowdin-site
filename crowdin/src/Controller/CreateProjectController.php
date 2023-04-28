<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Entity\Project;
use App\Entity\Source;
use App\Repository\ProjectRepository;
use App\Repository\SourceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProjectCreationType;
use App\Form\CreateSourceType;
use Symfony\Component\Validator\Constraints\Date;

class CreateProjectController extends AbstractController
{

    private $security;

    public function __construct(ProjectRepository $projectRepository, SourceRepository $sourceRepository)
    {
        $this->projectRepository = $projectRepository;
        $this ->sourceRepository = $sourceRepository;
       
    }

    #[Route('/createproject', name: 'app_create_project')]
    public function index(Request $request,EntityManagerInterface $entityManager,  ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $project = new Project();
        $source = new Source();
        $form = $this->createForm(ProjectCreationType::class,$project); 
        $form_source = $this->createForm(CreateSourceType::class,$source);
        $form -> handleRequest( $request); 

        $form_source->handleRequest($request);

        if($form->isSubmitted() && $form-> isValid()) {
            $project = $form->getData();
            
                $project -> setUser($this->getUser()); 
                $project -> setName( $project->getName()); 
                $project ->setDatecreation(new \DateTime());
                $project -> setLangue($project->getLangue());
                $this->projectRepository->save($project, true);
                return $this->redirectToRoute('app_create_project');
        }

        elseif($form_source->isSubmitted() && $form_source->isValid()){
            $source = $form_source->getData();
              $source -> setContent( $source->getContent()); 
              $this->sourceRepository->save($source, true);
              return $this->redirectToRoute('app_create_project');
      }


        return $this->render('create_project/index.html.twig', [
            'controller_name' => 'CreateProjectController',
            'form'=> $form->createView(),
            'form_source'=>$form_source->createView()
        ]);
    
  }
}
