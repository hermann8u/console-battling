<?php

namespace WarCardGame;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use WarCardGame\Game\Context\ConsoleContext;
use WarCardGame\Game\Game;

class WarCommand extends Command
{
    protected static $defaultName = 'war';

    /** @var StyleInterface */
    private $io;

    /** @var string */
    private $playerOneName;

    /** @var string */
    private $playerTwoName;

    protected function configure(): void
    {
        $this
            ->setDescription('This command launch a game')
            ->addOption('numeric', null, InputOption::VALUE_NONE, 'Change the type of package to numeric')
            ->addOption('no-sleep', 's', InputOption::VALUE_NONE, 'Remove the sleep')
            ->addOption('dark-mode', 'd', InputOption::VALUE_NONE, 'Indicate that your console has a light background')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $this->playerOneName = $this->io->ask('Quel est le nom du premier joueur ?', 'Florian');
        $this->playerTwoName = $this->io->ask('Quel est le nom du second joueur ?', 'Quentin');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $context = new ConsoleContext($this->io, $input->getOption('dark-mode'));
        $game = new Game($context, $this->playerOneName, $this->playerTwoName, [
            'sleep' => !$input->getOption('no-sleep'),
            'packageType' => $input->getOption('numeric') ? 'numeric' : 'classic'
        ]);

        $game->launch();

        return 0;
    }
}
