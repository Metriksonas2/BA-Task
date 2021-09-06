<?php

namespace App\Repository;

use App\Entity\PhoneBookRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PhoneBookRecord|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhoneBookRecord|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhoneBookRecord[]    findAll()
 * @method PhoneBookRecord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhoneBookRecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PhoneBookRecord::class);
    }

    public function getCreatedPhoneBookRecords(int $userId)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.creator = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult()
        ;
    }
}
