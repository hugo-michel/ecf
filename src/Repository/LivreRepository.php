<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livre>
 *
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    //    /**
    //     * @return Livre[] Returns an array of Livre objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Livre
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * This method find all books ordered by book's title
     * @return livre[] Returns an array of Livre objects
     */
    public function findAllLivreOrderByName(): array
    {
        return $this->createQueryBuilder('l')
            ->select('l')
            ->orderBy('l.titre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * This method finds all books containing a keyword anywhere in the book's title, ordered by title
     * @param string $keyword The keyword to search for
     * @return Livre[] Returns an array of Livre objects
     */
    public function findBookByKeyword(string $keyword): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.titre LIKE :keyword')
            ->setParameter('keyword', "%$keyword%")
            ->orderBy('l.titre', 'ASC')
            ->getQuery()
            ->getResult();
    }


    // public function findBooksByGenre(Genre $genres): array
    // {
    //     return $this->createQueryBuilder('l')
    //         ->innerJoin('l.genres', 'genres')
    //         ->andWhere('genres = :genres')
    //         ->setParameter('genres', $genres)
    //         ->orderBy('l.titre', 'ASC')
    //         ->getQuery()
    //         ->getResult();
    // }

    // pour tester la requete du dessous
    // SELECT titre
    // FROM livre_genre
    // INNER JOIN livre ON livre_genre.livre_id = livre.id
    // INNER JOIN genre ON livre_genre.genre_id = genre.id
    // WHERE genre.nom LIKE '%roman%'  
    // ORDER BY `livre`.`titre` ASC

    /**
     * This method finds all books containing a keyword anywhere in the book genres ordered by title
     * @param string $genres The keyword to search for
     * @return Livre[] Returns an array of Livre objects
     */
    public function findBooksByGenre(string $genres): array
    {
        return $this->createQueryBuilder('l')
            ->innerJoin('l.genres', 'genres')
            ->andWhere('genres.nom LIKE :genres')
            ->setParameter('genres', "%$genres%")
            ->orderBy('l.titre', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
