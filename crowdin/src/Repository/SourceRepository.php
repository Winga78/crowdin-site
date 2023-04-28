<?php

namespace App\Repository;

use App\Entity\Source;
use App\Entity\TraductionTarget;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\RouterInterface;
/**
 * @extends ServiceEntityRepository<Source>
 *
 * @method Source|null find($id, $lockMode = null, $lockVersion = null)
 * @method Source|null findOneBy(array $criteria, array $orderBy = null)
 * @method Source[]    findAll()
 * @method Source[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SourceRepository extends ServiceEntityRepository
{
    private $router;
    public function __construct(ManagerRegistry $registry, RouterInterface $router)
    {
        parent::__construct($registry, Source::class);
        $this->router = $router;
    }

    public function save(Source $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Source $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByIdProject($id)
    {
        $route = $this->router->match('/traductesource/' . $id);
        $projectId = $route['id'];
        return $this->createQueryBuilder('s')
                   ->andWhere('s.project = :val')
                   ->setParameter('val', $projectId)
                   ->orderBy('s.id', 'ASC')
               ;
    }

   public function countSource($value)
   {
       return $this->createQueryBuilder('s')
           ->select('COUNT(s.id)')
           ->andWhere('s.project = :val')
           ->setParameter('val', $value)
           ->orderBy('s.id', 'ASC')
           ->getQuery()
           ->getSingleScalarResult();
       ;
   }

   public function montest($id)
   {
       $route = $this->router->match('/traductesource/' . $id);
       $projectId = $route['id'];
       return $this->createQueryBuilder('s')
       ->where('s.project = :project')
       ->andWhere('NOT EXISTS (
           SELECT t
           FROM App\Entity\TraductionTarget t
           WHERE t.source = s
       )')
       ->setParameter('project', $projectId);
   }



   public function findOneBySomeField($value): array
   {
       return $this->createQueryBuilder('s')
           ->andWhere('s.project = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getResult()
       ;
   }


   
//    public function findUntranslatedSourcesByProjectId($projectId)
// {
//     $entityManager = $this->getEntityManager();

//     $query = $entityManager->createQuery('
//         SELECT s
//         FROM App\Entity\Source s
//         WHERE s.project = :project
//         AND NOT EXISTS (
//             SELECT t
//             FROM App\Entity\TraductionTarget t
//             WHERE t.source = s
//         )
//     ')
//     ->setParameter('project', $projectId);

//     return $query->getResult();
// }
//    /**
//     * @return Source[] Returns an array of Source objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Source
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
