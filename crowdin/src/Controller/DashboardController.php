<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Project;
use App\Entity\Source;
use App\Repository\ProjectRepository;
use App\Repository\SourceRepository;
use App\Repository\TraductionTargetRepository;
use Doctrine\ORM\EntityManagerInterface;
class DashboardController extends AbstractController
{
    public function __construct(TraductionTargetRepository $traductionTarget  )
    {
        $this ->traductionTarget = $traductionTarget;
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(EntityManagerInterface $entityManager): Response
    {
       $l = "en";
        $dataTraduction =  $this ->traductionTarget->findSqlRequest($this->getUser()->getId());
        // dd($dataTraduction);
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'dataTraduction' =>$dataTraduction,
            'l' => $l,
        ]);
    }
}
