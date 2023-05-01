<?php

namespace App\Controller;
use App\Service\DisplayProject;
use App\Repository\UserRepository; 
use App\Repository\UserLangueRepository; 
use App\Repository\ProjectRepository;
use App\Entity\User;
use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
class HomeController extends AbstractController
{

    public function __construct(DisplayProject $displayProject){

       
        $this->displayProject = $displayProject;
        
        // $this->sourceRepository =$sourceRepository;
    }

    #[Route('/home', name: 'app_home')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $lesprojets = $this->displayProject->projectsAffichage($this->getUser());
        $projectcount = $this->displayProject->projectCount();

        $articles = $paginator->paginate(
            $lesprojets, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'lesprojets' => $lesprojets,
            'articles' => $articles,
            'projectcount'=> $projectcount,
        ]);
    }
}
