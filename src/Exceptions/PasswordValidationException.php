<?php

namespace Administration\Exceptions;

use Exception;

class PasswordValidationException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->view(
            'errors.custom',
            array(
                'exception' => $this
            )
        );
    }
}
