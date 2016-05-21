<?php

namespace CodeWrong;

class Game
{
	protected $frames = [];
    protected $fillBalls = [];

    public function roll($pins)
    {
		$currentFrame = $this->getCurrentFrame();
        if ($currentFrame != null) {
            $currentFrame->roll($pins);
        } else {
            $this->fillBallRoll($pins);
        }
    }

    public function score()
    {
    	$score = 0;
		foreach ($this->frames as $key => $frame) {
			$score += $frame->score();

			switch($frame->state()) {
				case Frame::SPARE:
					$score += $this->getNextBallScore($key);
					break;
				case Frame::STRIKE:
					$score += $this->getNextTwoBallsScore($key);
					break;
			}
		}

		return $score;
    }

    protected function getCurrentFrame()
    {
    	if (sizeof($this->frames) == 0) {
    		return $this->addFrame();
    	}

    	$currentFrame = end($this->frames);

    	if ($currentFrame->allowsRoll()) {
    		return $currentFrame;
    	}

        if (sizeof($this->frames) == 10 and !$this->fillBallAllowed()) {
            throw new \Exception("No more shots allowed in the game.");
        }

    	return $this->addFrame();
    }

    protected function fillBallAllowed()
    {
        $lastFrame = end($this->frames);

        if (in_array($lastFrame->state(), [Frame::ROLL_MISSING, Frame::OPEN])) {
            return false;
        }

        if ($lastFrame->state() == Frame::SPARE and sizeof($this->fillBalls) > 0) {
            return false;
        }

        if ($lastFrame->state() == Frame::STRIKE and sizeof($this->fillBalls) > 1) {
            return false;
        }

        return true;
    }

    protected function getNextBallScore($key) {
		if (array_key_exists($key+1, $this->frames)) {
            return $this->frames[$key+1]->firstRoll();
        }

        if (sizeof($this->fillBalls) > 0) {
            return reset($this->fillBalls)->score();
        }
    }

    protected function getNextTwoBallsScore($key) {
		if (array_key_exists($key+1, $this->frames) and $this->frames[$key+1]->state() != Frame::STRIKE) {
            return $this->frames[$key+1]->firstRoll() + $this->frames[$key+1]->secondRoll();
        }

        $score = 0;
        foreach ($this->fillBalls as $roll) {
            $score += $roll->score();
        }

        return $score;
    }

    protected function addFrame() {
        if (sizeof($this->frames) == 10) {
            return null;
        }

    	$frame = new Frame();
    	$this->frames[] = $frame;

		return $frame;
    }

    protected function fillBallRoll($pins)
    {
        if (sizeof($this->frames) < 10) {
            throw new \Exception("Something went terribly wrong.");
        }

        $this->fillBalls[] = new Roll($pins);
    }
}
