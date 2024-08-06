<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticatedSystemMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // Example license check logic
        $licenseKey = config('app.app_license_key');

        if ($this->isLicenseValid($licenseKey)) {
            return $next($request);
        }

        return response()->json(['error' => 'Invalid license key'], 403);
    }

    /**
     * Check if the license key is valid.
     *
     * @param string $licenseKey
     * @return bool
     */
    protected function isLicenseValid($licenseKey): bool
    {
        // Implement your license validation logic here
        // This is just an example
        return $licenseKey === 'valid-license-key';
    }
}
