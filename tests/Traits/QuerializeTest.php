<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use OneGiba\DataLayer\Queries\Query;

class QuerializeTest extends TestBase
{
    public function setUp()
    {
        $this->model      = Mockery::mock(Model::class);
        $this->repository = new TestRepository($this->model);
    }

    public function testQueryUsingModelInstance()
    {
        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn(Mockery::mock(Builder::class));

        $this->assertInstanceOf(Query::class, $this->repository->query($this->model));
    }

    public function testQueryUsingBuilderInstance()
    {
        $this->assertInstanceOf(Query::class, $this->repository->query(Mockery::mock(Builder::class)));
    }
}
