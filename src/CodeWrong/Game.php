<?php

namespace CodeWrong;

class Game
{
    const MAX_ROLLS = 2;
    const MAX_PINS = 10;

    private $frames = [];
    private $fillBalls = [];

    public function roll($pins)
    {
        $currentFrame = $this->getCurrentFrame();
        if ($currentFrame != null) {
            $currentFrame->roll($pins);
        } else {
            $this->fillBallRoll($pins);
        }
    }

    private function getCurrentFrame()
    {
        if (sizeof($this->frames) == 0) {
            return $this->addFrame();
        }

        $currentFrame = end($this->frames);

        if ($currentFrame->allowsRoll()) {
            return $currentFrame;
        }

        if (sizeof($this->frames) == self::MAX_PINS and !$this->fillBallAllowed()) {
            throw new \Exception("No more shots allowed in the game.");
        }

        return $this->addFrame();
    }

    private function addFrame()
    {
        if (sizeof($this->frames) == self::MAX_PINS) {
            return null;
        }

        $frame = new Frame();
        $this->frames[] = $frame;

        return $frame;
    }

    private function fillBallAllowed()
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

    private function fillBallRoll($pins)
    {
        if (sizeof($this->frames) < self::MAX_PINS) {
            throw new \Exception("Something went terribly wrong.");
        }

        $this->fillBalls[] = new Roll($pins);
    }

    public function score()
    {
        $score = 0;
        foreach ($this->frames as $key => $frame) {
            $score += $frame->score();

            switch ($frame->state()) {
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

    private function getNextBallScore($key)
    {
        if (array_key_exists($key + 1, $this->frames)) {
            return $this->frames[$key+1]->firstRoll();
        }

        if (sizeof($this->fillBalls) > 0) {
            return reset($this->fillBalls)->score();
        }
    }

    private function getNextTwoBallsScore($key)
    {
        if (array_key_exists($key + 1, $this->frames) and $this->frames[$key + 1]->state() != Frame::STRIKE) {
            return $this->frames[$key+1]->firstRoll() + $this->frames[$key+1]->secondRoll();
        }

        $score = 0;
        foreach ($this->fillBalls as $roll) {
            $score += $roll->score();
        }

        return $score;
    }
}
