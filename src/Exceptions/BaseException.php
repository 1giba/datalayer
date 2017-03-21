<?php

namespace OneGiba\DataLayer\Exceptions;

use Exception;

class BaseException extends Exception
{
    /**
     * @var array
     */
    protected $errors;

    /**
     * @param array   $errors
     * @param string  $message
     * @param integer $code
     * @return void
     */
    public function __construct(
        $errors = [],
        $message = 'Exception founded',
        $code = 500
    ) {
        parent::__construct($message, $code);

        $this->errors = $errors;
    }

    /**
     * Get all errors
     *
     * @return array
     */
    public function all()
    {
        return $this->errors;
    }
}
