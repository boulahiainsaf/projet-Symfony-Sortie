<?php

namespace App\Repository;

use App\Entity\Participant;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method Participant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participant[]    findAll()
 * @method Participant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participant::class);
    }

    public function findByEmail(string $email): ?Participant
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.email= :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();

    }

    /*
   public function findOneBySomeField($value): ?Participant
   {
       return $this->createQueryBuilder('p')
           ->andWhere('p.exampleField = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }
   */

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Participant $entity, bool $flush = true): void
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
    public function remove(Participant $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Participant) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setMotPasse($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return Participant[] Returns an array of Participant objects
    //  */
    /*
    public function findByExampleField($email)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Participant
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function loadUserByIdentifier(string $usernameOrEmail): ?Participant
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT u
                FROM App\Entity\Participant u
                WHERE u.pseudo = :query
                OR u.email = :query'
        )
            ->setParameter('query', $usernameOrEmail)
            ->getOneOrNullResult();
    }

    /** @deprecated since Symfony 5.3 */
    public function loadUserByUsername(string $usernameOrEmail): ?Participant
    {
        return $this->loadUserByIdentifier($usernameOrEmail);
    }
    /* rechercher participant par nom, prénom, email ou pseudo */
    public function getSearchUsers($data)
    {
        $queryBuilder = $this->createQueryBuilder('u');
        if($data->getNomParticipant()){
            $queryBuilder->andWhere('u.nom LIKE :searchName OR u.prenom LIKE :searchName OR u.email LIKE :searchName OR u.pseudo LIKE :searchName');
            $queryBuilder->setParameter(':searchName', '%'.$data->getNomParticipant().'%' );
        }
        $queryBuilder->orderBy('u.nom', 'ASC');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
}
