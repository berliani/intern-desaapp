<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Company;

class SubdomainMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        
        $host = $request->getHost();


        $parts = explode('.', $host);


        if (count($parts) > 2) {
            $subdomain = $parts[0];

            $company = Company::where('subdomain', $subdomain)->first();

            if ($company) {
                $request->attributes->add(['company' => $company]);
            }
        }


        return $next($request);
    }
}
