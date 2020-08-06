<?php
namespace AppBundle\Repository;

use AppBundle\Entity\ItemsPerDay;
use Doctrine\ORM\EntityRepository;

class ItemsPerDayRepository extends EntityRepository
{
    public function mostSoldItems($day) {
        return $this->createQueryBuilder('ipd')
            ->where('ipd.date = :date')
            ->setParameter('date', $day)
            ->orderBy('ipd.quantity', 'desc')
            ->getQuery()->setMaxResults(50)->getResult();
    }
}