<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Session>
 *
 * @method Session|null find($id, $lockMode = null, $lockVersion = null)
 * @method Session|null findOneBy(array $criteria, array $orderBy = null)
 * @method Session[]    findAll()
 * @method Session[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Session $entity, bool $flush = false): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Session $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getCoursNonProgrammes($idSession)
    {
        $em = $this->getEntityManager();
        $sql1 = $em->createQueryBuilder();

        $sql1->select('IDENTITY(sp.cours)')
            ->from('App\Entity\SessionProgramme', 'sp')
            ->leftJoin('sp.session', 'se')
            ->where('se.id = :id');
        // permet d'avoir la liste des cours de la session

        $sql2 = $em->createQueryBuilder();
        $sql2->select('c')
            ->from('App\Entity\Cours', 'c')
            ->where($sql2->expr()->notIn('c.id', $sql1->getDQL()))
            ->setParameter('id', $idSession)
            ->orderby('c.nom_cours', 'ASC');
        $query = $sql2->getQuery();

        return $query->getResult();
    }

    public function AfficherSessionPasses()
    {
        $now = new \DateTime();

        return $this->createQueryBuilder('s')
        ->andWhere('s.date_fin < :val')
        ->setParameter('val', $now)
        ->orderBy('s.date_debut', 'ASC')
        ->getQuery()
        ->getResult();
    }

    public function AfficherSessionFutures()
    {
        $now = new \DateTime();

        return $this->createQueryBuilder('s')
        ->andWhere('s.date_fin > :val')
        ->setParameter('val', $now)
        ->orderBy('s.date_debut', 'ASC')
        ->getQuery()
        ->getResult();
    }

    public function AfficherSessionNow()
    {
        $now = new \DateTime();

        return $this->createQueryBuilder('s')
        ->andWhere('s.date_debut < :val')
        ->andWhere(':val > s.date_fin')
        ->setParameter('val', $now)
        ->orderBy('s.date_debut', 'ASC')
        ->getQuery()
        ->getResult();
    }

//    /**
//     * @return Session[] Returns an array of Session objects
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

//    public function findOneBySomeField($value): ?Session
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
