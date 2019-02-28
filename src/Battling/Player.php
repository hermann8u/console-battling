<?php

namespace Game\Battling;

use Game\Battling\Card\CardInterface;

class Player
{
    const SCORE_STEP = 1;

    /**
     * @var string
     */
    private $name;

    /**
     * @var CardInterface[]
     */
    private $cards;

    /**
     * @var CardInterface
     */
    private $currentCard;

    /**
     * @var int
     */
    private $score;

    public function __construct()
    {
        $this->cards = [];
        $this->score = 0;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function setCards(array $cards): self
    {
        $this->cards = $cards;

        return $this;
    }

    public function addCard(CardInterface $card)
    {
        $this->cards[] = $card;
    }

    public function getCurrentCard(): ?CardInterface
    {
        return $this->currentCard;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function incrementScore()
    {
        $this->score += self::SCORE_STEP;
    }

    public function drawCard(): ?CardInterface
    {
        return $this->currentCard = array_shift($this->cards);
    }

    public function hasCards(): bool
    {
        return (bool) count($this->cards);
    }
}