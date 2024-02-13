<?php

namespace App\Command;

use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Service\DockerHubService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:docker-hub-import',
    description: 'update data from Docker Hub Api'
)]
class TagCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private DockerHubService $dockerHubService,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /** @var TagRepository */
        $tagRepo = $this->em->getRepository(Tag::class);
        $tags = $tagRepo->getDistinctNames();

        foreach($tags as $tag)
        {
            $nr = explode('/', $tag['name']);
            if($nr && count($nr) == 2)$this->dockerHubService->load($nr[0], $nr[1]);
        }

        $io->success('DONE');
        return Command::SUCCESS;
    }
}