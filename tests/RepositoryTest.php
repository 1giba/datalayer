<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use OneGiba\DataLayer\Exceptions\NotFoundException;
use OneGiba\DataLayer\Exceptions\OnCreatingException;
use OneGiba\DataLayer\Exceptions\OnDeletingException;
use OneGiba\DataLayer\Exceptions\OnUpdatingException;

class RepositoryTest extends TestBase
{
    public function setUp()
    {
        parent::setUp();

        $this->model      = Mockery::mock(Model::class);
        $this->repository = new TestRepository($this->model);
    }

    public function testFindAllAndRaisesAnException()
    {
        $collection = Mockery::mock(Collection::class);
        $collection
            ->shouldReceive('isEmpty')
            ->once()
            ->andReturn(true);

        $builder = Mockery::mock(Builder::class);
        $builder
            ->shouldReceive('select')
            ->with(['*'])
            ->once()
            ->andReturn($builder);
        $builder
            ->shouldReceive('orderBy')
            ->with('id', 'ASC')
            ->once()
            ->andReturn($builder);
        $builder
            ->shouldReceive('get')
            ->once()
            ->andReturn($collection);

        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($builder);

        $this->setExpectedException(NotFoundException::class);
        $this->repository->findAll();
    }
    public function testFindAllAndReturnsACollection()
    {
        $collection = Mockery::mock(Collection::class);
        $collection
            ->shouldReceive('isEmpty')
            ->once()
            ->andReturn(false);

        $builder = Mockery::mock(Builder::class);
        $builder
            ->shouldReceive('select')
            ->with(['*'])
            ->once()
            ->andReturn($builder);
        $builder
            ->shouldReceive('orderBy')
            ->with('id', 'ASC')
            ->once()
            ->andReturn($builder);
        $builder
            ->shouldReceive('get')
            ->once()
            ->andReturn($collection);

        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($builder);

        $this->assertInstanceOf(Collection::class, $this->repository->findAll());
    }

    public function testFindFirstAndRaisesAnException()
    {
        $email = $this->faker->email;

        $builder = Mockery::mock(Builder::class);
        $builder
            ->shouldReceive('select')
            ->with(['*'])
            ->once()
            ->andReturn($builder);
        $builder
            ->shouldReceive('where')
            ->with('email', $email)
            ->once()
            ->andReturn($builder);
        $builder
            ->shouldReceive('first')
            ->once()
            ->andReturn(null);

        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($builder);


        $this->setExpectedException(NotFoundException::class);
        $this->repository->findFirst(['email' => $email]);
    }

    public function testFindFirstAndReturnsAnObject()
    {
        $email = $this->faker->email;

        $builder = Mockery::mock(Builder::class);
        $builder
            ->shouldReceive('select')
            ->with(['*'])
            ->once()
            ->andReturn($builder);
        $builder
            ->shouldReceive('where')
            ->with('email', $email)
            ->once()
            ->andReturn($builder);
        $builder
            ->shouldReceive('first')
            ->once()
            ->andReturn($this->model);

        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($builder);

        $this->assertInstanceOf(Model::class, $this->repository->findFirst([
            'email' => $email,
        ]));
    }

    public function testFindByIdAndRaisesAnException()
    {
        $resourceId = $this->faker->numberBetween(100, 999);

        $this->model
            ->shouldReceive('find')
            ->with($resourceId, ['*'])
            ->once()
            ->andReturn(null);

        $this->setExpectedException(NotFoundException::class);
        $this->repository->findById($resourceId);
    }

    public function testFindByIdAndReturnsAnObject()
    {
        $resourceId = $this->faker->numberBetween(100, 999);

        $this->model
            ->shouldReceive('find')
            ->with($resourceId, ['*'])
            ->once()
            ->andReturn($this->model);

        $this->assertInstanceOf(Model::class, $this->repository->findById($resourceId));
    }

    public function testFindByAndRaisesAnException()
    {
        $email = $this->faker->email;

        $collection = Mockery::mock(Collection::class);
        $collection
            ->shouldReceive('isEmpty')
            ->once()
            ->andReturn(true);

        $builder = Mockery::mock(Builder::class);
        $builder
            ->shouldReceive('select')
            ->with(['*'])
            ->once()
            ->andReturn($builder);
        $builder
            ->shouldReceive('where')
            ->with('email', $email)
            ->once()
            ->andReturn($builder);
        $builder
            ->shouldReceive('orderBy')
            ->with('id', 'ASC')
            ->once()
            ->andReturn($builder);
        $builder
            ->shouldReceive('get')
            ->once()
            ->andReturn($collection);

        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($builder);

        $this->setExpectedException(NotFoundException::class);
        $this->repository->findBy([
            'email' => $email,
        ]);
    }

    public function testFindByReturnsAnObject()
    {
        $email = $this->faker->email;

        $collection = Mockery::mock(Collection::class);
        $collection
            ->shouldReceive('isEmpty')
            ->once()
            ->andReturn(false);

        $builder = Mockery::mock(Builder::class);
        $builder
            ->shouldReceive('select')
            ->with(['*'])
            ->once()
            ->andReturn($builder);
        $builder
            ->shouldReceive('where')
            ->with('email', $email)
            ->once()
            ->andReturn($builder);
        $builder
            ->shouldReceive('orderBy')
            ->with('id', 'ASC')
            ->once()
            ->andReturn($builder);
        $builder
            ->shouldReceive('get')
            ->once()
            ->andReturn($collection);

        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($builder);

        $this->assertInstanceOf(Collection::class, $this->repository->findBy([
            'email' => $email,
        ]));
    }

    public function testPaginateReturnsAPaginator()
    {
        $perPage = $this->faker->randomElement([10, 30, 50, 100]);

        $builder = Mockery::mock(Builder::class);
        $builder
            ->shouldReceive('select')
            ->with(['*'])
            ->once()
            ->andReturn($builder);
        $builder
            ->shouldReceive('orderBy')
            ->with('id', 'ASC')
            ->once()
            ->andReturn($builder);
        $builder
            ->shouldReceive('paginate')
            ->with($perPage)
            ->once()
            ->andReturn(Mockery::mock(LengthAwarePaginator::class));

        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($builder);

        $this->assertInstanceOf(LengthAwarePaginator::class, $this->repository->paginate($perPage));
    }

    public function testCreateRaisesAnException()
    {
        $this->model
            ->shouldReceive('create')
            ->with([])
            ->once()
            ->andThrow(Exception::class);

        $this->setExpectedException(OnCreatingException::class);
        $this->repository->create([]);
    }

    public function testCreateReturnsNull()
    {
        $data = [
            'username' => $this->faker->userName,
            'password' => $this->faker->password,
        ];

        $this->model
            ->shouldReceive('create')
            ->with($data)
            ->once()
            ->andReturn(null);

        $this->assertNull($this->repository->create($data));
    }

    public function testCreateReturnsAnObject()
    {
        $data = [
            'username' => $this->faker->userName,
            'password' => $this->faker->password,
        ];

        $this->model
            ->shouldReceive('create')
            ->with($data)
            ->once()
            ->andReturn($this->model);

        $this->assertInstanceOf(Model::class, $this->repository->create($data));
    }

    public function testUpdateNotFound()
    {
        $resourceId = $this->faker->numberBetween(100, 999);

        $this->model
            ->shouldReceive('find')
            ->with($resourceId, ['*'])
            ->once()
            ->andReturn(null);

        $this->setExpectedException(NotFoundException::class);
        $this->repository->update([], $resourceId);
    }

    public function testUpdateRaisesAnException()
    {
        $data = [
            'username' => $this->faker->userName,
            'email'    => $this->faker->email,
        ];

        $resourceId = $this->faker->numberBetween(100, 999);

        $this->model
            ->shouldReceive('find')
            ->with($resourceId, ['*'])
            ->once()
            ->andReturn($this->model);

        $this->model
            ->shouldReceive('fill')
            ->with($data)
            ->once()
            ->andReturn($this->model);

        $this->model
            ->shouldReceive('save')
            ->once()
            ->andThrow(Exception::class);

        $this->setExpectedException(OnUpdatingException::class);
        $this->repository->update($data, $resourceId);
    }

    public function testUpdateShouldBeFalse()
    {
        $data = [
            'username' => $this->faker->userName,
            'email'    => $this->faker->email,
        ];

        $resourceId = $this->faker->numberBetween(100, 999);

        $this->model
            ->shouldReceive('find')
            ->with($resourceId, ['*'])
            ->once()
            ->andReturn($this->model);

        $this->model
            ->shouldReceive('fill')
            ->with($data)
            ->once()
            ->andReturn($this->model);

        $this->model
            ->shouldReceive('save')
            ->once()
            ->andReturn(false);

        $this->assertFalse($this->repository->update($data, $resourceId));
    }

    public function testUpdateShouldBeTrue()
    {
        $data = [
            'username' => $this->faker->userName,
            'email'    => $this->faker->email,
        ];

        $resourceId = $this->faker->numberBetween(100, 999);

        $this->model
            ->shouldReceive('find')
            ->with($resourceId, ['*'])
            ->once()
            ->andReturn($this->model);

        $this->model
            ->shouldReceive('fill')
            ->with($data)
            ->once()
            ->andReturn($this->model);

        $this->model
            ->shouldReceive('save')
            ->once()
            ->andReturn(true);

        $this->assertTrue($this->repository->update($data, $resourceId));
    }

    public function testDeleteRaisesAnException()
    {
        $resourceId = $this->faker->numberBetween(100, 999);

        $this->model
            ->shouldReceive('destroy')
            ->with($resourceId)
            ->once()
            ->andThrow(Exception::class);

        $this->setExpectedException(OnDeletingException::class);
        $this->repository->delete($resourceId);
    }

    public function testDeleteReturnsZero()
    {
        $resourceId = $this->faker->numberBetween(100, 999);

        $this->model
            ->shouldReceive('destroy')
            ->with($resourceId)
            ->once()
            ->andReturn(0);

        $this->assertEquals(0, $this->repository->delete($resourceId));
    }

    public function testDeleteShouldBeSuccess()
    {
        $resourceId = $this->faker->numberBetween(100, 999);

        $this->model
            ->shouldReceive('destroy')
            ->with($resourceId)
            ->once()
            ->andReturn(1);

        $this->assertEquals(1, $this->repository->delete($resourceId));
    }
}
