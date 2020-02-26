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

    public function __construct(Context $context, string $playerOneName, string $playerTwoName, array $config = [])
    {
        $this->playerOne = new Player($playerOneName);
        $this->playerTwo = new Player($playerTwoName);

        $this->context = $context;
        $this->context->setPlayers($this->playerOne, $this->playerTwo);

        $this->config = $config + [
            'sleep' => true,
            'packageType' => Package::TYPE_CLASSIC,
        ];
    }

    public function launch(): void
    {
        $this->context->launchGame();

        $this->initPlayersCards($this->config['packageType']);

        $roundCount = 1;
        while ($this->playerOne->hasCards() && $this->playerTwo->hasCards()) {
            $this->context->launchRound($roundCount);
            $this->round();
            $roundCount++;

            if ($this->config['sleep']) {
                sleep(2);
            }
        }

        $winner = $this->playerOne->getCardsCount() > $this->playerTwo->getCardsCount()
            ? $this->playerOne
            : $this->playerTwo;

        $this->context->finishGame($winner, $roundCount);
    }

    private function initPlayersCards(string $type): void
    {
        $package = new Package($type);
        $split = $package->splitIntoTwo();

        $this->playerOne->addCards(...$split[0]);
        $this->playerTwo->addCards(...$split[1]);
    }

    private function round(): void
    {
        $this->cardsForWinner[] = $this->playerOne->drawCard();
        $this->cardsForWinner[] = $this->playerTwo->drawCard();

        $this->context->afterPlayersDraw();

        if ($this->playerOne->getCurrentCard()->getValue() === $this->playerTwo->getCurrentCard()->getValue()) {
            $this->declareWar();
        } else {
            $winner = $this->winner();
        }

        $this->context->finishRound($winner ?? null);
    }

    private function declareWar(): void
    {
        if ($this->playerOne->getCardsCount() > 1 && $this->playerTwo->getCardsCount() > 1) {
            $this->cardsForWinner[] = $this->playerOne->drawCard();
            $this->cardsForWinner[] = $this->playerTwo->drawCard();
        }

        if (!$this->playerOne->hasCards() || !$this->playerTwo->hasCards()) {
            $this->playerOne->addCards($this->playerOne->getCurrentCard());
            $this->playerTwo->addCards($this->playerTwo->getCurrentCard());

            $this->cardsForWinner = [];
        }
    }

    private function winner(): Player
    {
        $winner = $this->playerOne->getCurrentCard()->getValue() > $this->playerTwo->getCurrentCard()->getValue()
            ? $this->playerOne
            : $this->playerTwo;

        // Add cards to winner package
        $winner->addCards(...$this->cardsForWinner);

        $this->cardsForWinner = [];

        return $winner;
    }
}
