<?php

use Faker\Factory as FakerFactory;

class TestBase extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->faker = FakerFactory::create();
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
