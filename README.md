# War card game

A war card game written in PHP

> You can show this as an exercise or a toy project.
> Its goals is to have a functional game, to implement designs patterns and separation of concerns and to follow best practices.

## Story behind this project

My friend Quentin had to do an exercise for a job interview about a war card game in the console with PHP. Later, he asked me to check its code. We spent an afternoon refactoring the code and upgrade it together. That's how this project is born.

The initials constraints were as follows :
- No external library
- The game should be executable only from the console
- 52 cards represented by a number between 1 and 52 included
- Each player draw a card. The player with the highest card number win the round and score a point.
- The game continues until one of the two players has no more cards
- The winner name is displayed

## How the constraints have evolved

Today the project totally changed. We implement this functionality :
- Composer and the Symfony console component to run the game
- A representation of a real 52 cards package
- The real game in its full design. The game finish when one of the two player has all the cards
- A war is declared when the drawn cards have the same value
- Different options of configuration (sleep between round, package type, discard or win the cards from the round).

## How to run it

You can run this project with the following steps :
- Clone or download the code
- Open a console and go the root directory of this project
- Install the dependencies with composer
- Execute it with "php run" command

## What's next

You can see here a list of the possible evolutions of this project :
- Adding translations (at least in english for the game messages)
- Factory pattern design for Package creation
- Observer or Mediator pattern design? (instead of the current ContextInterface)
- Brings it to the browser with Web sockets
