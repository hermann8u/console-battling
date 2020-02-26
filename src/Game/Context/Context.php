<?php

namespace WarCardGame\Game\Context;

use WarCardGame\Game\Player;

interface Context
{
    public function setPlayers(Player $playerOne, Player $playerTwo): void;
    public function launchGame(): void;
    public function launchRound(int $roundNumber): void;
    public function afterPlayersDraw(): void;
    public function finishRound(?Player $winner): void;
    public function finishGame(?Player $winner, int $roundsCount): void;
}
