<?php

namespace CodeWrong;

class Roll
{
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
        return $pins < self::ZERO or $pins > Game::MAX_PINS;
    }

    public function score()
    {
        return $this->pins;
    }
}
