<?php

namespace App\Repository;

use App\Entity\Emprunt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Emprunt>
 *
 * @method Emprunt|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emprunt|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emprunt[]    findAll()
 * @method Emprunt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmpruntRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emprunt::class);
    }

    //    /**
    //     * @return Emprunt[] Returns an array of Emprunt objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Emprunt
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    /**
     * @param Value $value The number of emprunts to search for
     * @return Emprunt[] Returns an array of Emprunt objects
     */
    public function findLastEmprunt($value): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.dateEmprunt', 'DESC')
            ->setMaxResults($value)
            ->getQuery()
            ->getResult();
    }


     /**
     * @param Value $value The value of emprunteurId to search for
     * @return Emprunt[] Returns an array of Emprunt objects
     */
    public function findEmpruntByEmprunteurId($value): array
    {
        return $this->createQueryBuilder('e')
            ->select('e')
            ->where('e.emprunteur = :value')
            ->setParameter('value', $value)
            ->orderBy('e.dateEmprunt', 'ASC')
            ->getQuery()
            ->getResult();
    }

      /**
     * @param Value $value The value of LivreId to search for
     * @return Emprunt[] Returns an array of Emprunt objects
     */
    public function findEmpruntByLivreId($value): array
    {
        return $this->createQueryBuilder('e')
            ->select('e')
            ->where('e.livre = :value')
            ->setParameter('value', $value)
            ->orderBy('e.dateEmprunt', 'DESC')
            ->getQuery()
            ->getResult();
    }

     /**
     * @param Value $value The number of emprunts to search for
     * @return Emprunt[] Returns an array of Emprunt objects
     */
    public function findLastEmpruntRetour($value): array
    {
        return $this->createQueryBuilder('e')
            ->select('e')
            ->where('e.dateRetour IS NOT null')
            ->orderBy('e.dateRetour', 'DESC')
            ->setMaxResults($value)
            ->getQuery()
            ->getResult();
    }

     /**
     * This method finds all Emprunt with no return date
     * @return Emprunteur[] Returns an array of Emprunt objects
     */
    public function findAllNonReturnEmprunt(): array
    {
        
        return $this->createQueryBuilder('e')
            ->select('e')
            ->Where('e.dateRetour IS null')
            ->orderBy('e.dateEmprunt', 'ASC')
            ->getQuery()
            ->getResult();
    }

          /**
     * @param Value $value The value of LivreId to search for
     * @return Emprunt[] Returns an array of Emprunt objects
     */
    public function findEmpruntDataByLivreId($value): array
    {
        return $this->createQueryBuilder('e')
            ->select('e')
            ->where('e.livre = :value')
            ->setParameter('value', $value)
            ->getQuery()
            ->getResult();
    }



}
