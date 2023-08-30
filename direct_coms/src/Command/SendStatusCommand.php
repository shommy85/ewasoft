<?php

namespace App\Command;

use App\Message\MessageEvent;
use App\Message\StatusUpdate;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

//TODO: Remove after testing
#[AsCommand(
    name: "app:send"
)]
class SendStatusCommand extends Command
{
    public function __construct(private readonly MessageBusInterface $messageBus, string $name = null)
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $status = "Message from User";

//        $this->messageBus->dispatch(
//            message: new StatusUpdate($status)
//        );

//        $this->messageBus->dispatch(new StatusUpdate($status), [
//            new AmqpStamp('message.received', AMQP_NOPARAM, []),
//        ]);

        $this->messageBus->dispatch(
            new MessageEvent('created', 1, 2, 'HELLO'),
            [new AmqpStamp('message.published')]
        );

        return Command::SUCCESS;
    }
}