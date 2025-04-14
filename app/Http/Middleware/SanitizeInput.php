<?php

namespace App\Http\Middleware;

use Closure;

class SanitizeInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {


        if ($request->has('email')) {
            $email = $this->sanitizeEmail($request->input('email'));

            $request->merge(['email' => $email]);
        }

        return $next($request);
    }

    /**
     * Sanitize the email address.
     *
     * @param  string  $email
     * @return string
     */
    protected function sanitizeEmail($email)
    {
        // Trim the email, remove spaces, new lines, etc.
        $email = trim($email);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        return $email;
    }
}
