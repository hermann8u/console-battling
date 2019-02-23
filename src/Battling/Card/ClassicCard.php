<?php

namespace Game\Battling\Card;

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

    const FAMILIES = ['♥', '♦', '♠', '♣'];

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $family;

    public function __construct(string $name, string $family)
    {
        $this->setName($name);
        $this->setFamily($family);
    }

    public function __toString(): string
    {
        return '<fg='.$this->getColor().'>'.$this->family.' '.$this->name.'</>';
    }

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

    public function getFamily()
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
    private function setFamily(string $family): self
    {
        if (!in_array($family,self::FAMILIES)) {
            throw new \InvalidArgumentException();
        }

        $this->family = $family;

        return $this;
    }

    public function getValue(): int
    {
        return self::CARDS_MAP[$this->name];
    }

    public function getColor(): string
    {
        return in_array($this->family, ['♥', '♦']) ? 'red' : 'white';
    }
}