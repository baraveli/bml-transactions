<?php

namespace Baraveli\BMLTransaction\Exceptions;

use Exception;

class AuthenticationFailedException extends Exception
{
    protected $message = 'failed to authenticate with bml api.';
}
