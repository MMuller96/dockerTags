<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Service\DockerHubService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TagController extends AbstractController
{
    public function __construct(
        private DockerHubService $dockerHubService,
        private EntityManagerInterface $em) 
    {
        
    }

    #[Route('/tag/{namespace}/{repository}', name: 'get_tags')]
    public function getTags(string $namespace, string $repository): Response
    {   
        /** @var TagRepository */
        $tagRepo = $this->em->getRepository(Tag::class);
        $tags = $tagRepo->findBy(['name' => $namespace . '/' . $repository]);

        if(!$tags)
        {
            $this->dockerHubService->load($namespace, $repository);
            $tags = $tagRepo->findBy(['name' => $namespace . '/' . $repository]);
        }

        return new JsonResponse($tags);
    }

    #[Route('/tag/{namespace}/{repository}/{tag_name}', name: 'get_tag')]
    public function getTag(string $namespace, string $repository, string $tag_name): Response
    {   
        /** @var TagRepository */
        $tagRepo = $this->em->getRepository(Tag::class);
        $tags = $tagRepo->findOneBy(['name' => $namespace . '/' . $repository, 'tag_name' => $tag_name]);

        if(!$tags)
        {
            $this->dockerHubService->load($namespace, $repository);
            $tags = $tagRepo->findBy(['name' => $namespace . '/' . $repository]);
        }

        return new JsonResponse($tags);
    }
}
