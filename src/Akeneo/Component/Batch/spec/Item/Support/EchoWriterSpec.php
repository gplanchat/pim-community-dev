<?php

namespace spec\Akeneo\Component\Batch\Item\Support;

use PhpSpec\ObjectBehavior;

class EchoWriterSpec extends ObjectBehavior
{
    function it_writes()
    {
        $this->write([])->shouldReturn(null);
    }
}
