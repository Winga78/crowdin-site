<?php 
namespace App\Service;
use App\Repository\UserRepository; 
use App\Repository\UserLangueRepository; 
use App\Repository\ProjectRepository;
use App\Entity\User;
use App\Entity\Project;

class DisplayProject
{
    public function __construct( UserRepository $userRepository, UserLangueRepository $userLangueRepository , ProjectRepository $projectRepository){

        $this->userRepository = $userRepository;
        $this->userLangueRepository = $userLangueRepository;
        $this->projectRepository = $projectRepository;
    }

    public function projectsAffichage($user) 
    {
        $tab = [];
        $res_project_non_traduit = $this->projectRepository->findSqlRequest();
        $leslangues= $this->userLangueRepository->countUserLanguage($user);
        // $res_user_langues =  $this->projectRepository->findProjectUserLangueById($user->getId());
        $projects =  $this->projectRepository->findProjectUserById($user->getId());
    
      function removeDuplicatesByKeyValue($array, $key) {
        $uniqueArray = array_reduce($array, function($accumulator, $currentValue) use ($key) {
            $keyValue = $currentValue[$key];
            if (!isset($accumulator[$keyValue])) {
                $accumulator[$keyValue] = $currentValue;
            }
            return $accumulator;
        }, []);
        $uniqueArray = array_values($uniqueArray);
        return $uniqueArray;
    }

       if($leslangues < 2){
         $message = "vous n'êtes pas traducteur";
       }
       else {
          for ($i = 0 ; $i < count($projects) ; $i++){
            array_push($tab, $projects[$i]);
          }
             
       }
       
    $array = removeDuplicatesByKeyValue($tab, "id");
        return $array;
    }
}