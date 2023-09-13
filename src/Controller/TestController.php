<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Entity\User;
use App\Entity\Auteur;
use App\Entity\Genre;
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
        $em = $doctrine->getManager();
        $repositoryLivre = $em->getRepository(Livre::class);
        $repositoryAuteur = $em->getRepository(Auteur::class);
        $repositoryGenre = $em->getRepository(Genre::class);



        //liste de tous les livres, classé par ordre alphabetique
        $livres = $repositoryLivre->findAllLivreOrderByName();

        //trouve les datas du livre ayant l'id 1
        $livre1 = $repositoryLivre->find(1);

        //trouve la liste des livres contenant dans le titre lorem
        $livreLorem = $repositoryLivre->findBookByKeyword('lorem');

        //liste des livres dont l'auteur a l'id 2
        $listeLivreIdAuth2 = $repositoryLivre->findBy([
            'auteur' => 2,
        ], [
            'titre' => 'ASC',
        ]);

        //liste des livres dont le genre contient le mot "roman"
        $listeLivreGenreRoman = $repositoryLivre->findBooksByGenre("roman");

        $title = "test des livres";

        //création d'un nouveau livre
        $newLivre = new Livre();
        $newLivre->setTitre('Totum autem id externum');
        $newLivre->setAnneeEdition(2020);
        $newLivre->setNombrePages(300);
        $newLivre->setCodeIsbn(9790412882714);
        $auteur2 = $repositoryAuteur->find(2);
        $newLivre->setAuteur($auteur2);
        $genre6 = $repositoryGenre->find(6);
        $newLivre->addGenre($genre6);
        $em->persist($newLivre);
        $em->flush();

        //modification d'un livre existant id2
        $livre2 = $repositoryLivre->find(2);
        $livre2->setTitre('Aperiendum est igitur');
        $genre5 = $repositoryGenre->find(5);
        $livre2->addGenre($genre5);
        $em->flush();

        //suppression du livre ac id 123
        $livre123 = $repositoryLivre->find(123);

        if($livre123) {
            $em->remove($livre123);
            $em->flush();
        }



        return $this->render('test/livre.html.twig', [
            'controller_name' => 'TestController',
            'title' => $title,
            'livres' => $livres,
            'livre1' => $livre1,
            'livreLorem' => $livreLorem,
            'listeLivreIdAuth2' => $listeLivreIdAuth2,
            'listeLivreGenreRoman' => $listeLivreGenreRoman,
        ]);
    }
}
