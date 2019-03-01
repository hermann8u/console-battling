<?php

namespace Game;

use Game\War\Context\ConsoleContext;
use Game\War\Game;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputOption;

class WarCommand extends Command
{
    protected static $defaultName = 'war';

    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * @var string
     */
    private $playerOneName;

    /**
     * @var string
     */
    private $playerTwoName;

    protected function configure()
    {
        $this
            ->setDescription('This command launch a War game between two players')
            ->addOption('number', null, InputOption::VALUE_NONE, 'Change the type of package to numeric (1 to 52)')
            ->addOption('discard', 'd', InputOption::VALUE_NONE, 'Discard the card after played')
            ->addOption('no-sleep', 's', InputOption::VALUE_NONE, 'Remove the sleep')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $this->playerOneName = $this->io->ask('Quel est le nom du premier joueur ?', 'Florian');
        $this->playerTwoName = $this->io->ask('Quel est le nom du second joueur ?', 'Quentin');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $context = new ConsoleContext($this->io);
        $game = new Game($context, $this->playerOneName, $this->playerTwoName, [
            'discard' => (bool) $input->getOption('discard'),
            'sleep' => (bool) !$input->getOption('no-sleep'),
            'packageType' => $input->getOption('number') ? 'number' : 'classic'
        ]);

        $game->launch();
    }
}