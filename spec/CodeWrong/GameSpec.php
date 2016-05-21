<?php

namespace spec\CodeWrong;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GameSpec extends ObjectBehavior
{
	public function it_is_initializable()
	{
		$this->shouldHaveType('CodeWrong\Game');
	}

	public function it_states_the_current_score()
	{
		$this->score()->shouldEqual(0);
	}

	public function it_does_not_allow_twenty_one_rolls()
	{
		for ($i=0;$i<20;$i++) {
			$this->roll(1);
		}

		$this->shouldThrow(new \Exception("No more shots allowed in the game."))->duringRoll(1);
	}

	public function it_keeps_a_simple_score()
	{
		for ($i=0;$i<20;$i++) {
			$this->roll(4);
		}

		$this->score()->shouldEqual(80);
	}

	public function it_keeps_a_score_with_a_spare()
	{
		for ($i=0;$i<16;$i++) {
			$this->roll(4);
		}

		$this->roll(5);
		$this->roll(5);
		$this->roll(4);
		$this->roll(4);

		$this->score()->shouldEqual(86);
	}

	public function it_keeps_a_score_with_a_strike()
	{
		for ($i=0;$i<16;$i++) {
			$this->roll(4);
		}

		$this->roll(10);
		$this->roll(4);
		$this->roll(4);

		$this->score()->shouldEqual(90);
	}

	public function it_allows_one_fill_ball_if_and_when_the_last_frame_is_a_spare()
	{
		for ($i=0;$i<18;$i++) {
			$this->roll(4);
		}

		$this->roll(5);
		$this->roll(5);
		$this->roll(1);

		$this->score()->shouldEqual(83);
	}

	public function it_allows_two_fill_balls_if_and_when_the_last_frame_is_a_strike()
	{
		for ($i=0;$i<18;$i++) {
			$this->roll(4);
		}

		$this->roll(10);
		$this->roll(5);
		$this->roll(1);

		$this->score()->shouldEqual(88);
	}
}
