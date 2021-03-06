<?php

namespace Rector\CodingStyle\Tests\Rector\Use_\RemoveUnusedAliasRector\DocBlockUnionType;

use DateTime as PhpDateTime;
use DateTimeImmutable as PhpDateTimeImmutable;

class DocBlock
{
    /**
     * @var PhpDateTime|bool
     */
    protected $b;

    /**
     * @var PhpDateTimeImmutable
     */
    protected $a;
}
