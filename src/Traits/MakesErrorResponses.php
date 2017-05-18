<?php

namespace OneGiba\DataLayer\Traits;

use OneGiba\DataLayer\Exceptions\OnCreatingException;
use OneGiba\DataLayer\Exceptions\OnDeletingException;
use OneGiba\DataLayer\Exceptions\OnUpdatingException;

trait MakesErrorResponses
{
    /**
     * Throws On Creating Exception
     *
     * @param array $errors
     * @param null|string $message
     * @throws \OneGiba\DataLayer\Exceptions\OnCreatingException
     */
    public function throwErrorOnCreating($errors = [], $message = null)
    {
        throw new OnCreatingException($errors, $message);
    }

    /**
     * Throws On Deleting Exception
     *
     * @param array $errors
     * @param null|string $message
     * @throws \OneGiba\DataLayer\Exceptions\OnDeletingException
     */
    public function throwErrorOnDeleting($errors = [], $message = null)
    {
        throw new OnDeletingException($errors, $message);
    }

    /**
     * Throws On Updating Exception
     *
     * @param array $errors
     * @param null|string $message
     * @throws \OneGiba\DataLayer\Exceptions\OnUpdatingException
     */
    public function throwErrorOnUpdating($errors = [], $message = null)
    {
        throw new OnUpdatingException($errors, $message);
    }
}
