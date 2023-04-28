<?php 
namespace App\Service;
use App\Repository\UserRepository; 
use App\Repository\UserLangueRepository; 
use App\Repository\ProjectRepository;
use App\Entity\User;
use App\Entity\Project;

class UpdateProfil
{

    public function __construct( UserRepository $userRepository, UserLangueRepository $userLangueRepository , ProjectRepository $projectRepository){

        $this->userRepository = $userRepository;
        $this->userLangueRepository = $userLangueRepository;
        $this->projectRepository = $projectRepository;
    }

    public function updateDescription($user) 
    {
        $this->userRepository -> save($user, true);

    }


    public function updateLangue($user , $langue) 
    {
        $nb = $this -> userLangueRepository -> countUserLanguage($user);
        // dd($nb);
        if(count($user->getRoles()) < 2 and $nb>= 2){
            $user->setRoles(['ROLE_USER','ROLE_ADMIN']);
            $this->userLangueRepository -> save($langue, true);
            echo "oui";
        }

        else {
            $this->userLangueRepository -> save($langue, true);
        }
        
    }


}