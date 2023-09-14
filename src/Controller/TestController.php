<?php

namespace App\Controller;

use DateTime;
use App\Entity\Livre;
use App\Entity\User;
use App\Entity\Auteur;
use App\Entity\Emprunt;
use App\Entity\Emprunteur;
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


    #[Route('/emprunteur', name: 'app_test_emprunteur')]
    public function emprunteur(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $repositoryEmprunteur = $em->getRepository(Emprunteur::class);
        $repositoryUser = $em->getRepository(User::class);


        //liste des emprunteurs, triés par nom et prenom
        $emprunteurs = $repositoryEmprunteur->findAllEmprunteurOrderByNameAndFirstName();

        //données de l'emprunteur id 3
        $emprunteur3 = $repositoryEmprunteur->find(3);

        //les données de l'emprunteur qui sont reliées au user dont l'id est `3
        $user3 = $repositoryUser->find(3);

        //la liste des emprunteurs dont le nom ou le prénom contient le mot clé `foo`, 
        //triée par ordre alphabétique de nom et prénom
        $emprunteurFooFoo = $repositoryEmprunteur->findEmprunteurByKeyword("foo");

        //la liste des emprunteurs dont le téléphone contient le mot clé `1234`, 
        //triée par ordre alphabétique de nom et prénom
        $emprunteur1234 = $repositoryEmprunteur->findEmprunteurByKeywordInTel("1234");

        // la liste des emprunteurs dont la date de création est antérieure au 01/03/2021 exclu 
        //(c-à-d strictement plus petit), triée par ordre alphabétique de nom et prénom
        $date = new DateTime('2021-03-01');
        $emprunteursBeforeDate = $repositoryEmprunteur->findEmprunteurByDateCreatedAt($date);


        $title = "test des emprunteurs";


        return $this->render('test/emprunteur.html.twig', [
            'controller_name' => 'TestController',
            'title' => $title,
            'emprunteurs' => $emprunteurs,
            'emprunteur3' => $emprunteur3,
            'user3' => $user3,
            'emprunteurFooFoo' => $emprunteurFooFoo,
            'emprunteur1234' => $emprunteur1234,
            'emprunteursBeforeDate' => $emprunteursBeforeDate,
        ]);
    }

    #[Route('/emprunt', name: 'emprunt')]
    public function emprunt(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $repositoryEmprunt = $em->getRepository(Emprunt::class);
        
        // la liste des 10 derniers emprunts au niveau chronologique,
        // triée par ordre **décroissant** de date d'emprunt
        $listeLast10Emprunt = $repositoryEmprunt->findLastEmprunt(10);

        //la liste des emprunts de l'emprunteur dont l'id est `2`, 
        //triée par ordre **croissant** de date d'emprunt (le plus ancien en premier)
        $empruntId2 = $repositoryEmprunt->findEmpruntByEmprunteurId(2);

        //la liste des emprunts du livre dont l'id est `3`, 
        //triée par ordre **décroissant** de date d'emprunt (le plus récent en premier)
        $empruntLivreId3 = $repositoryEmprunt->findEmpruntByLivreId(3);

        //la liste des 10 derniers emprunts qui ont été retournés, 
        //triée par ordre **décroissant** de date de rendretour (le plus récent en premier)
        $ListeLast10RetourEmprunt = $repositoryEmprunt->findLastEmpruntRetour(10);

        // la liste des emprunts qui n'ont pas encore été retournés (c-à-d dont la date de retour est nulle), 
        //triée par ordre **croissant** de date d'emprunt (le plus ancien en premier)
        $listeEmpruntNoReturnDate = $repositoryEmprunt->findAllNonReturnEmprunt();

        //les données de l'emprunt relié au livre dont l'id est `3`
        $dataEmpruntLivreId3 = $repositoryEmprunt->findEmpruntDataByLivreId(3);

        //création d'un nouvel emprunt
        $newEmprunt = new Emprunt();
        $newEmprunt->setDateEmprunt(new DateTime('2020-12-01 16:00:00'));
        $newEmprunt->setDateRetour(null);
        $emprunteurId4 = $em->getRepository(Emprunteur::class)->find(1);
        $newEmprunt->setEmprunteur($emprunteurId4);
        $livreId1 = $em->getRepository(Livre::class)->find(1);
        $newEmprunt->setLivre($livreId1);
        $em->persist($newEmprunt);
        $em->flush();

        //mise à jour d'un emprunt
        $empruntId3 = $repositoryEmprunt->find(3);
        $empruntId3->setDateRetour(new DateTime('2020-05-01 10:00:00'));
        $em->flush();

        //requete de suppression
        $empruntId42 = $repositoryEmprunt->find(42);
        
        if($empruntId42) {
            $em->remove($empruntId42);
            $em->flush();
        }        
        


        $title = "test des emprunts";

        return $this->render('test/emprunt.html.twig', [
            'controller_name' => 'TestController',
            'title' => $title,   
            'listeLast10Emprunt' => $listeLast10Emprunt,  
            'empruntId2' => $empruntId2,   
            'empruntLivreId3' => $empruntLivreId3,
            'ListeLast10RetourEmprunt' => $ListeLast10RetourEmprunt,
            'listeEmpruntNoReturnDate' => $listeEmpruntNoReturnDate,
            'dataEmpruntLivreId3' => $dataEmpruntLivreId3,
        ]);
    }
}
