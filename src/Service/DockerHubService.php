<?php

namespace App\Service;

use App\Entity\Image;
use App\Entity\Tag;
use App\Repository\ImageRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class DockerHubService
{
    const URL = 'https://hub.docker.com/v2/namespaces/{namespace}/repositories/{repository}/tags';

    public function __construct(
        private HttpClientInterface $httpClient,
        private EntityManagerInterface $em
    ){ }

    public function load(string $namespace, string $repository): bool
    {
        $response = null;
        $data = null;

        $response = $this->httpClient->request('GET', str_replace(['{namespace}', '{repository}'], [$namespace, $repository], self::URL));

        if (200 !== $response->getStatusCode()) {
            return false;
        }

        if($response)$data = $response->toArray();

        if($data)
        {
            /** @var TagRepository */
            $tagRepo = $this->em->getRepository(Tag::class);

            /** @var ImageRepository */
            $imageRepo = $this->em->getRepository(Image::class);

            $currentTags = [];

            foreach($data['results'] as $rowTag)
            {
                $tagExists = true;
                $tag = $tagRepo->findOneBy(['name' => $namespace . '/' . $repository, 'tag_name' => $rowTag['name']]);
                $currentTags[] = $rowTag['name']; 

                if($tag == null) 
                {
                    $tag = new Tag();
                    $tagExists = false;
                }

                $tag->setName($namespace . '/' . $repository);
                $tag->setTagName($rowTag['name']);
                $tag->setStatus($rowTag['tag_status']);
                $tag->setLastUpdated($rowTag['last_updated']);

                $currentImages = [];

                foreach($rowTag['images'] as $rowImage)
                {
                    $currentImages[] = [
                        'architecture' => $rowImage['architecture'],
                        'os' => $rowImage['os']
                    ];

                    $image = null;
                    $imageExists = true;

                    if($tagExists)
                    {
                        $image = $imageRepo->findOneBy(['tag' => $tag->getId(), 'architecture' => $rowImage['architecture'], 'os' => $rowImage['os']]);
                    }

                    if($image == null)
                    {
                        $image = new Image();
                        $imageExists = false;
                    }

                    $image->setArchitecture($rowImage['architecture']);
                    $image->setOs($rowImage['os']);
                    $image->setStatus($rowImage['status']);
                    $image->setSize($rowImage['size'] > 0 ? $rowImage['size'] / 1024 / 1024 / 1024 : 0);

                    if(!$imageExists)$tag->addImage($image);
                }

                if($tagExists) $this->removeObsoleteImages($tag->getId(), $currentImages);
                else $this->em->persist($tag);
                $this->em->flush();
            }

            $this->removeObsoleteTags($namespace . '/' . $repository, $currentTags);
        }

        return true;
    }

    private function removeObsoleteTags(string $name, array $currentTags)
    {
        /** @var TagRepository */
        $tagRepo = $this->em->getRepository(Tag::class);

        $allTags = $tagRepo->findBy(['name' => $name]);

        foreach($allTags as $tag)
        {
            if(!in_array($tag->getTagName(), $currentTags))
            {
                $this->em->remove($tag);
            }
        }

        $this->em->flush();
    }

    private function removeObsoleteImages(int $tagId, array $currentImages)
    {
        /** @var ImageRepository */
        $imageRepo = $this->em->getRepository(Image::class);

        $allImages = $imageRepo->findBy(['tag' => $tagId]);

        foreach($allImages as $image)
        {
            $toDelete = true;

            foreach($currentImages as $current)
            {
                if($image->getArchitecture() == $current['architecture'] && $image->getOs() == $current['os'])
                {
                    $toDelete = false;
                    break;
                }
            }

            if($toDelete) $this->em->remove($image);
        }

        $this->em->flush();
    }
}