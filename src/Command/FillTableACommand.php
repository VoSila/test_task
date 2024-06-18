<?php

namespace App\Command;

use App\Entity\TableA;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:fill-table')]
class FillTableACommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputOutput = new SymfonyStyle($input, $output);

        $jsonFile = 'public/tableData/testTableData.json' ;
        $jsonData = file_get_contents($jsonFile);
        $users = json_decode($jsonData, true);

        foreach ($users as $userData) {
            $tableA = new TableA();
            $tableA->setName($userData['name']);
            $tableA->setSurname($userData['surname']);
            $tableA->setEmail($userData['email']);
            $tableA->setType($userData['type']);
            $tableA->setDescription($userData['description']);
            $date = new \DateTime($userData['date']);
            $tableA->setDate($date);

            $this->entityManager->persist($tableA);
        }

        $this->entityManager->flush();
        $inputOutput->success('Table A filled with random data.');

        return Command::SUCCESS;
    }
}
