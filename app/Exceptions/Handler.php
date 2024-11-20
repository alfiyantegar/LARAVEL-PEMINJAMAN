<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
// Add other necessary imports

class Handler extends ExceptionHandler
{
    // Existing code...

    /**
     * Handle unauthenticated exceptions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // Check if the request expects a JSON response
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // For web requests, redirect to the login route
        return redirect()->guest(route('login'));
    }

    // Existing code...
}
