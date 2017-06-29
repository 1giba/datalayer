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
    public function __construct(User $user)
    {
        parent::__construct($user);
    }
}

// Instanciates the user repository
$userRepository = new UserRepository(new User);

// Find an user by id
$user = $userRepository->findById(1234);

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
    public function findAll(array $columns = ['*'], array $sortableFields = ['id'], $limit = 0);

    public function findFirst(array $conditions = [], $columns = ['*']);

    public function findById($resourceId, $columns = ['*']);

    public function findBy(array $conditions, array $columns = ['*'], array $sortableFields = ['id'], $limit = 0);

    public function paginate($perPage, array $conditions = [], array $columns = ['*'], array $sortableFields = ['id']);

    public function create(array $data);

    public function update(array $data, $resourceId);

    public function delete($resourceId);
}
```

## License

Datalayer is released under the MIT Licence.

