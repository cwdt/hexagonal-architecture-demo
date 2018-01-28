<?php

namespace PTF\Infrastructure\Persistence\Doctrine;

use DateTime;
use Doctrine\ORM\EntityRepository;
use PTF\Domain\Status\Status;
use PTF\Domain\Status\StatusRepository as StatusRepositoryInterface;

class StatusRepository extends EntityRepository implements StatusRepositoryInterface
{
    public function save(Status $status): void
    {
        $this->getEntityManager()->persist($status);
        $this->getEntityManager()->flush($status);
    }

    public function byDateTime(DateTime $dateTime): ?Status
    {
        return $this->createQueryBuilder('S')
            ->where('S.from <= :datetime AND S.until >= :datetime')
            ->orderBy('S.createdAt', 'DESC')
            ->setMaxResults(1)
            ->setParameters([
                'datetime' => $dateTime,
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }
}