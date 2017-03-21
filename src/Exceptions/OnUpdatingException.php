<?php

namespace OneGiba\DataLayer\Exceptions;

class OnUpdatingException extends BaseException
{
    /**
     * @param array   $errors
     * @param string  $message
     * @param integer $code
     * @return void
     */
    public function __construct(
        $errors = [],
        $message = 'An error occurs while updating data',
        $code = 500
    ) {
        parent::__construct($errors, $message, $code);
    }
}
