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
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Game[] Returns an array of adult Game objects 
     */
    public function findGameByAdult($offset): array
    {       
        $value = 17;
        return $this->createQueryBuilder('g')           
            ->andWhere('g.age > :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(5)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }


    /**
     * @return Game[] Returns an array of Children Game objects 
     */
    public function findGameByChild($offset): array
    {       
        return $this->createQueryBuilder('g')
            ->andWhere('g.age < 18')            
            ->orderBy('g.age', 'ASC')
            ->setMaxResults(5)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    // SELECT * FROM games left JOIN swap ON games.id = swap.idgameuser_id AND swap.iduser_id = $value WHERE swap.idgameuser_id is  null;

    /**
     * @return Game[] Returns an array of Game objects by User
     */
    public function findGameByNotUser($value,$offset): array
    {               
            return $this->createQueryBuilder('g')              
            ->leftJoin(Swap::class, 's', 'WITH', 'g.id = s.idgameuser AND s.iduser = :val')
            ->where('s.idgameuser is null')            
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(5)
            ->setFirstResult($offset)
            ->getQuery()            
            ->getResult();      
    }

    /**
     * @return Game[] Returns an array of Game objects by User
     */
    public function findGameAdultByNotUser($value,$offset): array
    {               
            return $this->createQueryBuilder('g')              
            ->leftJoin(Swap::class, 's', 'WITH', 'g.id = s.idgameuser AND s.iduser = :val')
            ->where('s.idgameuser is null AND g.age > 17')            
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(5)
            ->setFirstResult($offset)
            ->getQuery()            
            ->getResult();      
    }

    /**
     * @return Game[] Returns an array of Game objects by User
     */
    public function findGameChildByNotUser($value,$offset): array
    {               
            return $this->createQueryBuilder('g')              
            ->leftJoin(Swap::class, 's', 'WITH', 'g.id = s.idgameuser AND s.iduser = :val')
            ->where('s.idgameuser is null AND g.age < 18')            
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(5)
            ->setFirstResult($offset)
            ->getQuery()            
            ->getResult();      
    }

    /**
     * @return Game[] Returns an array of Game objects by Categorie
     */
    public function findGameByCateg($value): array
    {          
            return $this->createQueryBuilder('g')   
            ->andWhere('g.idcat = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();       
    }

    public function findGameAllByFive($offset): array
    {          
            return $this->createQueryBuilder('g')
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(5)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();       
    }
    
    /**
     * @return Game[] Returns an count of all Game objects
     */
    public function findGameCount(): array
    {       
            $value = 17;   
            return $this->createQueryBuilder('g')
            ->select('count(g.id)')            
            ->getQuery()
            ->getResult();       
    }

    /**
     * @return Game[] Returns an count of all Game objects by User
     */
    public function findGameCountUser($value): array
    {               
            return $this->createQueryBuilder('g')  
            ->select('count(g.id)')           
            ->leftJoin(Swap::class, 's', 'WITH', 'g.id = s.idgameuser AND s.iduser = :val')
            ->where('s.idgameuser is null')            
            ->setParameter('val', $value)            
            ->getQuery()            
            ->getResult();      
    }
   
    /**
     * @return Game[] Returns an count of Game objects by Adulte
     */
    public function findGameAdultCount(): array
    {        
        $value = 17;
        return $this->createQueryBuilder('g')  
            ->select('count(g.id)')          
            ->andWhere('g.age > :val')
            ->setParameter('val', $value)            
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Game[] Returns an count of Game objects by Child
     */
    public function findGameChildCount(): array
    {        
        $value = 18;
        return $this->createQueryBuilder('g')  
            ->select('count(g.id)')          
            ->andWhere('g.age < :val')
            ->setParameter('val', $value)            
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Game[] Returns an count of all Adult Game objects by User
     */
    public function findGameCountAdultUser($value): array
    {  
            return $this->createQueryBuilder('g')  
            ->select('count(g.id)')           
            ->leftJoin(Swap::class, 's', 'WITH', 'g.id = s.idgameuser AND s.iduser = :val')
            ->where('s.idgameuser is null AND g.age > 17')            
            ->setParameter('val', $value)                      
            ->getQuery()            
            ->getResult();      
    }

    /**
     * @return Game[] Returns an count of all Children Game objects by User
     */
    public function findGameCountChildUser($value): array
    {  
        // dd('find',$value);
            return $this->createQueryBuilder('g')  
            ->select('count(g.id)')           
            ->leftJoin(Swap::class, 's', 'WITH', 'g.id = s.idgameuser AND s.iduser = :val')
            ->where('s.idgameuser is null AND g.age < 18')            
            ->setParameter('val', $value)                      
            ->getQuery()            
            ->getResult();      
    }
   
   
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
