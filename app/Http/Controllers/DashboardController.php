<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
         
            $totalDebt = Order::sum('total_amount');
            $totalPaid = Payment::sum('amount');
            $pendingDebt = $totalDebt - $totalPaid;

           
            $lowStockProducts = Product::with('category')
                ->where('stock', '<=', 5)
                ->orderBy('stock', 'asc')
                ->take(5)
                ->get();

          
            $topProducts = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('total_sold')
                ->take(5)
                ->get();

         
            $recentPayments = Payment::with(['order.client'])
                ->orderByDesc('created_at')
                ->take(5)
                ->get();

           
            if ($request->has('refresh')) {
                Cache::forget('gemini_dashboard_report');
            }

            $aiReport = Cache::remember('gemini_dashboard_report', now()->addHours(12), function () use ($lowStockProducts, $topProducts, $pendingDebt) {
                return $this->generateAiReport($lowStockProducts, $topProducts, $pendingDebt);
            });

            Log::info('Reporte final asignado a la vista:', $aiReport);

            return view('dashboard.index', compact(
                'totalDebt',
                'totalPaid',
                'pendingDebt',
                'lowStockProducts',
                'topProducts',
                'recentPayments',
                'aiReport'
            ));

        } catch (QueryException $e) {
            Log::error('Fallo de base de datos en la carga del Dashboard', [
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ]);
            return back()->withErrors(['error' => 'Error de consistencia de datos al cargar las estadísticas del servidor.']);

        } catch (\Exception $e) {
            Log::error('Fallo crítico en el Dashboard', [
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ]);
            return back()->withErrors(['error' => 'Ocurrió un error inesperado al procesar el reporte general.']);
        }
    }

    private function generateAiReport($lowStock, $topProducts, $pendingDebt)
    {
        Log::info('Ejecutando petición real a la API de Gemini...');
        try {
            $apiKey = config('services.gemini.key');

            if (!$apiKey) {
                Log::warning('No se detectó API Key de Gemini');
                return $this->getMockAiFallback();
            }

            $dataContext = [
                'cartera_pendiente_total' => $pendingDebt,
                'alertas_stock_critico' => $lowStock->map(fn($p) => ['nombre' => $p->name, 'stock_actual' => $p->stock]),
                'productos_mas_vendidos' => $topProducts
            ];


            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent?key={$apiKey}";

            $response = Http::timeout(60)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'systemInstruction' => [
                        'parts' => [['text' => $this->getSystemPrompt()]]
                    ],
                    'contents' => [
                        ['parts' => [['text' => "Datos en JSON: " . json_encode($dataContext)]]]
                    ],
                    'generationConfig' => [
                        'responseMimeType' => 'application/json',
                        'temperature' => 0.2, 
                  
                        'thinkingConfig' => ['includeThoughts' => false] 
                    ]
                ]);

            if ($response->successful()) {
                $result = $response->json();
                $rawText = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;

                if ($rawText) {
                    Log::info('Respuesta exitosa de Gemini');
                    $data = json_decode(trim($rawText), true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        return $data;
                    }
                    Log::error('Gemini devolvió texto pero no es un JSON válido: ' . $rawText);
                }
            }

            Log::error('Error en API Gemini', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return $this->getMockAiFallback();

        } catch (\Exception $e) {
            Log::error('Excepción al conectar con Gemini', ['msg' => $e->getMessage()]);
            return $this->getMockAiFallback();
        }
    }

    private function getSystemPrompt()
    {
        return "Eres una API que responde SOLO con JSON. No incluyas explicaciones, no incluyas markdown, no incluyas pensamientos, no incluyas bloques de código como ```json. Tu respuesta debe ser únicamente el objeto JSON solicitado:
    {
        \"resumen_ejecutivo\": \"string\",
        \"alertas_criticas\": [{\"producto\": \"string\", \"razon\": \"string\"}],
        \"predicciones_restock\": [{\"producto\": \"string\", \"fecha_estimada_sugerida\": \"string\", \"cantidad_sugerida\": \"string\"}],
        \"recomendaciones_estrategicas\": [{\"titulo\": \"string\", \"descripcion\": \"string\"}]
    }";
    }

    private function getMockAiFallback()
    {
        return [
            "resumen_ejecutivo" => "El sistema registra deudas activas pendientes de recaudo. Se sugiere priorizar la cobranza y revisar insumos próximos a agotarse.",
            "alertas_criticas" => [["producto" => "Insumos base", "razon" => "Nivel de stock bajo mínimos óptimos."]],
            "predicciones_restock" => [["producto" => "Productos top", "fecha_estimada_sugerida" => "Próximos 2 días", "cantidad_sugerida" => "Aumentar un 20%"]],
            "recomendaciones_estrategicas" => [["titulo" => "Control preventivo", "descripcion" => "Monitorear el flujo de caja e inyectar el capital recuperado en restock inmediato."]]
        ];
    }
}