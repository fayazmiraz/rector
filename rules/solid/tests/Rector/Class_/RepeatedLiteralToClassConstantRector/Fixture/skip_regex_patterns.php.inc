<?php

namespace Rector\SOLID\Tests\Rector\Class_\RepeatedLiteralToClassConstantRector\Fixture;

class SkipRegexPatterns
{
    public function run($key, $items)
    {
        if ($key === '$') {
            return $items['$'];
        }

        return $items['$'];
    }
}
