<?php

namespace App\Controller;

use App\Entity\SearchHistory;
use App\Repository\SearchHistoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SerachHistoryController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em) 
    {
        
    }

    #[Route('/slog/{namespace}/{repository}', name: 'get_slogs')]
    public function getLogs(string $namespace, string $repository): Response
    {
        /** @var SearchHistoryRepository */
        $searchRepo = $this->em->getRepository(SearchHistory::class);
        $logs = $searchRepo->findBy(['namespace' => $namespace, 'repository' => $repository, 'tag_name' => null]);

        if(!$logs) return new Response('', 204);
        else return new JsonResponse($logs);
    }

    #[Route('/slog/{namespace}/{repository}/{tag_name}', name: 'get_slog')]
    public function getLog(string $namespace, string $repository, string $tag_name): Response
    {
        /** @var SearchHistoryRepository */
        $searchRepo = $this->em->getRepository(SearchHistory::class);
        $logs = $searchRepo->findBy(['namespace' => $namespace, 'repository' => $repository, 'tag_name' => $tag_name]);

        if(!$logs) return new Response('', 204);
        else return new JsonResponse($logs);
    }
}