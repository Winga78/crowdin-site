<?php

namespace App\Repository;

use App\Entity\UserLangue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserLangue>
 *
 * @method UserLangue|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserLangue|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserLangue[]    findAll()
 * @method UserLangue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserLangueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserLangue::class);
    }

    public function save(UserLangue $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserLangue $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByUserID($id)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.user = :val')
            ->setParameter('val', $id)
            ->orderBy('u.id', 'ASC')
        ;
    }

    public function countUserLanguage($value)
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.langue)')
            ->andWhere('u.user = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getSingleScalarResult();
        ;
    }
       /**
    * @return UserLangue[] Returns an array of UserLangue objects
    */
   public function findUserLangues($value): array
   {
       return $this->createQueryBuilder('u')
           ->andWhere('u.user = :val')
           ->setParameter('val', $value)
           ->orderBy('u.id', 'ASC')
           ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }
 
   public function findUserLangueById($userId)
   {
       $conn = $this->getEntityManager()->getConnection();
       $sql = 'SELECT langue FROM user_langue where user_id =:userId;';
       $stmt = $conn->prepare($sql);
       $stmt->bindValue('userId', $userId);
       $resultSet = $stmt->executeQuery();
       return $resultSet->fetchAll();
   }

//    /**
//     * @return UserLangue[] Returns an array of UserLangue objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserLangue
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
