<?php

namespace OneGiba\DataLayer\Exceptions;

class NotFoundException extends BaseException
{
    /**
     * @param array   $errors
     * @param string  $message
     * @param integer $code
     * @return void
     */
    public function __construct(
        $errors = [],
        $message = 'Not Found',
        $code = 404
    ) {
        parent::__construct($errors, $message, $code);
    }
}
