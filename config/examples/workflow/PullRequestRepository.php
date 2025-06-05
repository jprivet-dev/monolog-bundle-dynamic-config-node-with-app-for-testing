<?php

namespace App\Entity;

namespace App\Repository;

use App\Entity\PullRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Copy this file in app/src/Repository
 *
 * @extends ServiceEntityRepository<PullRequest>
 */
class PullRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PullRequest::class);
    }
}
