<?php

namespace OneGiba\DataLayer\Exceptions;

class OnCreatingException extends BaseException
{
    /**
     * @param array   $errors
     * @param string  $message
     * @param integer $code
     * @return void
     */
    public function __construct(
        $errors = [],
        $message = 'An error occurrs while creating the registry',
        $code = 500
    ) {
        parent::__construct($errors, $message, $code);
    }
}
