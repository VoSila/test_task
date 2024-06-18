<?php

namespace App\MessageHandler;

use App\Entity\TableA;
use App\Entity\TableB;
use App\Message\TransferTableMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class TransferTableHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function __invoke(TransferTableMessage $message): void
    {
        $tableA = $this->findTableA($message->getContent());
        $tableB = $this->createTableBFromTableA($tableA);
        $this->saveTableB($tableB);
        $this->deleteTableA($tableA);
    }

    private function findTableA($tableAId)
    {
        $tableARepository = $this->entityManager->getRepository(TableA::class);
        return $tableARepository->find($tableAId);
    }

    private function createTableBFromTableA(TableA $tableA): TableB
    {
        $tableB = new TableB();
        $tableB->setSurname($tableA->getSurname());
        $tableB->setEmail($tableA->getEmail());
        $tableB->setDate($tableA->getDate());

        return $tableB;
    }

    private function saveTableB(TableB $tableB): void
    {
        $this->entityManager->persist($tableB);
        $this->entityManager->flush();
    }

    private function deleteTableA(TableA $tableA): void
    {
        $this->entityManager->remove($tableA);
        $this->entityManager->flush();
    }
}
