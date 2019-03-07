<?php

namespace OneGiba\DataLayer\Tests;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use OneGiba\DataLayer\Repository as AbstractRepository;
use Mockery;

class RepositoryTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->builder    = Mockery::mock(Builder::class);
        $this->model      = Mockery::mock(Model::class);
        $this->repository = new Repository($this->model);
    }

    /**
     * @test
     */
    public function it_is_query_builder()
    {
        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(Builder::class, $this->repository->getQuery());
    }

    /**
     * @test
     */
    public function find_model_by_id_should_be_null()
    {
        $this->builder
            ->shouldReceive('find')
            ->with(123, ['*'])
            ->once()
            ->andReturn(null);

        $this->model
            ->shouldReceive('newQuery')
            ->twice() // call find and reset query
            ->andReturn($this->builder);

        $this->assertNull($this->repository->findById(123));
    }

    /**
     * @test
     */
    public function find_model_by_id_should_be_an_object()
    {
        $this->builder
            ->shouldReceive('find')
            ->with(456, ['*'])
            ->once()
            ->andReturn($this->model);

        $this->model
            ->shouldReceive('newQuery')
            ->twice() // call find and reset query
            ->andReturn($this->builder);

        $this->assertInstanceOf(Model::class, $this->repository->findById(456));
    }

    /**
     * @test
     */
    public function fetch_all_returns_an_collection()
    {
        $this->model
            ->shouldReceive('all')
            ->once()
            ->andReturn(Mockery::mock(Collection::class));

        $this->assertInstanceOf(Collection::class, $this->repository->fetchAll());
    }

    /**
     * @test
     */
    public function find_first_should_be_null()
    {
        $this->builder
            ->shouldReceive('first')
            ->once()
            ->andReturn(null);

        $this->model
            ->shouldReceive('newQuery')
            ->twice() // call first and reset query
            ->andReturn($this->builder);

        $this->assertNull($this->repository->first());
    }

    /**
     * @test
     */
    public function find_first_should_be_an_object()
    {
        $this->builder
            ->shouldReceive('first')
            ->once()
            ->andReturn($this->model);

        $this->model
            ->shouldReceive('newQuery')
            ->twice() // call first and reset query
            ->andReturn($this->builder);

        $this->assertInstanceOf(Model::class, $this->repository->first());
    }

    /**
     * @test
     */
    public function fetch_a_collection()
    {
        $this->builder
            ->shouldReceive('get')
            ->once()
            ->andReturn(Mockery::mock(Collection::class));

        $this->model
            ->shouldReceive('newQuery')
            ->twice() // call get and reset query
            ->andReturn($this->builder);

        $this->assertInstanceOf(Collection::class, $this->repository->fetch());
    }

    /**
     * @test
     */
    public function paginate_data()
    {
        $this->builder
            ->shouldReceive('paginate')
            ->once()
            ->andReturn(Mockery::mock(LengthAwarePaginator::class));

        $this->model
            ->shouldReceive('newQuery')
            ->twice() // call get and reset query
            ->andReturn($this->builder);

        $this->assertInstanceOf(LengthAwarePaginator::class, $this->repository->paginate());
    }

    /**
     * @test
     */
    public function create_and_return_null()
    {
        $this->builder
            ->shouldReceive('create')
            ->with([
                'name' => 'Gilberto Junior',
            'email' => 'olamundo@gmail.com',
            ])
            ->once()
            ->andReturn(null);

        $this->model
            ->shouldReceive('newQuery')
            ->twice() // call create and reset query
            ->andReturn($this->builder);

        $this->assertNull($this->repository->create([
            'name' => 'Gilberto Junior',
            'email' => 'olamundo@gmail.com',
        ]));
    }

    /**
     * @test
     */
    public function create_and_return_an_object()
    {
        $this->builder
            ->shouldReceive('create')
            ->with([
                'name' => 'Gilberto Junior',
                'email' => 'olamundo@gmail.com',
            ])
            ->once()
            ->andReturn($this->model);

        $this->model
            ->shouldReceive('newQuery')
            ->twice() // call create and reset query
            ->andReturn($this->builder);

        $this->assertInstanceOf(Model::class, $this->repository->create([
            'name' => 'Gilberto Junior',
            'email' => 'olamundo@gmail.com',
        ]));
    }

    /**
     * @test
     */
    public function update_and_return_null()
    {
        $this->builder
            ->shouldReceive('find')
            ->with(123)
            ->once()
            ->andReturn(null);

        $this->model
            ->shouldReceive('newQuery')
            ->twice() // call update and reset query
            ->andReturn($this->builder);

        $this->assertNull($this->repository->update([
            'name' => 'DataLayer',
        ], 123));
    }

    /**
     * @test
     */
    public function update_and_return_an_object()
    {
        $model = Mockery::mock(Model::class);
        $model
            ->shouldReceive('fill')
            ->with([
                'name' => 'DataLayer',
            ])
            ->once()
            ->andReturn($model);

        $this->builder
            ->shouldReceive('find')
            ->with(123)
            ->once()
            ->andReturn($model);

        $this->model
            ->shouldReceive('newQuery')
            ->twice() // call update and reset query
            ->andReturn($this->builder);

        $this->assertInstanceOf(Model::class, $this->repository->update([
            'name' => 'DataLayer',
        ], 123));
    }
}

class Repository extends AbstractRepository
{
    public function model(): string
    {
        return '';
    }
}
