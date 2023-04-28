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

    public function findSqlRequest()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = ' SELECT * FROM (SELECT project.id , project.langue, project.name , user_langue.langue as t, COUNT(source.id) as source, COUNT(source_id) as traduction FROM project LEFT JOIN user_langue ON project.user_id = user_langue.user_id  LEFT JOIN source ON project.id = project_id LEFT JOIN traduction_target ON source.id = source_id GROUP BY project.name, t ,project.langue, project.id) as subquery WHERE subquery.traduction < source;
        ';
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
