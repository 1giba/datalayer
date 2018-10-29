# Datalayer

> A simplest abstraction layer for eloquent models

## Installation

### Composer

Execute the following command to get the latest version of the package:

```terminal
$ composer require 1giba/datalayer
```

### Pre-requisites

* >= laravel5.1
* >= php7.1

## Basic Usage

```php
<?php

use OneGiba\Datalayer\Repository;
use App\User;

// Creates an user repository class extends datalayer repository
class UserRepository extends Repository
{
    public function model(): string
    {
        return User::class;
    }
}

// Instanciates the user repository
$userRepository = new UserRepository;

// Find an user by id
$user = $userRepository->find(1234);

// Show results
echo $user->name . PHP_EOL;
echo $user->email;
```

## Methods

### OneGiba\DataLayer\Contracts\RepositoryInterface

- fisrt(array $columns = ['*']): mixed
- get(): \Illuminate\Support\Collection
- paginate(int $perPage = 50): \Illuminate\Pagination\LengthAwarePaginator;
- create(array $fillable): mixed
- update(array $fillable, int $resourceId): mixed
- delete(int $resourceId): int;
- count(): int;
- sum(string $column): mixed;
- max(string $column): mixed;

### OneGiba\DataLayer\Traits\Searchable

- find(int $resourceId, array $columns = ['*']): ?\Illuminate\Database\Eloquent\Model
- all(array $columns = ['*']): \Illuminate\Support\Collection
- select(array $fields): self
- orderBy(...$args): self
- groupBy(...$args): self
- having(string $aggregation, string $operator, $value): self
- distinct(): self

### OneGiba\DataLayer\Traits\Conditionable

- where(string $column, $value): self
- whereNot(string $column, $value): self
- whereLike(string $column, $value): self
- whereNotLike(string $column, $value): self
- whereBetween(string $column, $from, $until): self
- whereGreaterThan(string $column, $value): self
- whereGreaterOrEqualThan(string $column, $value): self
- whereLessThan(string $column, $value): self
- whereLessOrEqualThan(string $column, $value): self
- whereIsNull(string $column): self
- whereIsNotNull(string $column, $value): self
- orWhere(string $column, $value): self
- orWhereNot(string $column, $value): self
- orWhereLike(string $column, $value): self
- orWhereNotLike(string $column, $value): self
- orWhereBetween(string $column, $from, $until): self
- orWhereGreaterThan(string $column, $value): self
- orWhereGreaterOrEqualThan(string $column, $value): self
- orWhereLessThan(string $column, $value): self
- orWhereLessOrEqualThan(string $column, $value): self
- orWhereIsNull(string $column): self
- orWhereIsNotNull(string $column, $value): self
- whereGroup(Closure $closure): self

### OneGiba\DataLayer\Traits\Joinable

- join(string $table, array $relationships): self
- innerJoin(string $table, array $relationships): self
- leftJoin(string $table, array $relationships): self
- with(array $relationships): self

## Optional Methods

### OneGiba\DataLayer\Traits\Debuggable

- debug(): void

### OneGiba\DataLayer\Traits\Requestable

- addPartialSearch(string $attribute): self
- queryString(array $params = []): self
- allowedFilters(array $fields): self
- allowedSorts(array $fields): self
- refer(string $alias, string $fieldname): self
- addCustomFilters(array $filters): self
- changeAttrsParam(string $param): self
- changeSortParam(string $sort): self

## Usage

### Create a Repository

```php
namespace App\Repositories;

use OneGiba\DataLayer\Repository;
use App\User;

class UserRepository extends Repository
{
    function model(): string
    {
        return User::class;
    }
}
```

### Use methods

```php
namespace App\Http\Controllers;

use App\Repositories\UserRepository;

class UserController extends Controller {

    /**
     * @var \App\Repositories\UserRepository
     */
    protected $repository;

    public function __construct(UserRepository $repository){
        $this->repository = $repository;
    }

    ....
}
```

Find all results in Repository:

```php
$users = $this->repository->all();
```

Find all results in Repository with pagination:

```php
$users = $this->repository->paginate(50);
```

Find by result by id:

```php
$user = $this->repository->find($id);
```

Showing only specific attributes of the model:

```php
$user = $this->repository->find($id, [
    'name',
    'email',
]);
```

Loading the Model relationships:

```php
$user = $this->repository->with([
    'roles',
])->find($id);
```

Find by result by field name:

```php
$users = $this->repository
    ->where('name','Joe Doe')
    ->first();
```

Find by result by multiple fields:

```php
$users = $this->repository
    ->where('email','joedoe@joedoe.com')
    ->where('role_id', 1)
    ->whereGreaterThan('age', 17)
    ->get();
```

Find by result by multiple values in one field;

```php
$users = $this->repository
    ->where('id', [1, 2, 3, 4, 5,])
    ->get();
```

Find by result by excluding multiple values in one field

```php
$users = $this->repository
    ->whereNot('id', [6, 7, 8, 9, 10,])
    ->get();
```

Find by result using OR

```php
$users = $this->repository
    ->where('name', 'Joe Doe')
    ->orWhere('name', 'Mary Jane')
    ->get();
```

Find by result using OR and WhereGroup

```php
$users = $this->repository
    ->where('name', 'Joe Doe')
    ->whereGroup(function ($query) {
        $query->where('age', 15)
            ->orWhere('age', 18);
    })
    ->get();
```

Create new entry in Repository

```php
$user = $this->repository->create(request()->all());
```

Update entry in Repository

```php
$user = $this->repository->update(request()->all(), $userId );
```

Delete entry in Repository

```php
$this->repository->delete($userId)
```

### Using the Debuggable Trait

```php
use OneGiba\DataLayer\Traits\Debuggable;

class UserRepository extends Repository
{
    use Debuggable;
}
```

Prints SQL statement:

```php
$users = $this->repository
    ->select([
        'age',
        'COUNT(1) AS total_per_age',
    ])
    ->groupBy('age')
    ->orderBy('age')
    ->debug(); // Show SQL
```

### Using the Requestable Trait

```php
use OneGiba\DataLayer\Traits\Requestable;

class UserRepository extends Repository
{
    use Requestable;
}
```

Conducting research in the repository:

```php
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $inputs = $request->all();
        return $this->repository
            ->allowedFilters([
                'name', 'email', 'age'
            ])
            ->allowedSorts([
                'id',
                'name',
                'email',
                'age'
            ])
            ->addPartialSearch('name')
            ->queryString($inputs)
            ->paginate(30);
    }
}

/**
 * Supports
 *
 * http://onegiba.dev.local/users?name=John
 * http://onegiba.dev.local/users?email=johndoe@johndoe.com
 * http://onegiba.dev.local/users?sorts=id,-name
 */
```

`http://onegiba.dev.local/users?name=John`

or

`http://onegiba.dev.local/users?email=johndoe@johndoe.com`

```json
[
    {
        "id": 1,
        "name": "John Doe",
        "email": "john@gmail.com",
        "age": 22,
        "role_id": 1,
        "created_at": "-0001-11-30 00:00:00",
        "updated_at": "-0001-11-30 00:00:00"
    }
]
```

`http://onegiba.dev.local/users?sorts=-id`

```json
[
    {
        "id": 2,
        "username": "Mary Jane",
        "email": "mary@gmail.com",
        "age": 41,
        "role_id": 2,
        "created_at": "-0001-11-30 00:00:00",
        "updated_at": "-0001-11-30 00:00:00"
    },
    {
        "id": 1,
        "username": "John Doe",
        "email": "john@gmail.com",
        "age": 22,
        "role_id": 1,
        "created_at": "-0001-11-30 00:00:00",
        "updated_at": "-0001-11-30 00:00:00"
    },
]
```

Filtering fields:

`http://onegiba.dev.local/users?attrs=id,name`

```json
[
    {
        "id": 1,
        "name": "John Doe"
    },
    {
        "id": 2,
        "name": "Lorem Ipsum"
    },
    {
        "id": 3,
        "name": "Laravel"
    }
]
```

## License

Datalayer is released under the MIT Licence.

