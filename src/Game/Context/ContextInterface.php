<?php

namespace WarCardGame\Game\Context;

use WarCardGame\Game\Player;

interface ContextInterface
{
    public function setPlayers(Player $playerOne, Player $playerTwo): self;
    public function launchGame();
    public function launchRound(int $roundNumber);
    public function afterPlayersDraw();
    public function finishRound(?Player $winner);
    public function finishGame(?Player $winner);
}