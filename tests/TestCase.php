<?php

namespace OneGiba\DataLayer\Tests;

use PHPUnit\Framework\TestCase as PhpUnitTestCase;

class TestCase extends PhpUnitTestCase
{
    public function tearDown()
    {
        $mock = 'Mockery';
        if (class_exists($mock)) {
            $mock::close();
        }
    }
}
