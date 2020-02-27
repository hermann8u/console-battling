<?php

namespace WarCardGame\Game;

use WarCardGame\Game\Card\Card;
use WarCardGame\Game\Context\Context;
use WarCardGame\Game\Package\Package;

class Game
{
    /** @var Context */
    private $context;

    /** @var Player */
    private $playerOne;

    /** @var Player */
    private $playerTwo;

    /** @var array */
    private $config;

    /** @var Card[] */
    private $cardsForWinner;

    /** @var Round[] */
    private $rounds;

    public function __construct(Context $context, string $playerOneName, string $playerTwoName, array $config = [])
    {
        $this->playerOne = new Player($playerOneName);
        $this->playerTwo = new Player($playerTwoName);

        $this->context = $context;
        $this->context->setPlayers($this->playerOne, $this->playerTwo);

        $this->rounds = [];
        $this->cardsForWinner = [];

        $this->config = $config + [
            'sleep' => true,
            'packageType' => Package::TYPE_CLASSIC,
        ];
    }

    public function launch(): void
    {
        $this->context->launchGame();

        $this->initPlayersCards($this->config['packageType']);

        while ($this->playerOne->hasCards() && $this->playerTwo->hasCards()) {
            $round = new Round($this->context, count($this->rounds) + 1, $this->playerOne, $this->playerTwo, $this->cardsForWinner);
            $this->rounds[] = $round;

            $this->cardsForWinner = $round->process();

            if ($this->config['sleep']) {
                sleep(2);
            }
        }

        $winner = $this->playerOne->hasCards()
            ? $this->playerOne
            : $this->playerTwo;

        $this->context->finishGame($winner, $this->rounds);
    }

    private function initPlayersCards(string $type): void
    {
        $package = new Package($type);
        $split = $package->splitIntoTwo();

        $this->playerOne->addCards(...$split[0]);
        $this->playerTwo->addCards(...$split[1]);
    }
}
