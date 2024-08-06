<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
        // license check logic
        $licenseKey = config('app.app_license_key');

        $domain = $request->getSchemeAndHttpHost(); // Get the domain with scheme//$request->getHost(); // Get the domain from the request
        $validity = $this->isLicenseValid($licenseKey, $domain);

        if ($validity['status']) {
            return $next($request);
        }
        $message = [
            'name' => 'license',
            'message' => __( $validity['message'] ),
            'status' => 'error',
        ];
        return response()->json( $message, 403 );
    }

    /**
     * Check if the license key is valid.
     *
     * @param string $licenseKey
     * @return array
     */
    protected function isLicenseValid($licenseKey, $domain): array
    {
        $url = ns()->envEditor->get('APP_LICENSE_MANAGER', 'http://127.0.0.1:8888').'/api/validate/license';

        try {
            $response = Http::get($url, [
                'domain' => $domain,
                'license_key' => $licenseKey,
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                return $responseData;
            } else {
                return $response->json();
            }
        } catch (\Exception $e) {
            return
                [
                'status'=> false,
                'message'=>'License validation request failed with: '. $e->getMessage()
            ];
        }
    }
}
