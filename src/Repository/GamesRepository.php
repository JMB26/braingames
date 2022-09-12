<?php

namespace App\Repository;

use App\Entity\Games;
use App\Repository\SwapRepository;
use App\Entity\Swap;
use ContainerAoUllF8\getSwapRepositoryService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Games>
 *
 * @method Games|null find($id, $lockMode = null, $lockVersion = null)
 * @method Games|null findOneBy(array $criteria, array $orderBy = null)
 * @method Games[]    findAll()
 * @method Games[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GamesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Games::class);
    }

    public function add(Games $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Games $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // Recherche des jeux par leur nom
    public function findGamesByName(string $query)
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->orX(
                        $qb->expr()->like('p.mark', ':query'),
                        $qb->expr()->like('p.nom', ':query'),
                    ),
                    //  $qb->expr()->isNotNull('p.created_at')
                )
            )
            ->setParameter('query', '%' . $query . '%');
        return $qb
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Game[] Returns an array of Game objects by User
     */
    public function findGameByUser($value): array
    {
        // dd($value);
        return $this->createQueryBuilder('s')
            ->andWhere('s.id = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Game[] Returns an array of Game objects by Adulte
     */
    public function findGameByAdult(): array
    {
        // dd($value);
        $value = 17;
        return $this->createQueryBuilder('s')
            ->andWhere('s.age > :val')
            ->setParameter('val', $value)
            ->orderBy('s.age', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }


     /**
     * @return Game[] Returns an array of Game objects by Adulte
     */
    public function findGameByChild(): array
    {
        // dd($value);
        $value = 18;
        return $this->createQueryBuilder('s')
            ->andWhere('s.age < :val')
            ->setParameter('val', $value)
            ->orderBy('s.age', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    // SELECT * FROM games left JOIN swap ON games.id = swap.idgameuser_id AND swap.iduser_id = $value WHERE swap.idgameuser_id is  null;

    /**
     * @return Game[] Returns an array of Game objects by User
     */
    public function findGameByNotUser($value): array
    {          
            return $this->createQueryBuilder('games')   
            ->addSelect('s')  
            ->leftJoin('games.swap', 's')
            ->where('s.idgameuser = :val')            
            ->setParameter('val', $value)
            ->orderBy('games.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()            
            ->getResult();         
    }

    public function findGameByCateg($value): array
    {          
            return $this->createQueryBuilder('g')   
            ->andWhere('g.idcat = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();       
    }

    // ->addSelect('r') 
    // ->leftJoin('e.relatedEntity', 'r')
    // ->where('r.foo = :parameter')
    // ->setParameter('parameter', $parameter)
    // ->getQuery();

    //    /**
    //     * @return Games[] Returns an array of Games objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Games
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
