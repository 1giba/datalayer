<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use OneGiba\DataLayer\Traits\ActionalizeQueries;

class ActionalizeTest extends TestBase
{
    use ActionalizeQueries;

    public function setUp()
    {
        parent::setUp();

        $this->builder = Mockery::mock(Builder::class);
    }

    public function testFirst()
    {
        $this->builder
            ->shouldReceive('first')
            ->once()
            ->andReturn(Mockery::mock(Model::class));

        $this->assertInstanceOf(Model::class, $this->first());
    }

    public function testGet()
    {
        $this->builder
            ->shouldReceive('get')
            ->once()
            ->andReturn(Mockery::mock(Collection::class));

        $this->assertInstanceOf(Collection::class, $this->get());
    }

    public function testPaginate()
    {
        $this->builder
            ->shouldReceive('paginate')
            ->with(100)
            ->once()
            ->andReturn(Mockery::mock(LengthAwarePaginator::class));

        $this->assertInstanceOf(LengthAwarePaginator::class, $this->paginate(100));
    }

    public function testSql()
    {
        $sql = 'SELECT * FROM users';

        $this->builder
            ->shouldReceive('toSql')
            ->once()
            ->andReturn($sql);

        $this->assertEquals($sql, $this->sql());
    }
}
