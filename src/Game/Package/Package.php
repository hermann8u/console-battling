<?php

namespace WarCardGame\Game\Package;

use WarCardGame\Game\Card\Card;
use WarCardGame\Game\Card\ClassicCard;
use WarCardGame\Game\Card\NumberCard;

class Package
{
    public const TYPE_CLASSIC = 'classic';
    public const TYPE_NUMERIC = 'numeric';
    public const TYPES = [
        self::TYPE_CLASSIC,
        self::TYPE_NUMERIC,
    ];

    /** @var Card[] */
    private $cards;

    public function __construct(string $type = self::TYPE_CLASSIC)
    {
        $this->cards = [];

        if (!in_array($type, self::TYPES)) {
            throw new \InvalidArgumentException();
        }

        if ($type === self::TYPE_CLASSIC) {
            foreach (ClassicCard::getFamiliesName() as $family) {
                foreach (ClassicCard::CARDS_MAP as $name => $value) {
                    $this->cards[] = new ClassicCard($name, $family);
                }
            }
        } elseif ($type === self::TYPE_NUMERIC) {
            for ($i = 1; $i < 14; $i++) {
                $this->cards[] = new NumberCard($i);
                $this->cards[] = new NumberCard($i);
                $this->cards[] = new NumberCard($i);
                $this->cards[] = new NumberCard($i);
            }
        }

        shuffle($this->cards);
    }

    public function splitIntoTwo(): array
    {
        return array_chunk($this->cards, count($this->cards) / 2);
    }
}
