# Datalayer

> A simplest abstraction layer for eloquent models

* No fat models;
* Standardized methods;
* More instictive;
* Improves more easily readable.

## Installation

### Composer

Execute the following command to get the latest version of the package:

```terminal
$ composer require 1giba/datalayer
```

### Pre-requisites

* `>= laravel5.5`
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
$user = $userRepository->findById(1234);

// Show results
echo $user->name . PHP_EOL;
echo $user->email;
```

## Methods

### OneGiba\DataLayer\Contracts\RepositoryInterface

- fisrt(): mixed
- fetch(): \Illuminate\Support\Collection
- paginate(int $perPage = 50): \Illuminate\Pagination\LengthAwarePaginator;
- create(array $fillable): mixed
- update(array $fillable, int $resourceId): mixed
- delete(int $resourceId): int;
- count(): int;
- sum(string $column): mixed;
- max(string $column): mixed;
- min(string $column): mixed;
- avg(string $column): mixed;

### OneGiba\DataLayer\Traits\Searchable

- findById(int $resourceId): ?\Illuminate\Database\Eloquent\Model
- fetchAll(array $columns = ['*']): \Illuminate\Support\Collection
- showFields(array $fields): self
- sortAscending(string $sortField): self
- sortDescending(string $sortField): self
- groupBy(...$args): self
- limit(int $quantity): self
- having(string $aggregation, string $operator, $value): self
- distinct(): self

### OneGiba\DataLayer\Traits\Conditionable

- isEqual(string, mixed): self
- isNotEqual(string, mixed): self
- like(string, mixed): self
- notLike(string, mixed): self
- isGreaterThan(string, mixed): self
- isGreaterThanEqual(string, mixed): self
- isLessThan(string, mixed): self
- isLessThanEqual(string, mixed): self
- between(string, mixed, mixed): self
- isNull(string): self
- isNotNull(string): self
- clausules(closure): self
- orClausules(closure): self

*PS:* All clausules methods have a `or` implementation with prefix `or`. Ex.: orEqual(), orIsNull(), etc.

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
$users = $this->repository->fetchAll();
```

Find all results in Repository with pagination:

```php
$users = $this->repository->paginate(50);
```

Find by result by id:

```php
$user = $this->repository->findById($id);
```

Showing only specific attributes of the model:

```php
$user = $this->repository
    ->showFields([
        'name',
        'email',
    ])
    ->findById($id);
```

Loading the Model relationships:

```php
$user = $this->repository->with([
    'roles',
])->findById($id);
```

Find by result by field name:

```sql
SELECT * FROM users WHERE name = 'Joe Doe' LIMIT 1
```

```php
$users = $this->repository
    ->isEqual('name','Joe Doe')
    ->first();
```

Find all except one:

```sql
SELECT * FROM users WHERE id <> 234;
```

```php
$users = $this->repository
    ->isNotEqual('id', 234)
    ->get();
```

Find by result by multiple fields:

```sql
SELECT * FROM users WHERE email = 'joedoe@gmail.com' AND role_id > 1
ORDER BY id
```

```php
$users = $this->repository
    ->isEqual('email','joedoe@gmail.com')
    ->isGreaterThan('role_id', 1)
    ->sortAscending('id')
    ->fetch();
```

Find by result by multiple values in one field;

```sql
SELECT * FROM users WHERE id IN (1, 2, 3, 4, 5) ORDER BY id DESC
```

```php
$users = $this->repository
    ->isEqual('id', [1, 2, 3, 4, 5,])
    ->sortDesceding('id')
    ->fetch();
```

Find by result using `AND` and `OR`:

```sql
SELECT * FROM users WHERE (age > 18 AND genre = 'M')
OR email LIKE '%@gmail.com'
```

```php
$users = $this->repository
    ->clausules(function ($repository) {
        return $repository->isGreaterThan(18)
            ->isEqual('genre', 'M');
    })
    ->orLike('email', '%gmail.com')
    ->fetch();
```

Find by result using `OR` and `AND`:

```sql
SELECT * FROM users WHERE (name LIKE %oe% OR email LIKE %gmail%)
AND active = 1
```

```php
$users = $this->repository
    ->clausules(function ($repository) {
        return $repository->like('%oe%')
            ->orLike('email', '%gmail%');
    })
    ->isEqual('active', 1)
    ->fetch();
```

Find by result using `AND` and `OR`:

```sql
SELECT * FROM users WHERE (age > 18 AND genre = 'M') OR
(email LIKE '%@gmail.com' OR email LIKE '%@live.com')
ORDER BY name, email DESC
```

```php
$users = $this->repository
    ->clausules(function ($repository) {
        return $repository->isGreaterThan(18)
            ->isEqual('genre', 'M');
    })
    ->orClausules(function ($repository) {
        return $repository->like('email', '%gmail.com')
            ->orLike('email', '%@live.com');
    })
    ->sortAscending('name')
    ->sortDescending('email')
    ->fetch();
```

Find by result using `INNER JOIN`:

```sql
SELECT users.id, roles.name, users.name, users.email FROM users
INNER JOIN roles ON roles.id = users.role_id
WHERE users.age >= 18
ORDER BY users.name
```

```php
$users = $this->repository
    ->showFields([
        'users.id',
        'roles.name',
        'users.name',
        'users.email',
    ])
    ->innerJoin('roles', [
        'roles.id' => 'users.role_id',
    ])
    ->greaterThanEqual('users.age', 18)
    ->sortAscending('users.name')
    ->fetch();
```

Find products without categories by result using `LEFT JOIN`:

```sql
SELECT products.id, products.name FROM products
LEFT JOIN categories ON categories.id = products.category_id
WHERE categories.id IS NULL
ORDER BY products.stock DESC
```

```php
$products = $this->productRepository
    ->showFields([
        'products.id',
        'products.name',
    ])
    ->leftJoin('categories', [
        'categories.id' => 'products.category_id',
    ])
    ->isNull('categories.id')
    ->sortDescending('products.stock')
    ->fetch();
```

Find how much the top 10 customers already spent more than $100.00 using aggregate fields:

```sql
SELECT customers.id, customers.name, SUM(orders.total) AS total_orders FROM customers
INNER JOIN orders ON orders.customer_id = customers.id
GROUP BY customers.id, customers.name
HAVING SUM(orders.total) > 100.00
ORDER BY SUM(orders.total) DESC
LIMIT 10
```

```php
$topCustomers = $this->customerRepository
    ->showFields([
        'products.id',
        'products.name',
        'SUM(orders.total) AS total_orders',
    ])
    ->innerJoin('orders', [
        'orders.customer_id' => 'customers.id',
    ])
    ->groupBy([
        'customers.id',
        'customers.name',
    ])
    ->having('SUM(orders.total)', '>', 100.00)
    ->sortDescending('SUM(orders.total)')
    ->limit(10)
    ->fetch();
```

*PS:* Queries with joins must have the table name like alias.

Create new entry in Repository

```php
$user = $this->repository->create(request()->all());
```

Update entry in Repository (object response null if not found)

```php
$user = $this->repository->update(request()->all(), $userId);
```

Delete entry in Repository

```php
$this->repository->delete($userId);
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
    ->showFields([
        'age',
        'COUNT(1) AS total_per_age',
    ])
    ->groupBy('age')
    ->sortAscending('age')
    ->debug(); // Show SQL
```

```sql
SELECT age, COUNT(1) AS total_per_age FROM users GROUP BY age ORDER BY age
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

