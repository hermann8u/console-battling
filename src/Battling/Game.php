<?php

namespace Game\Battling;

use Game\Battling\Card\CardInterface;
use Game\Battling\Context\ContextInterface;
use Game\Battling\Package\Package;

class Game
{
    /**
     * @var ContextInterface
     */
    private $context;

    /**
     * @var Player
     */
    private $playerOne;

    /**
     * @var Player
     */
    private $playerTwo;

    /**
     * @var array
     */
    private $config;

    /**
     * @var CardInterface[]
     */
    private $cardsForWinner;

    public function __construct(ContextInterface $context, string $playerOneName, string $playerTwoName, array $config = [])
    {
        $this->playerOne = (new Player())->setName($playerOneName);
        $this->playerTwo = (new Player())->setName($playerTwoName);

        $this->context = $context->setPlayers($this->playerOne, $this->playerTwo);

        $defaultConfig = [
            'sleep' => true,
            'discard' => false,
            'packageType' => 'classic'
        ];

        $this->config = array_merge($defaultConfig, $config);
    }

    public function launch()
    {
        $this->context->launchGame();

        $this->initPlayersCards($this->config['packageType']);

        $roundCount = 1;
        while ($this->playerOne->hasCards() && $this->playerTwo->hasCards()) {
            $this->context->launchRound($roundCount);
            $this->round();
            $roundCount++;

            if ($this->config['sleep']) {
                sleep(1);
            }
        }

        if ($this->playerOne->getScore() === $this->playerTwo->getScore()) {
            $winner = null;
        } else {
            $winner = $this->playerOne->getScore() > $this->playerTwo->getScore() ? $this->playerOne : $this->playerTwo;
        }

        $this->context->finishGame($winner);
    }

    private function initPlayersCards(string $type)
    {
        $package = new Package($type);
        $split = array_chunk($package->getCards(), count($package->getCards()) / 2);

        $this->playerOne->setCards($split[0]);
        $this->playerTwo->setCards($split[1]);
    }

    private function round()
    {
        $winner = null;

        $this->cardsForWinner[] = $this->playerOne->drawCard();
        $this->cardsForWinner[] = $this->playerTwo->drawCard();

        $this->context->afterPlayersDraw();

        if ($this->playerOne->getCurrentCard()->getValue() === $this->playerTwo->getCurrentCard()->getValue()) {
            $this->equality();
        } else {
            $winner = $this->winner();
        }

        $this->context->finishRound($winner);
    }

    private function equality()
    {
        if (count($this->playerOne->getCards()) > 1 && count($this->playerTwo->getCards()) > 1) {
            $this->cardsForWinner[] = $this->playerOne->drawCard();
            $this->cardsForWinner[] = $this->playerTwo->drawCard();
        }

        if (!$this->playerOne->hasCards() || !$this->playerTwo->hasCards()) {
            $this->playerOne->addCard($this->playerOne->getCurrentCard());
            $this->playerTwo->addCard($this->playerTwo->getCurrentCard());

            $this->cardsForWinner = [];
        }
    }

    private function winner()
    {
        $winner = $this->playerOne->getCurrentCard()->getValue() > $this->playerTwo->getCurrentCard()->getValue() ?
            $this->playerOne :
            $this->playerTwo;

        $winner->incrementScore();

        // Add cards to winner package
        if (!$this->config['discard']) {
            shuffle($this->cardsForWinner);
            foreach ($this->cardsForWinner as $card) {
                $winner->addCard($card);
            }

            $this->cardsForWinner = [];
        }

        return $winner;
    }
}