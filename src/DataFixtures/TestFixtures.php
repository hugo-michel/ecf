<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Auteur;
use App\Entity\Emprunt;
use App\Entity\Emprunteur;
use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestFixtures extends Fixture implements FixtureGroupInterface
{
    private $faker;
    private $hasher;
    private $manager;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->faker = FakerFactory::create('fr_FR');
        $this->hasher = $hasher;
    }

    public static function getGroups(): array
    {
        return ['test'];
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this->loadAuteurs();
        $this->loadGenres();
        $this->loadLivres();
        $this->loadEmprunteurs();
        $this->loadEmprunts();
    }

    public function loadAuteurs(): void
    {
        $repository = $this->manager->getRepository(Auteur::class);

        //données static

        $datas = [
            [
                'nom' => 'auteur inconnu',
                'prenom' => 'auteur inconnu',
            ],
            [
                'nom' => 'Cartier',
                'prenom' => 'Hugues',
            ],
            [
                'nom' => 'Lambert',
                'prenom' => 'Armand',
            ],
            [
                'nom' => 'Moitessier',
                'prenom' => 'Thomas',
            ],
        ];
        foreach ($datas as $data) {
            $auteur = new Auteur();
            $auteur->setNom($data['nom']);
            $auteur->setPrenom($data['prenom']);
           
            $this->manager->persist($auteur);
        }

        $this->manager->flush();
    }

    public function loadGenres(): void
    {
        $repository = $this->manager->getRepository(Genre::class);

        //données static

        $datas = [
            [
                'nom' => 'poésie',
                'description' => null,
            ],
            [
                'nom' => 'nouvelle',
                'description' => null,
            ],
            [
                'nom' => 'roman historique',
                'description' => null,
            ],
            [
                'nom' => "roman d'amour",
                'description' => null,
            ],
            [
                'nom' => "roman d'aventure",
                'description' => null,
            ],
            [
                'nom' => 'science-fiction',
                'description' => null,
            ],
            [
                'nom' => 'fantasy',
                'description' => null,
            ],
            [
                'nom' => 'biographie',
                'description' => null,
            ],
            [
                'nom' => 'conte',
                'description' => null,
            ],
            [
                'nom' => 'témoignage',
                'description' => null,
            ],
            [
                'nom' => 'théâtre',
                'description' => null,
            ],
            [
                'nom' => 'essai',
                'description' => null,
            ],
            [
                'nom' => 'journal intime',
                'description' => null,
            ],
        ];

        foreach ($datas as $data) {
            $genre = new Genre();
            $genre->setNom($data['nom']);
            $genre->setDescription($data['description']);
           
            $this->manager->persist($genre);
        }

        $this->manager->flush();
    }

    public function loadLivres(): void
    {
        $repositoryAuteur = $this->manager->getRepository(Auteur::class);
        $auteurs = $repositoryAuteur->findAll();
        $auteur1 = $repositoryAuteur->find(1);
        $auteur2 = $repositoryAuteur->find(2);
        $auteur3 = $repositoryAuteur->find(3);
        $auteur4 = $repositoryAuteur->find(4);

        $repositoryGenre = $this->manager->getRepository(Genre::class);
        $genres = $repositoryGenre->findAll();
        $genre1 = $repositoryGenre->find(1);
        $genre2 = $repositoryGenre->find(2);
        $genre3 = $repositoryGenre->find(3);
        $genre4 = $repositoryGenre->find(4);




        //données static

        $datas = [
            [
                'titre' => 'Lorem ipsum dolor sit amet',
                'anneeEdition' => 2010,
                'nombrePages' => 100,
                'codeIsbn' => '9785786930024',
                'auteurs' => [$auteur1],
                'genres' => [$genre1],
            ],
            [
                'titre' => 'Consectetur adipiscing elit',
                'anneeEdition' => 2011,
                'nombrePages' => 150,
                'codeIsbn' => '9783817260935',
                'auteurs' => [$auteur2],
                'genres' => [$genre2],
            ],
            [
                'titre' => 'Mihi quidem Antiochum',
                'anneeEdition' => 2012,
                'nombrePages' => 200,
                'codeIsbn' => '9782020493727',
                'auteurs' => [$auteur3],
                'genres' => [$genre3],
            ],
            [
                'titre' => 'Quem audis satis belle ',
                'anneeEdition' => 2013,
                'nombrePages' => 250,
                'codeIsbn' => '9794059561353',
                'auteurs' => [$auteur4],
                'genres' => [$genre4],
            ],

        ];
        foreach ($datas as $data) {
            $livre = new Livre();
            $livre->setTitre($data['titre']);
            $livre->setAnneeEdition($data['anneeEdition']);
            $livre->setNombrePages($data['nombrePages']);
            $livre->setCodeIsbn($data['codeIsbn']);
            $livre->setAuteur($data['auteurs'][0]);

            $livre->addGenre($data['genres'][0]);

            $this->manager->persist($livre);
        }

        $this->manager->flush();

    }

    public function loadEmprunteurs(): void
    {
        //données static

        $datas = [
            [
                'email' => 'foo.foo@example.com',
                'roles' => ['ROLE_USER'],
                'password' => '123',
                'enabled' => true,

                'nom' => 'foo',
                'prenom' => 'foo',
                'tel' => '123456789'
            ],
            [
                'email' => 'bar.bar@example.com',
                'roles' => ['ROLE_USER'],
                'password' => '123',
                'enabled' => false,

                'nom' => 'bar',
                'prenom' => 'bar',
                'tel' => '123456789'
            ],
            [
                'email' => 'baz.baz@example.com',
                'roles' => ['ROLE_USER'],
                'password' => '123',
                'enabled' => true,

                'nom' => 'baz',
                'prenom' => 'baz',
                'tel' => '123456789'
            ],
        ];

        foreach ($datas as $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $password = $this->hasher->hashPassword($user, $data['password']);
            $user->setPassword($password);
            $user->setRoles($data['roles']);
            $user->setEnabled($data['enabled']);

            $this->manager->persist($user);

            $emprunteur = new Emprunteur();

            $emprunteur->setNom($data['nom']);
            $emprunteur->setPrenom($data['prenom']);
            $emprunteur->setTel($data['tel']);

            $emprunteur->setUser($user);

            $this->manager->persist($emprunteur);

        }

        $this->manager->flush();

    }

    public function loadEmprunts(): void
    {
        $repositoryLivre = $this->manager->getRepository(Livre::class);
        $livre1 = $repositoryLivre->find(1);
        $livre2 = $repositoryLivre->find(2);
        $livre3 = $repositoryLivre->find(3);

        $repositoryEmprunteur = $this->manager->getRepository(Emprunteur::class);
        $emprunteur1 = $repositoryEmprunteur->find(1);
        $emprunteur2 = $repositoryEmprunteur->find(2);
        $emprunteur3 = $repositoryEmprunteur->find(3);



        //donées statics

        $datas = [
            [
                'dateEmprunt' => new DateTime('2020-02-01 10:00:00'),
                'dateRetour' => new DateTime('2020-03-01 10:00:00'),
                'emprunteur' => [$emprunteur1],
                'livre' => [$livre1],
            ],
            [
                'dateEmprunt' => new DateTime('2020-03-01 10:00:00'),
                'dateRetour' => new DateTime('2020-04-01 10:00:00'),
                'emprunteur' => [$emprunteur2],
                'livre' => [$livre2],
            ],
            [
                'dateEmprunt' => new DateTime('2020-04-01 10:00:00'),
                'dateRetour' => null,
                'emprunteur' => [$emprunteur3],
                'livre' => [$livre3],
            ],
        ];

        foreach ($datas as $data) {
            $emprunt = new Emprunt();
            $emprunt->setDateEmprunt($data['dateEmprunt']);
            $emprunt->setDateRetour($data['dateRetour']);
            $emprunt->setEmprunteur($data['emprunteur'][0]);
            $emprunt->setLivre($data['livre'][0]);

            $this->manager->persist($emprunt);
        }

        $this->manager->flush();

    }


}