<?php

namespace App\Http\Middleware;

use Closure;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Idempotency;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class IdempotencyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->header('x-idempotency-key') ?? $request->input('X-Idempotency-Key');

        if (!$key) {
            return $next($request);
        }

        $idempotency = Idempotency::where('idempotency_key', $key)->first();
        
        if ($idempotency && $idempotency->status === 'completed') {
            Log::info('Idempotencia: Redirigiendo duplicado con mensaje guardado.');
            return redirect($idempotency->redirect_url)
                   ->with($idempotency->key, $idempotency->message);
        }

        $lock = Cache::lock('lock:idempotency:' . $key, 10);

        if (!$lock->get()) {
            Log::info('Idempotencia: Petición ignorada por duplicidad activa.');
            return back()->with('error', 'Tu solicitud se está procesando, no es necesario hacer clic de nuevo.');
        }

        try {
            $idempotency = Idempotency::where('idempotency_key', $key)->first();
            
            if ($idempotency && $idempotency->status === 'completed') {
                return redirect($idempotency->redirect_url)->with($idempotency->key, $idempotency->message);
            }

            if (!$idempotency) {
                $idempotency = Idempotency::create([
                    'idempotency_key' => $key,
                    'status' => 'processing'
                ]);
            }

            $response = $next($request);

            if ($response instanceof \Illuminate\Http\RedirectResponse) {
                $flashKey = session()->has('success') ? 'success' : 'error';
                $message = session()->get('success') ?? session()->get('error');

                Idempotency::where('id', $idempotency->id)->update([
                    'status' => 'completed',
                    'redirect_url' => $response->getTargetUrl(),
                    'key' => $flashKey,
                    'message' => $message
                ]);
                Log::info('Idempotencia: Petición completada exitosamente.');
            } else {
                Idempotency::where('id', $idempotency->id)->delete();
            }

            return $response;

        } catch (Throwable $e) {
            if (isset($idempotency)) {
                Idempotency::where('id', $idempotency->id)->delete();
            }
            throw $e;
        } finally {
            $lock->release();
        }
    }
}