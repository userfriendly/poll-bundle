<?php

namespace Userfriendly\Bundle\PollBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Custom query repository for Poll related stuff
 */
class PollRepository extends EntityRepository
{
    /**
     * Get a limited collection of Poll entities ordered descending by date
     *
     * @param integer $limit
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getLatestPolls( $limit = 1 )
    {
        return $this->qbLatestPolls( $limit )->getQuery()->getResult();
    }

    /**
     * Query:
     *
     * Get a limited collection of Poll entities ordered descending by date
     *
     * @param integer $limit
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function qbLatestPolls( $limit = 1 )
    {
        $qb = $this->createQueryBuilder( 'p' );
        $qb->orderBy( 'p.createdAt', 'desc' )
           ->setMaxResults( $limit );
        return $qb;
    }
}
