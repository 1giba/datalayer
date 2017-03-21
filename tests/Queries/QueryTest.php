<?php

use Illuminate\Database\Eloquent\Builder;
use OneGiba\DataLayer\Queries\Query;
use OneGiba\DataLayer\Contracts\RepositoryInterface;
use OneGiba\DataLayer\Contracts\PostgresInterface;

class QueryTest extends TestBase
{
    public function setUp()
    {
        parent::setUp();

        $this->builder    = Mockery::mock(Builder::class);
        $this->repository = Mockery::mock(RepositoryInterface::class);
        $this->query      = new Query($this->builder, $this->repository);
    }

    public function testWith()
    {
        $this->builder
            ->shouldReceive('with')
            ->with([
                'relationship1',
                'relationship2',
            ])
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(Query::class, $this->query->with([
                'relationship1',
                'relationship2',
            ]));
    }

    public function testSelectWithEmptyParam()
    {
        $this->assertInstanceOf(Query::class, $this->query->select([]));
    }

    public function testSelectWithAllFields()
    {
        $this->builder
            ->shouldReceive('select')
            ->with(['*'])
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(Query::class, $this->query->select(['*']));
    }

    public function testSelectWithEspecificFields()
    {
        $this->builder
            ->shouldReceive('select')
            ->with([
                'username',
                'email',
                'created_at',
                'updated_at',
            ])
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(Query::class, $this->query->select([
                'username',
                'email',
                'created_at',
                'updated_at',
            ]));
    }

    public function testApplyWithEmptyConditions()
    {
        $this->assertInstanceOf(Query::class, $this->query->apply([]));
    }

    public function testApplyUsingWhereIn()
    {
        $this->builder
            ->shouldReceive('whereIn')
            ->with('user_id', [1, 2, 3, 4, 5])
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(Query::class, $this->query->apply([
            'user_id' => [1, 2, 3, 4, 5],
        ]));
    }

    public function testApplyUsingWhereGreaterThan()
    {
        $this->builder
            ->shouldReceive('where')
            ->with('price', '>', '10.89')
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(Query::class, $this->query->apply([
            'price' => '10.89..',
        ]));
    }

    public function testApplyUsingWhereLowerThan()
    {
        $this->builder
            ->shouldReceive('where')
            ->with('price', '<', '10.89')
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(Query::class, $this->query->apply([
            'price' => '..10.89',
        ]));
    }

    public function testApplyUsingWhereGreaterAndEqualsThan()
    {
        $this->builder
            ->shouldReceive('where')
            ->with('price', '>=', '10.89')
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(Query::class, $this->query->apply([
            'price' => '10.89...',
        ]));
    }

    public function testApplyUsingWhereLowerAndEqualsThan()
    {
        $this->builder
            ->shouldReceive('where')
            ->with('price', '<=', '10.89')
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(Query::class, $this->query->apply([
            'price' => '...10.89',
        ]));
    }

    public function testApplyUsingWhereGreaterAndLowerThan()
    {
        $this->builder
            ->shouldReceive('where')
            ->with('created_at', '>', '2017-01-01')
            ->once()
            ->andReturn($this->builder);

        $this->builder
            ->shouldReceive('where')
            ->with('created_at', '<', '2017-02-20')
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(Query::class, $this->query->apply([
            'created_at' => '2017-01-01..2017-02-20',
        ]));
    }

    public function testApplyUsingWhereGreaterAndLowerAndEqualsThan()
    {
        $this->builder
            ->shouldReceive('where')
            ->with('created_at', '>=', '2017-01-01')
            ->once()
            ->andReturn($this->builder);

        $this->builder
            ->shouldReceive('where')
            ->with('created_at', '<=', '2017-02-20')
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(Query::class, $this->query->apply([
            'created_at' => '2017-01-01...2017-02-20',
        ]));
    }

    public function testApplyUsingWhereLike()
    {
        $this->builder
            ->shouldReceive('where')
            ->with('name', 'LIKE', '%fulano%')
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(Query::class, $this->query->apply([
            'name' => '%fulano%',
        ]));
    }

    /*public function testApplyUsingWhereLikePostgres()
    {
        $repository = Mockery::mock(TestPostgresRepository::class);
        $query      = new Query($this->builder, $repository);

        $this->builder
            ->shouldReceive('where')
            ->with('name', 'ILIKE', '%fulano%')
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(Query::class, $query->apply([
            'name' => '%fulano%',
        ]));
    }*/

    public function testApplyUsingWhere()
    {
        $email = $this->faker->email;

        $this->builder
            ->shouldReceive('where')
            ->with('email', $email)
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(Query::class, $this->query->apply([
            'email' => $email,
        ]));
    }

    public function testApplyUsingWhereIsNull()
    {
        $this->builder
            ->shouldReceive('whereIsNull')
            ->with('role_id')
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(Query::class, $this->query->apply([
            'role_id' => null,
        ]));
    }

    public function testApplyUsingWhereIsNotNull()
    {
        $this->builder
            ->shouldReceive('whereIsNotNull')
            ->with('role_id')
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(Query::class, $this->query->apply([
            'role_id' => '!',
        ]));
    }

    public function testGroupByWithEmptyParam()
    {
        $this->assertInstanceOf(Query::class, $this->query->groupBy([]));
    }

    public function testGroupByWithParams()
    {
        $this->builder
            ->shouldReceive('groupBy')
            ->with('store_id', 'created_at')
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(Query::class, $this->query->groupBy([
            'store_id',
            'created_at',
        ]));
    }

    public function testHavingWithEmptyParam()
    {
        $this->assertInstanceOf(Query::class, $this->query->having(null));
    }

    public function testHavingWithSqlClausule()
    {
        $sql = 'COUNT(id) > 1 AND SUM(total) > 100.00';

        $this->builder
            ->shouldReceive('havingRaw')
            ->with($sql)
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(Query::class, $this->query->having($sql));
    }

    public function testOrderByWithEmptyParam()
    {
        $this->assertInstanceOf(Query::class, $this->query->orderBy([]));
    }

    public function testOrderByWithSortableFields()
    {
        $this->builder
            ->shouldReceive('orderBy')
            ->with('id', 'DESC')
            ->once()
            ->andReturn($this->builder);

        $this->builder
            ->shouldReceive('orderBy')
            ->with('name', 'ASC')
            ->once()
            ->andReturn($this->builder);

        $this->builder
            ->shouldReceive('orderBy')
            ->with('code', 'ASC')
            ->once()
            ->andReturn($this->builder);

        $this->builder
            ->shouldReceive('orderBy')
            ->with('created_at', 'DESC')
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(Query::class, $this->query->orderBy([
            '-id',
            '+name',
            'code',
            '-created_at',
        ]));
    }

    public function testLimitWithZero()
    {
        $this->assertInstanceOf(Query::class, $this->query->limit(0));
    }

    public function testLimitWithLast100()
    {
        $this->builder
            ->shouldReceive('take')
            ->with(100)
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(Query::class, $this->query->limit(100));
    }

    public function testScopeQuery()
    {
        $this->assertInstanceOf(Query::class, $this->query->scopeQuery(function ($query) {
            return $query;
        }));
    }
}
