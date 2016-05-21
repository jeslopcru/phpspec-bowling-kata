<?php

namespace spec\CodeWrong;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RollSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->beConstructedWith(10);

        $this->shouldHaveType('CodeWrong\Roll');
    }

    public function it_states_value()
    {
        $this->beConstructedWith(10);

        $this->score()->shouldBe(10);
    }

    public function it_throws_an_exception_with_incorrect_roll()
    {
        $this->beConstructedWith(14);

        $this->shouldThrow(new \Exception("Incorrect value."))->duringInstantiation();
    }
}
