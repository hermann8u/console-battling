<?php

namespace Game;

use Game\Battling\Card\CardInterface;
use Game\Battling\Package;
use Game\Battling\Player;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputOption;

class BattlingCommand extends Command
{
    protected static $defaultName = 'battling';

    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * @var Player
     */
    private $playerOne;

    /**
     * @var Player
     */
    private $playerTwo;

    /**
     * @var CardInterface[]
     */
    private $cardsForWinner = [];

    /**
     * @var bool Indicate that the cards will be re-add to the winner package
     */
    private $hasDiscard;

    /**
     * @var bool
     */
    private $hasSleep;

    protected function configure()
    {
        $this
            ->setDescription('This command launch a Battling game between two players')
            ->addOption('number', null, InputOption::VALUE_NONE, 'Change the type of package to numeric (1 to 52)')
            ->addOption('discard', 'd', InputOption::VALUE_NONE, 'Discard the card after played')
            ->addOption('no-sleep', 's', InputOption::VALUE_NONE, 'Remove the sleep')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $playerOneName = $this->io->ask('Quel est le nom du premier joueur ?', 'Florian');
        $playerTwoName = $this->io->ask('Quel est le nom du second joueur ?', 'Quentin');

        $this->playerOne = (new Player())->setName($playerOneName);
        $this->playerTwo = (new Player())->setName($playerTwoName);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io->section('La partie commence !');

        $this->hasDiscard = $input->getOption('discard');
        $this->hasSleep = !$input->getOption('no-sleep');

        $packageType = $input->getOption('number') ? 'number' : 'classic';

        $this->initPlayersCards($packageType);

        $roundCount = 1;
        while ($this->playerOne->hasCards() && $this->playerTwo->hasCards()) {
            $this->io->section('Round '.$roundCount);
            $this->round();
            $this->io->block('');
            $roundCount++;

            if ($this->hasSleep) {
                sleep(1);
            }
        }

        $this->displayResult();
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
        $playerOneCard = $this->playerOne->drawCard();
        $playerTwoCard = $this->playerTwo->drawCard();

        $this->cardsForWinner[] = $playerOneCard;
        $this->cardsForWinner[] = $playerTwoCard;

        if ($playerOneCard->getValue() === $playerTwoCard->getValue()) {
            $winner = null;
            if (count($this->playerOne->getCards()) > 1 && count($this->playerTwo->getCards()) > 1) {
                $this->cardsForWinner[] = $this->playerOne->drawCard();
                $this->cardsForWinner[] = $this->playerTwo->drawCard();
            }

            if (!$this->playerOne->hasCards() || !$this->playerTwo->hasCards()) {
                $this->playerOne->addCard($playerOneCard);
                $this->playerTwo->addCard($playerTwoCard);

                $this->cardsForWinner = [];
            }
        } else {
            $winner = $playerOneCard->getValue() > $playerTwoCard->getValue() ? $this->playerOne : $this->playerTwo;

            // Add cards to winner package
            if (!$this->hasDiscard) {
                shuffle($this->cardsForWinner);
                foreach ($this->cardsForWinner as $card) {
                    $winner->addCard($card);
                }

                $this->cardsForWinner = [];
            }
        }

        $this->resumeScores($playerOneCard, $playerTwoCard);
        $this->winRound($winner);
    }

    private function winRound(?Player $player)
    {
        if ($player) {
            $this->io->comment($player->getName().' gagne le round');

            $player->incrementScore();
        } else {
            $this->io->comment('Bataille !');

            if ($this->hasSleep) {
                sleep(1);
            }
        }
    }

    private function resumeScores(CardInterface $playerOneCard, CardInterface $playerTwoCard)
    {
        $this->io->table([
            'Joueur',
            'Carte',
            'Cartes restantes',
        ], [
            [$this->playerOne->getName(), $playerOneCard, count($this->playerOne->getCards())],
            [$this->playerTwo->getName(), $playerTwoCard, count($this->playerTwo->getCards())]
        ]);
    }

    private function displayResult()
    {
        if ($this->playerOne->getScore() === $this->playerTwo->getScore()) {
            $this->io->success('C\'est une égalité !');

            return;
        }

        $winner = $this->playerOne->getScore() > $this->playerTwo->getScore() ? $this->playerOne : $this->playerTwo;
        $this->io->success($winner->getName().' gagne la partie avec '.$winner->getScore().' points !');
    }
}