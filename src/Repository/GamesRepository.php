<?php

namespace App\Repository;

use App\Entity\Swap;
use App\Entity\Games;
use App\Entity\Categories;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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

// dd($query);

        return $this->createQueryBuilder('g')
            ->andWhere('g.status = 1 AND g.mark like :val OR g.nom like :val')
            ->setParameter('val','%' . $query . '%')
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(5)
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
            ->andWhere('s.id = :val AND g.status = 1')
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
            ->andWhere('g.age > :val AND g.status = 1')
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
            ->andWhere('g.age < 18 AND g.status = 1')
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
    public function findGameByNotUser($value, $offset): array
    {
        return $this->createQueryBuilder('g')
            ->leftJoin(Swap::class, 's', 'WITH', 'g.id = s.idgameuser AND s.iduser = :val')
            ->where('s.idgameuser is null AND g.status = 1')
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
    public function findGameAdultByNotUser($value, $offset): array
    {
        return $this->createQueryBuilder('g')
            ->leftJoin(Swap::class, 's', 'WITH', 'g.id = s.idgameuser AND s.iduser = :val')
            ->where('s.idgameuser is null AND g.age > 17 AND g.status = 1')
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
    public function findGameChildByNotUser($value, $offset): array
    {
        return $this->createQueryBuilder('g')
            ->leftJoin(Swap::class, 's', 'WITH', 'g.id = s.idgameuser AND s.iduser = :val')
            ->where('s.idgameuser is null AND g.age < 18 AND g.status = 1')
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
    public function findGameByCateg($cat,$offset): array
    {

        // SELECT * FROM games JOIN categories on games.idcat_id = categories.id where categories.nom like "Puzzles" AND games.status = 1;

        return $this->createQueryBuilder('g')
            ->leftJoin(Categories::class, 'c', 'WITH', 'g.idcat = c.id')
            ->where('c.nom = :val AND g.status = 1')            
            ->setParameter('val', $cat)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(5)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function findGameAllByFive($offset): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.status = 1')
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
        return $this->createQueryBuilder('g')
            ->andWhere('g.status = 1')
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
            ->where('s.idgameuser is null AND g.status = 1')
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
            ->andWhere('g.age > :val AND g.status = 1')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Game[] Returns an count of Game objects by Categorie
     */
    public function findGameCatCount($cat): array
    {        
        $cat= 4;
        return $this->createQueryBuilder('g')
            ->select('count(g.id)')
            ->andWhere('g.idcat = :val AND g.status = 1')
            ->setParameter('val', $cat)
            ->getQuery()
            ->getResult();
    }
    


    // SELECT * FROM games 
    // JOIN categories on games.idcat_id = categories.id 
    // LEFT JOIN swap ON games.id = swap.idgameuser_id AND swap.iduser_id =1
    // where categories.nom like "Puzzles" AND games.status = 1 AND swap.idgameuser_id is  null

    /**
     * @return Game[] Returns an count of a Categorie Game objects by User
     */
    public function findGameCountCatUser($user,$cat): array
    {
        return $this->createQueryBuilder('g')
            ->select('count(g.id)')
            ->Join(Categories::class, 'c', 'WITH', 'g.idcat = c.id')  
            ->leftJoin(Swap::class, 's', 'WITH', 'g.id = s.idgameuser AND s.iduser = :user')                      
            ->where('c.nom LIKE :cat AND s.idgameuser is null AND g.status = 1')  
            ->setParameter('user', $user)
            ->setParameter('cat', $cat)           
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Game[] Returns all Categorie Game objects by not User
     */
    public function findGameCatByNotUser($user,$cat,$offset): array
    {
        return $this->createQueryBuilder('g')           
            ->Join(Categories::class, 'c', 'WITH', 'g.idcat = c.id')  
            ->leftJoin(Swap::class, 's', 'WITH', 'g.id = s.idgameuser AND s.iduser = :user')                      
            ->where('c.nom LIKE :cat AND s.idgameuser is null AND g.status = 1')  
            ->setParameter('user', $user)
            ->setParameter('cat', $cat)  
            ->setFirstResult($offset)         
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
            ->andWhere('g.age < :val AND g.status = 1')
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
            ->where('s.idgameuser is null AND g.age > 17 AND g.status = 1')
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
            ->where('s.idgameuser is null AND g.age < 18  AND g.status = 1')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }   
}
