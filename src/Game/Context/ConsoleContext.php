<?php

namespace WarCardGame\Game\Context;

use WarCardGame\Game\Card\CardInterface;
use WarCardGame\Game\Player;
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

    /**
     * @var bool Indicate that the console has a light background
     */
    private $lightMode;

    public function __construct(SymfonyStyle $io, bool $lightMode)
    {
        $this->io = $io;
        $this->lightMode = $lightMode;
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
            [$this->playerOne->getName(), $this->formatCard($this->playerOne->getCurrentCard()), count($this->playerOne->getCards())],
            [$this->playerTwo->getName(), $this->formatCard($this->playerTwo->getCurrentCard()), count($this->playerTwo->getCards())]
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

    private function formatCard(CardInterface $card)
    {
        $result = (string) $card;

        if ($color = $card->getColor()) {
            if ($this->lightMode && $color === 'black') {
                $color = 'white';
            }

            $result = '<fg='.$color.'>'.$result.'</>';
        }

        return $result;
    }
}