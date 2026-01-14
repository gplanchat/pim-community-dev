<?php

namespace Akeneo\Platform\Bundle\ImportExportBundle\Query;

class SystemClock implements ClockInterface
{
    #[\Override]
    public function now(): \DateTimeInterface
    {
        return new \DateTime('now', new \DateTimeZone('UTC'));
    }
}
