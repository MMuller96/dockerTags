<?php

namespace App\Service;

use App\Entity\SearchHistory;
use Doctrine\ORM\EntityManagerInterface;

class SearchLogService
{
    public function __construct(
        private EntityManagerInterface $em
    ){ }

    public function add(string $namespace, string $repository, string $tag_name = null)
    {
        $log = new SearchHistory();
        $log->setNamespace($namespace);
        $log->setRepository($repository);
        if($tag_name)$log->setTagName($tag_name);
        $log->setSearchDate(new \DateTime());

        $this->em->persist($log);
        $this->em->flush();
    }
}