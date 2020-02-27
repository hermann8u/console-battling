<?php

namespace WarCardGame\Game\Context;

use Symfony\Component\Console\Style\StyleInterface;
use WarCardGame\Game\Card\Card;
use WarCardGame\Game\Player;

class ConsoleContext implements Context
{
    /** @var StyleInterface */
    private $io;

    /** @var Player */
    private $playerOne;

    /** @var Player */
    private $playerTwo;

    /** @var bool Indicate that the console has a dark background */
    private $darkMode;

    public function __construct(StyleInterface $io, bool $lightMode)
    {
        $this->io = $io;
        $this->darkMode = $lightMode;
    }

    public function setPlayers(Player $playerOne, Player $playerTwo): void
    {
        $this->playerOne = $playerOne;
        $this->playerTwo = $playerTwo;
    }

    public function launchGame(): void
    {
        $this->io->section('La partie commence !');
    }

    public function launchRound(int $roundNumber): void
    {
        $this->io->section('Round '.$roundNumber);
    }

    public function afterPlayersDraw(): void
    {
        $this->io->table([
            'Joueur',
            'Carte',
            'Cartes restantes',
        ], [
            [$this->playerOne->getName(), $this->formatCard($this->playerOne->getCurrentCard()), $this->playerOne->getCardsCount()],
            [$this->playerTwo->getName(), $this->formatCard($this->playerTwo->getCurrentCard()), $this->playerTwo->getCardsCount()]
        ]);
    }

    public function finishRound(?Player $winner): void
    {
        $this->io->comment($winner ? $winner->getName().' gagne le round' : 'Bataille !');
    }

    public function finishGame(?Player $winner, array $rounds): void
    {
        $this->io->success(($winner ? $winner->getName().' gagne la partie !' : 'C\'est une égalité !').' La partie a durée '.count($rounds).' rounds.');
    }

    private function formatCard(Card $card): string
    {
        $result = (string) $card;

        if ($color = $card->getColor()) {
            if ($this->darkMode && $color === 'black') {
                $color = 'white';
            }

            $result = sprintf('<fg=%s>%s</>', $color, $result);
        }

        return $result;
    }
}
