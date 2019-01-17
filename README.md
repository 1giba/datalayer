# Datalayer

> A simplest abstraction layer for eloquent models

No fat models and standardized methods.

## Installation

### Composer

Execute the following command to get the latest version of the package:

```terminal
$ composer require 1giba/datalayer
```

### Pre-requisites

* `>= laravel5.1`
* `>= php7.1`

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

- fisrt(): mixed
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

- setEquals(string): self
- where(string $column, $value): self
- setScope(string): self
- resetScope(): self
- applyScope(): self

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

class UserController extends Controller
{

    protected $repository;

    public function __construct(UserRepository $repository)
    {
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
$user = $this->repository
    ->select([
        'name',
        'email',
    ])
    ->find($id);
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
    ->where('email','joedoe@gmail.com')
    ->where('role_id', 1)
    ->get();
```

Find by result by multiple values in one field;

```php
$users = $this->repository
    ->where('id', [1, 2, 3, 4, 5,])
    ->get();
```

Find by result using scope

```php
$users = $this->repository
    ->where('name', 'Joe Doe')
    ->setScope(function ($query) {
        $query->where('age', 15)
            ->orWhere('age', 18);
    })
    ->applyScope()
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
 * http://onegiba.dev.local/users?name=Joe
 * http://onegiba.dev.local/users?email=joedoe@gmail.com
 * http://onegiba.dev.local/users?sorts=id,-name
 */
```

`http://onegiba.dev.local/users?name=Joe`

or

`http://onegiba.dev.local/users?email=joedoe@gmail.com`

```json
[
    {
        "id": 1,
        "name": "Joe Doe",
        "email": "joedoe@gmail.com",
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
        "name": "Mary Jane",
        "email": "mary@gmail.com",
        "age": 41,
        "role_id": 2,
        "created_at": "-0001-11-30 00:00:00",
        "updated_at": "-0001-11-30 00:00:00"
    },
    {
        "id": 1,
        "name": "Joe Doe",
        "email": "joedoe@gmail.com",
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
        "name": "Joe Doe"
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

