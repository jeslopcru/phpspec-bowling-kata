<?php

namespace CodeWrong;

class Roll
{
	protected $pins;

    public function __construct($pins)
    {
    	if ($pins < 0 or $pins > 10) {
    		throw new \Exception("Incorrect value.");
    	}

        $this->pins = $pins;
    }

    public function score()
    {
        return $this->pins;
    }
}
