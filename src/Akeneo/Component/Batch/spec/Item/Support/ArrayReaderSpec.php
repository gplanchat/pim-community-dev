<?php

namespace spec\Akeneo\Component\Batch\Item\Support;

use PhpSpec\ObjectBehavior;

class ArrayReaderSpec extends ObjectBehavior
{
    function it_reads()
    {
        $this->setItems(['item1', 'item2', 'item3']);
        $this->read()->shouldReturn('item1');
        $this->read()->shouldReturn('item2');
        $this->read()->shouldReturn('item3');
        $this->read()->shouldReturn(null);
    }
}
