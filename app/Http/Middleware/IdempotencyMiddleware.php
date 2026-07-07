<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class IdempotencyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {

        if (in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'])) {
            return $next($request);
        }

    
        $idempotencyKey = $request->header('Idempotency-Key');

        if (!$idempotencyKey) {
            return $next($request); 
        }

        $cacheKey = "idempotency:{$idempotencyKey}";

        if (Cache::has($cacheKey)) {
            $cachedData = Cache::get($cacheKey);

            if ($cachedData === 'processing') {
              
                return response()->json([
                    'error' => 'La petición ya se está procesando. Inténtalo de nuevo en unos segundos.'
                ], 409); // 409 Conflict
            }

         
            return response()->json($cachedData['content'], $cachedData['status'], $cachedData['headers']);
        }

     
        Cache::put($cacheKey, 'processing', 60); 

        
        $response = $next($request);

        
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 500) {
            $dataToCache = [
                'status' => $response->getStatusCode(),
                'headers' => array_merge($response->headers->all(), ['X-Cache-Idempotent' => 'true']),
                'content' => json_decode($response->getContent(), true),
            ];

            Cache::put($cacheKey, $dataToCache, 86400);
        } else {

            Cache::forget($cacheKey);
        }

        return $response;
    }
}