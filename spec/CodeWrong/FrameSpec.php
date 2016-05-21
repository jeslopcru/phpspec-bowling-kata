<?php

namespace spec\CodeWrong;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FrameSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('CodeWrong\Frame');
    }

    public function it_allows_rolling_pins()
    {
        $this->roll(1);

        $this->score()->shouldEqual(1);
    }

    public function it_allows_only_two_rolls()
    {
        $this->roll(1);
        $this->roll(1);

        $this->shouldThrow(new \Exception("No rolls allowed."))->duringRoll(1);
    }

    public function it_allows_only_one_roll_if_it_is_a_strike()
    {
        $this->roll(10);

        $this->shouldThrow(new \Exception("No rolls allowed."))->duringRoll(1);
    }

    public function it_keeps_track_of_score()
    {
        $this->roll(7);
        $this->roll(1);

        $this->score()->shouldEqual(8);
    }

    public function it_states_the_score_of_the_first_roll()
    {
        $this->roll(7);
        $this->roll(1);

        $this->firstRoll()->shouldEqual(7);
    }

    public function it_states_the_score_of_the_second_roll()
    {
        $this->roll(7);
        $this->roll(1);

        $this->secondRoll()->shouldEqual(1);
    }

    public function it_throws_an_exception_if_asked_for_the_second_roll_of_a_strike()
    {
        $this->roll(10);

        $this->shouldThrow(new \Exception("No second roll was rolled."))->duringSecondRoll();
    }

    public function it_throws_an_exception_if_asked_for_the_first_roll_of_an_empty_frame()
    {
        $this->shouldThrow(new \Exception("Empty frame."))->duringFirstRoll();
    }

    public function it_does_not_allow_incorrect_scores()
    {
        $this->roll(7);

        $this->shouldThrow(new \Exception("Too many pins."))->duringRoll(5);
    }

    public function it_states_its_state_roll_missing()
    {
        $this->roll(6);

        $this->state()->shouldEqual('roll_missing');
    }

    public function it_states_its_state_open()
    {
        $this->roll(5);
        $this->roll(3);

        $this->state()->shouldEqual('open');
    }

    public function it_states_its_state_spare()
    {
        $this->roll(2);
        $this->roll(8);

        $this->state()->shouldEqual('spare');
    }

    public function it_states_its_state_strike()
    {
        $this->roll(10);

        $this->state()->shouldEqual('strike');
    }

    public function it_states_if_it_allows_roll()
    {
        $this->roll(4);

        $this->allowsRoll()->shouldBe(true);

        $this->roll(4);

        $this->allowsRoll()->shouldBe(false);
    }

    public function it_states_it_does_not_allow_roll_after_a_strike()
    {
        $this->roll(10);

        $this->allowsRoll()->shouldBe(false);
    }
}
