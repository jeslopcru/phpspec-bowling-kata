<?php

namespace CodeWrong;

class Roll
{
    const MAX_NUMBER_OF_PINS = 10;
    const ZERO = 0;
    protected $pins;

    public function __construct($pins)
    {
        $this->checkPins($pins);
        $this->pins = $pins;
    }

    private function checkPins($pins)
    {
        if ($this->isCorrectValue($pins)) {
            throw new \Exception("Incorrect value.");
        }
    }

    private function isCorrectValue($pins)
    {
        return $pins < self::ZERO or $pins > self::MAX_NUMBER_OF_PINS;
    }

    public function score()
    {
        return $this->pins;
    }
}
