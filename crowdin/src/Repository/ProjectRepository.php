<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 *
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function save(Project $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Project $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findOneByIdUser($name):array
    {
        return $this->createQueryBuilder('p')
                   ->andWhere('p.user = :val')
                   ->setParameter('val', $name)
                   ->getQuery()

               ;
    }

    public function findSqlRequest($userId)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT project.id, project.user_id , project.name, pul.user_id, pult.user_id, project.langue FROM project INNER JOIN user_langue as pul ON pul.user_id = project.user_id INNER JOIN user_langue as pult ON pul.langue = pult.langue WHERE pult.user_id = :userId and pul.user_id != :userId;';
        // $sql = 'SELECT project.id , project.name , project.langue , count(source.id) as source , COUNT(source_id) as traduction FROM user_langue as plu INNER JOIN project ON project.user_id = plu.user_id  INNER  JOIN user_langue as utl ON utl.langue = plu.langue LEFT JOIN source ON project.id = source.project_id LEFT JOIN traduction_target ON source.id = traduction_target.source_id  where utl.user_id = :userId and plu.user_id != :userId GROUP BY project.name , project.langue, project.id;';
        // $sql = ' SELECT * FROM (SELECT project.id , project.langue, project.name , user_langue.user_id as uid, user_langue.langue as t, COUNT(source.id) as source, COUNT(source_id) as traduction FROM project LEFT JOIN user_langue ON project.user_id = user_langue.user_id  LEFT JOIN source ON project.id = project_id LEFT JOIN traduction_target ON source.id = source_id GROUP BY project.name, t ,project.langue, project.id) as subquery WHERE subquery.traduction < source
        // and subquery.uid = :userId;';
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('userId', $userId);
        $resultSet = $stmt->executeQuery();
        return $resultSet->fetchAll();
    }

    public function findProjectCount()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT source.project_id as id , COUNT(source.id) as sources, COUNT(traduction_target.source_id) as traductions FROM source LEFT JOIN traduction_target ON source.id = traduction_target.source_id GROUP BY id;';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        return $resultSet->fetchAll();
    }

    public function findProjectUserById($userId)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT * FROM project where user_id != :userId;';
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('userId', $userId);
        $resultSet = $stmt->executeQuery();
        return $resultSet->fetchAll();
    }


    public function findProjectUserLangueById($userId)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT DISTINCT project.name , user_langue.langue FROM project INNER JOIN user_langue as user_creator ON project.user_id = user_creator.user_id INNER JOIN user_langue ON user_langue.user_id = :userId;';
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('userId', $userId);
        $resultSet = $stmt->executeQuery();
        return $resultSet->fetchAll();
    }
    
//    /**
//     * @return Project[] Returns an array of Project objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Project
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
