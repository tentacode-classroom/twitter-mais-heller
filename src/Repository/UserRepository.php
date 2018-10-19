<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function findUsers($search): array
    {
        $qb = $this->createQueryBuilder('u')
            ->andWhere('u.profileName LIKE :val')
            ->setParameter('val', '%'.$search.'%')
            ->getQuery()
        ;
        return $qb->execute();
    }

    public function findLasts(): array
    {
        return $this->createQueryBuilder('u')
        ->orderBy('u.id', 'DESC')
        ->setMaxResults(3)
        ->getQuery()
        ->getResult()
        ;
       
    }
}
