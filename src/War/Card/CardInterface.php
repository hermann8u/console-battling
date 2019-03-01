<?php

namespace Game\War\Card;

interface CardInterface
{
    public function __toString(): string;
    public function getValue(): int;
}