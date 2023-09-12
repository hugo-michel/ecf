<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\Repository\RepositoryFactory;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/test')]
class TestController extends AbstractController
{
    #[Route('/user', name: 'app_test_user')]
    public function user(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $repositoryUser = $em->getRepository(User::class);

        //fonction all user order by email
        $users = $repositoryUser->findAllUsersOrderByMail();

        //trouve les datas de l'utilisateur 1
        $user1 = $repositoryUser->find(1);

        //trouve user dont email est foo.foo@example.com
        $userFooFoo = $repositoryUser->findByEmail("foo.foo@example.com");

        //trouver tous les user dont le role est ROLE_USER
        $roleUser = $repositoryUser->allRoleUser();

        //trouver tous les users inactifs, triés par email
        $userInactifs = $repositoryUser->falseEnabled();

        $title = "test des users";


        return $this->render('test/user.html.twig', [
            'controller_name' => 'TestController',
            'title' => $title,
            'users' => $users,
            'user1' => $user1,
            'userFooFoo' => $userFooFoo,
            'roleUser' => $roleUser,
            'userInactifs' => $userInactifs,
        ]);
    }

    

    #[Route('/livre', name: 'app_test_livre')]
    public function livre(ManagerRegistry $doctrine): Response
    {
        
        $title = "test des livres";


        return $this->render('test/livre.html.twig', [
            'controller_name' => 'TestController',
            'title' => $title,
        ]);
    }
}
