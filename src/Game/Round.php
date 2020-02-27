<?php

namespace WarCardGame\Game;

use WarCardGame\Game\Card\Card;
use WarCardGame\Game\Context\Context;

class Round
{
    /** @var Context */
    private $context;

    /** @var int */
    private $number;

    /** @var Player */
    private $playerOne;

    /** @var Player */
    private $playerTwo;

    /** @var Player|null */
    private $winner;

    /** @var Card[] */
    private $cardsForWinner;

    public function __construct(Context $context, int $number, Player $playerOne, Player $playerTwo, array $cardsForWinner)
    {
        $this->context = $context;
        $this->number = $number;
        $this->playerOne = $playerOne;
        $this->playerTwo = $playerTwo;
        $this->cardsForWinner = $cardsForWinner;
    }

    public function process(): array
    {
        $this->context->launchRound($this->number);

        $this->cardsForWinner[] = $this->playerOne->drawCard();
        $this->cardsForWinner[] = $this->playerTwo->drawCard();

        $this->context->afterPlayersDraw();

        if ($this->playerOne->getCurrentCard()->getValue() === $this->playerTwo->getCurrentCard()->getValue()) {
            $this->declareWar();
        } else {
            $this->determineWinner();
        }

        $this->context->finishRound($this->winner);

        return $this->cardsForWinner;
    }

    public function getWinner(): ?Player
    {
        return $this->winner;
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

    private function determineWinner(): void
    {
        $this->winner = $this->playerOne->getCurrentCard()->getValue() > $this->playerTwo->getCurrentCard()->getValue()
            ? $this->playerOne
            : $this->playerTwo;

        // Add cards to winner package
        $this->winner->addCards(...$this->cardsForWinner);

        $this->cardsForWinner = [];
    }
}
