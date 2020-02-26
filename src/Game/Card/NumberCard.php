<?php

namespace WarCardGame\Game\Card;

class NumberCard implements Card
{
    /** @var int */
    private $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getColor(): ?string
    {
        return null;
    }
}
