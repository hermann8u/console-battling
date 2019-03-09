<?php

namespace WarCardGame\Game\Card;

interface CardInterface
{
    public function __toString(): string;
    public function getValue(): int;
    public function getColor(): ?string;
}