# Datalayer

> A simplest abstraction layer for eloquent models

## Installation

```sh
$ composer require 1giba/datalayer
```

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

## Interface

```php
<?php

namespace OneGiba\DataLayer\Contracts;

interface RepositoryInterface
{
    /**
     * First row
     *
     * @param array $columns
     * @param array $conditions
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function first(array $columns = ['*'], array $conditions = []): ?Model;

    /**
     * Get collection
     *
     * @return \Illuminate\Support\Collection
     */
    public function get(): Collection;

    /**
     * Paginate data
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate(int $perPage = 50): LengthAwarePaginator;

    /**
     * New entry
     *
     * @param array $fillable
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function create(array $fillable): ?Model;

    /**
     * Updates data
     *
     * @param array $fillable
     * @param int $resourceId
     * @return mixed
     */
    public function update(array $fillable, int $resourceId);

    /**
     * Removes data
     *
     * @param int $resourceId
     * @return int
     */
    public function delete(int $resourceId): int;
}
```

## License

Datalayer is released under the MIT Licence.

