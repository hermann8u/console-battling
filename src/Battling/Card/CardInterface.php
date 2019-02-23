<?php

namespace Game\Battling\Card;

interface CardInterface
{
    public function __toString(): string;
    public function getValue(): int;
}