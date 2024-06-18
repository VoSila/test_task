<?php

namespace App\Command;

use App\Message\TransferTableMessage;
use App\Repository\TableARepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'app:send-data',)]
class SendDataCommand extends Command
{
    public function __construct(
        private readonly TableARepository $tableARepository,
        private readonly MessageBusInterface $messageBus,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputOutput = new SymfonyStyle($input, $output);
        $tableARecords = $this->tableARepository->findAll();

        foreach ($tableARecords as $record) {
            $this->messageBus->dispatch(new TransferTableMessage($record->getId()));
        }

        $inputOutput->success('Data transfer initiated.');

        return Command::SUCCESS;
    }
}
