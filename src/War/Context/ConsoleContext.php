<?php

namespace Game\War\Context;

use Game\War\Player;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConsoleContext implements ContextInterface
{
    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * @var Player
     */
    private $playerOne;

    /**
     * @var Player
     */
    private $playerTwo;

    public function __construct(SymfonyStyle $io)
    {
        $this->io = $io;
    }

    public function setPlayers(Player $playerOne, Player $playerTwo): ContextInterface
    {
        $this->playerOne = $playerOne;
        $this->playerTwo = $playerTwo;

        return $this;
    }

    public function launchGame()
    {
        $this->io->section('La partie commence !');
    }

    public function launchRound(int $roundNumber)
    {
        $this->io->section('Round '.$roundNumber);
    }

    public function afterPlayersDraw()
    {
        $this->io->table([
            'Joueur',
            'Carte',
            'Cartes restantes',
        ], [
            [$this->playerOne->getName(), $this->playerOne->getCurrentCard(), count($this->playerOne->getCards())],
            [$this->playerTwo->getName(), $this->playerTwo->getCurrentCard(), count($this->playerTwo->getCards())]
        ]);
    }

    public function finishRound(?Player $winner)
    {
        $this->io->comment($winner ? $winner->getName().' gagne le round' : 'Bataille !');
    }

    public function finishGame(?Player $winner)
    {
        $this->io->success($winner ? $winner->getName().' gagne la partie avec '.$winner->getScore().' points !' : 'C\'est une égalité !');
    }
}