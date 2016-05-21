<?php

namespace CodeWrong;

class Frame
{
    const ROLL_MISSING = 'roll_missing';
    const OPEN = 'open';
    const SPARE = 'spare';
    const STRIKE = 'strike';
    const FIRST = 1;
    const SECOND = 2;

    protected $rolls = [];

    public function roll($pins)
    {
        $this->checkNextRollAllowed();
        $this->checkToManyPins($pins);

        $this->rolls[] = new Roll($pins);
    }

    private function checkNextRollAllowed()
    {
        if ($this->isMaxPins() or $this->isLastRolls()) {
            throw new \Exception("No rolls allowed.");
        }
    }

    private function isMaxPins()
    {
        return $this->score() == Game::MAX_PINS;
    }

    public function score()
    {
        $score = 0;
        foreach ($this->rolls as $roll) {
            $score += $roll->score();
        }

        return $score;
    }

    private function isLastRolls()
    {
        return sizeof($this->rolls) == Game::MAX_ROLLS;
    }

    private function checkToManyPins($pins)
    {
        if ($this->score() + $pins > Game::MAX_PINS) {
            throw new \Exception("Too many pins.");
        }
    }

    public function state()
    {
        if ($this->isRollMissing()) {
            return self::ROLL_MISSING;
        }

        if ($this->isStrike()) {
            return self::STRIKE;
        }

        if ($this->isSpare()) {
            return self::SPARE;
        }

        if ($this->isOpen()) {
            return self::OPEN;
        }
        return null;
    }

    private function isRollMissing()
    {
        return $this->isFirst() and $this->thereArePins();
    }

    private function isFirst()
    {
        return sizeof($this->rolls) == self::FIRST;
    }

    private function thereArePins()
    {
        return $this->score() < Game::MAX_PINS;
    }

    private function isStrike()
    {
        return $this->isFirst() and $this->isMaxPins();
    }

    private function isSpare()
    {
        return $this->isLastRolls() and $this->isMaxPins();
    }

    private function isOpen()
    {
        return $this->isLastRolls() and $this->thereArePins();
    }

    public function allowsRoll()
    {
        $isAllowRoll = true;
        if ($this->isMaxPins() or $this->isLastRolls()) {
            $isAllowRoll = false;
        }
        return $isAllowRoll;
    }

    public function firstRoll()
    {
        $this->checkEmptyFrame();

        return $this->calculateScore(self::FIRST);
    }

    private function checkEmptyFrame()
    {
        if (sizeof($this->rolls) == 0) {
            throw new \Exception("Empty frame.");
        }
    }

    private function calculateScore($attempt)
    {
        $correctedAttempt = $attempt - 1;
        return $this->rolls[$correctedAttempt]->score();
    }

    public function secondRoll()
    {
        $this->checkMaxRollsPerFrame();

        return $this->calculateScore(self::SECOND);
    }

    private function checkMaxRollsPerFrame()
    {
        if (sizeof($this->rolls) < Game::MAX_ROLLS) {
            throw new \Exception("No second roll was rolled.");
        }
    }
}
