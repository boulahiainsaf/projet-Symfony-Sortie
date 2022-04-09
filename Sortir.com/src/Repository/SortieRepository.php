<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Sortie $entity, bool $flush = true): void
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
    public function remove(Sortie $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getSortiesByCampus(Campus $campus){
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder->join('s.etat', 'e')
            ->addSelect('e');
        $queryBuilder->leftJoin('s.participants', 'p')
            ->addSelect('p');
        $queryBuilder->andWhere('s.campus = :campus');
        $queryBuilder->setParameter(':campus', $campus->getId());
        $queryBuilder->orderBy('s.dateHeureDebut', 'ASC');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
    // get all Sorties from User Campus but Passées
    public function getAllFromUserCampus(Participant $connectedUser)
    {
        $queryBuilder = $this->createQueryBuilder('s');
       $queryBuilder->join('s.etat', 'e')
            ->addSelect('e');
        $queryBuilder->leftJoin('s.participants', 'p')
            ->addSelect('p');
        $queryBuilder->andWhere('s.campus = '.$connectedUser->getCampus()->getId());
        $queryBuilder->andWhere($queryBuilder->expr()->notIn('e.libelle', ':passee'));
        $queryBuilder->setParameter(':passee', 'Passée');
        $queryBuilder->andWhere($queryBuilder->expr()->notIn('e.libelle', ':historisee'));
        $queryBuilder->setParameter(':historisee', 'Historisée');
        $queryBuilder->orderBy('s.dateHeureDebut', 'ASC');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    public function getFilteredSorties($data, Participant $connectedUser)
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder->join('s.etat', 'e')
            ->addSelect('e');
         $queryBuilder->leftJoin('s.participants', 'p')
            ->addSelect('p');
        $queryBuilder->andWhere($queryBuilder->expr()->notIn('e.libelle', ':historisee'));
        $queryBuilder->setParameter(':historisee', 'Historisée');
        if($data->getCampus()) {
            $queryBuilder->andWhere('s.campus = :campus');
            $queryBuilder->setParameter(':campus', $data->getCampus());
        }
        if($data->getNomSortie()){
            $queryBuilder->andWhere('s.nom LIKE :nameSortie');
            $queryBuilder->setParameter(':nameSortie', '%'.$data->getNomSortie().'%' );
        }
        if($data->getDateDebut()){
            $queryBuilder->andWhere('s.dateHeureDebut >= :dateFrom');
            $queryBuilder->setParameter(':dateFrom', $data->getDateDebut() );
        }
        if($data->getDateFin()){
            $queryBuilder->andWhere('s.dateHeureDebut <= :dateTo');
            $queryBuilder->setParameter(':dateTo', $data->getDateFin());
        }
        if($data->isSortiesOrganises()===true){
            $queryBuilder->andWhere('s.organisateur = :organisator');
            $queryBuilder->setParameter(':organisator', $connectedUser);
        }
        // MANY TO MANY !!! member of
        if($data->isSortiesInscrites()===true){
            $queryBuilder->andWhere(':userInscrit member of s.participants');
            $queryBuilder->setParameter(':userInscrit', $connectedUser);
        }
        if($data->isSortiesPasInscrites()===true){
            $queryBuilder->andWhere(':userPasInscrit not member of s.participants');
            $queryBuilder->setParameter(':userPasInscrit', $connectedUser);
        }

        if($data->isSortiesPassees()===true){
            $queryBuilder->andWhere('e.libelle = :etat');
            $queryBuilder->setParameter(':etat', 'Passée');
        }

        $queryBuilder->orderBy('s.dateHeureDebut', 'ASC');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    // Récuperer sorties inscrites pour un utilisateur
    public function getUserSortiesAdmission(Participant $connectedUser)
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder->join('s.etat', 'e')
            ->addSelect('e');
        $queryBuilder->leftJoin('s.participants', 'p')
            ->addSelect('p');
        $queryBuilder->andWhere($queryBuilder->expr()->notIn('e.libelle', ':cree'));
        $queryBuilder->setParameter(':cree', Etat::CREE);
        $queryBuilder->andWhere($queryBuilder->expr()->notIn('e.libelle', ':historisee'));
        $queryBuilder->setParameter(':historisee', Etat::HISTORISEE);
        $queryBuilder->andWhere(':userInscrit member of s.participants');
        $queryBuilder->setParameter(':userInscrit', $connectedUser);
        $queryBuilder->orderBy('s.dateHeureDebut', 'ASC');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

}
