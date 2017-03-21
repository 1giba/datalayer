<?php

namespace OneGiba\DataLayer\Exceptions;

class OnDeletingException extends BaseException
{
    /**
     * @param array   $errors
     * @param string  $message
     * @param integer $code
     * @return void
     */
    public function __construct(
        $errors = [],
        $message = 'An error occurrs while deleting the registry',
        $code = 500
    ) {
        parent::__construct($errors, $message, $code);
    }
}
