<?php

namespace WarCardGame\Game\Card;

class ClassicCard implements CardInterface
{
    const CARDS_MAP = [
        '2' => 2,
        '3' => 3,
        '4' => 4,
        '5' => 5,
        '6' => 6,
        '7' => 7,
        '8' => 8,
        '9' => 9,
        '10' => 10,
        'Valet' => 11,
        'Dame' => 12,
        'Roi' => 13,
        'As' => 14
    ];

    const FAMILIES = [
        'heart' => [
            'name' => 'Heart',
            'symbol' => '♥',
            'color' => 'red'
        ],
        'tiles' => [
            'name' => 'Tiles',
            'symbol' => '♦',
            'color' => 'red'
        ],
        'spades' => [
            'name' => 'Spades',
            'symbol' => '♠',
            'color' => 'black'
        ],
        'clover' => [
            'name' => 'Clover',
            'symbol' => '♣',
            'color' => 'black'
        ],
    ];

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $family;

    public function __construct(string $name, string $family)
    {
        $this->setName($name);
        $this->setFamilyByName($family);
    }

    public static function getFamiliesName(): array
    {
        return array_keys(self::FAMILIES);
    }

    public function __toString(): string
    {
        return $this->family['symbol'].' '.$this->name.' ';
    }

    /*public function __toString(): string
    {
        return '<fg='.$this->getColor().'>'.$this->family.' '.$this->name.'</>';
    }*/

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * This setter is private because we can't redefine the value later
     *
     * @param string $name
     *
     * @return ClassicCard
     */
    private function setName(string $name): self
    {
        if (!in_array($name, array_keys(self::CARDS_MAP))) {
            throw new \InvalidArgumentException();
        }

        $this->name = $name;

        return $this;
    }

    public function getFamily(): array
    {
        return $this->family;
    }

    /**
     * This setter is private because we can't redefine the value later
     *
     * @param string $family
     *
     * @return ClassicCard
     */
    private function setFamilyByName(string $family): self
    {
        if (!in_array($family, self::getFamiliesName())) {
            throw new \InvalidArgumentException();
        }

        $this->family = self::FAMILIES[$family];

        return $this;
    }

    public function getValue(): int
    {
        return self::CARDS_MAP[$this->name];
    }

    public function getColor(): ?string
    {
        return $this->family['color'];
    }
}