<?php

namespace Game\War\Card;

class NumberCard implements CardInterface
{
    /**
     * @var int
     */
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
}