<?php

namespace CodeWrong;

class Frame
{
	const ROLL_MISSING 	= 'roll_missing';
	const OPEN 			= 'open';
	const SPARE 		= 'spare';
	const STRIKE 		= 'strike';

	protected $rolls = [];

    public function roll($pins)
    {
    	if ($this->score() == 10 or sizeof($this->rolls) == 2) {
    		throw new \Exception("No rolls allowed.");
    	}

    	if ($this->score() + $pins > 10) {
    		throw new \Exception("Too many pins.");
    	}

        $this->rolls[] = new Roll($pins);
    }

    public function score()
    {
        $score = 0;
        foreach($this->rolls as $roll) {
            $score += $roll->score();
        }

        return $score;
    }

    public function state()
    {
        if (sizeof($this->rolls) == 1 and $this->score() < 10) {
        	return self::ROLL_MISSING;
        }

        if (sizeof($this->rolls) == 1 and $this->score() == 10) {
        	return self::STRIKE;
        }

        if (sizeof($this->rolls) == 2 and $this->score() == 10) {
        	return self::SPARE;
        }

        if (sizeof($this->rolls) == 2 and $this->score() < 10) {
        	return self::OPEN;
        }
    }

    public function allowsRoll()
    {
        if ($this->score() == 10 or sizeof($this->rolls) == 2) {
            return false;
        }

        return true;
    }

    public function firstRoll()
    {
        if (sizeof($this->rolls) == 0) {
            throw new \Exception("Empty frame.");            
        }

        return reset($this->rolls)->score();
    }

    public function secondRoll()
    {
        if (sizeof($this->rolls) < 2) {
            throw new \Exception("No second roll was rolled.");            
        }

        return end($this->rolls)->score();
    }
}
