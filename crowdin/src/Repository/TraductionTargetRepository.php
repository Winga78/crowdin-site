<?php

namespace App\Repository;

use App\Entity\TraductionTarget;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\DriverManager;
use App\Entity\Project;
/**
 * @extends ServiceEntityRepository<TraductionTarget>
 *
 * @method TraductionTarget|null find($id, $lockMode = null, $lockVersion = null)
 * @method TraductionTarget|null findOneBy(array $criteria, array $orderBy = null)
 * @method TraductionTarget[]    findAll()
 * @method TraductionTarget[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TraductionTargetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TraductionTarget::class);
    }

    public function save(TraductionTarget $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TraductionTarget $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function countTraduction($value)
    {
        return $this->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            ->andWhere('s.source = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getSingleScalarResult();
        ;
    }
    public function findSqlRequest($userId)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT project.name , project.datecreation, source.content, project.langue, COUNT(project_id) AS datasource, COUNT(source_id) AS datatraduction  FROM project LEFT JOIN source ON project.id = project_id LEFT JOIN traduction_target ON source_id = source.id where project.user_id = :userId GROUP BY source.content,project.name, project.datecreation, project.langue;';
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('userId', $userId);
        $resultSet = $stmt->executeQuery();
        return $resultSet->fetchAll();
        // $conn = $this->getEntityManager()->getConnection();
        // $sql = ' SELECT project.name , project.datecreation , project.langue, COUNT(project_id) AS datasource, COUNT(source_id) AS datatraduction  FROM project LEFT JOIN source ON project.id = project_id LEFT JOIN traduction_target ON source_id = source.id GROUP BY project.name, project.datecreation, project.langue;';
        // $stmt = $conn->prepare($sql);
        // $resultSet = $stmt->executeQuery();
        // return $qb->getQuery()->getResult();
    }


    public function findTraductionSource()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql= 'SELECT project_id  FROM source INNER JOIN traduction_target ON source.id != traduction_target.source_id';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        return $resultSet->fetchAll();
    }


   /**
    * @return TraductionTarget[] Returns an array of TraductionTarget objects
    */
   public function findTraductionByUser($value): array
   {
       return $this->createQueryBuilder('t')
           ->andWhere('t.user = :val')
           ->setParameter('val', $value)
           ->orderBy('t.id', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

   

//    /**
//     * @return TraductionTarget[] Returns an array of TraductionTarget objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TraductionTarget
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
