<?php

namespace WarCardGame\Game;

use WarCardGame\Game\Card\Card;

class Player
{
    /** @var string */
    private $name;

    /** @var Card[] */
    private $cards;

    /** @var Card */
    private $currentCard;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->cards = [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addCards(Card ...$cards)
    {
        if (!$cards) {
            return;
        }

        shuffle($cards);
        foreach ($cards as $card) {
            $this->cards[] = $card;
        }
    }

    public function getCardsCount(): int
    {
        return count($this->cards);
    }

    public function hasCards(): bool
    {
        return $this->getCardsCount() > 0;
    }

    public function getCurrentCard(): ?Card
    {
        return $this->currentCard;
    }

    public function drawCard(): ?Card
    {
        return $this->currentCard = array_shift($this->cards);
    }
}
