<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Controller\SerachHistoryController;
use App\Repository\SearchHistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: SearchHistoryRepository::class)]
#[ApiResource(operations: [
    new Get(
        name: 'get_slogs', 
        uriTemplate: '/slog/{namespace}/{repository}', 
        controller: SerachHistoryController::class),
    new Get(
        name: 'get_slog', 
        uriTemplate: '/slog/{namespace}/{repository}/{tag_name}', 
        controller: SerachHistoryController::class),
])]
class SearchHistory implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $namespace = null;

    #[ORM\Column(length: 100)]
    private ?string $repository = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $tag_name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $search_date = null;

    public function jsonSerialize() {

        return [
            'namespace' => $this->namespace,
            'repository' => $this->repository,
            'tag_name' => $this->tag_name,
            'search_date' => $this->search_date
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): static
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function getRepository(): ?string
    {
        return $this->repository;
    }

    public function setRepository(string $repository): static
    {
        $this->repository = $repository;

        return $this;
    }

    public function getTagName(): ?string
    {
        return $this->tag_name;
    }

    public function setTagName(string $tag_name): static
    {
        $this->tag_name = $tag_name;

        return $this;
    }

    public function getSearchDate(): ?\DateTimeInterface
    {
        return $this->search_date;
    }

    public function setSearchDate(\DateTimeInterface $search_date): static
    {
        $this->search_date = $search_date;

        return $this;
    }
}
