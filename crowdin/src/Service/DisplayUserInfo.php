<?php 
namespace App\Service;
use App\Repository\UserRepository; 
use App\Repository\UserLangueRepository; 
use App\Repository\ProjectRepository;
use App\Repository\SourceRepository;
use App\Repository\TraductionTargetRepository;
use App\Entity\User;
use App\Entity\Project;

class DisplayUserInfo
{
    public function __construct( SourceRepository $sourceRepository, TraductionTargetRepository $traductionRepository, UserRepository $userRepository, UserLangueRepository $userLangueRepository , ProjectRepository $projectRepository){

        $this->userRepository = $userRepository;
        $this->userLangueRepository = $userLangueRepository;
        $this->projectRepository = $projectRepository;
        $this->traductionRepository = $traductionRepository;
        $this ->sourceRepository = $sourceRepository;
    }

    public function userLangues($user) 
    {
        $leslangues= $this->userLangueRepository->findUserLangues($user);

        return $leslangues;
    }

    public function userDescription($user) 
    {
        $leslangues= $this->userRepository->findDescriptionUser($user);

        return $leslangues;
    }

    public function userTraduction($user) 
    {
        $tab = [];
        $lestraductions =  $this->traductionRepository ->findTraductionByUser($user);

        for($i = 0 ; $i < count($lestraductions) ; $i++){
            $idProject = $lestraductions[$i]->getSource()->getId();
            $lestraductions_by_project= $this ->sourceRepository->findOneBySomeField($idProject);
            for($u=0 ;  $u < count($lestraductions_by_project) ; $u++){
                array_push($tab , $lestraductions_by_project[$u]);
            }
           

        }

        return $tab;
    }
}