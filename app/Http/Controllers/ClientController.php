<?php

namespace App\Http\Controllers;
use App\Services\ClientService;
use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClient;
use Illuminate\Support\Facades\Log;
use App\Models\Client;
use Illuminate\Database\QueryException;
class ClientController extends Controller
{
    protected $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }
    public function index()
    {
        try {

            $clients = $this->clientService->getClients();
            return view('pages.clients.index', ['clients' => $clients]);
        } catch (\Exception $e) {
            Log::error('Fallo critico en el registro', [
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ]);
            return back()->withErrors('Hubo un problema al cargar los clientes.');
        }
    }
    public function create()
    {
        return view('pages.clients.create');
    }

    public function store(StoreClientRequest $request)
    {
        try {
            $this->clientService->createClient($request->validated());
            return redirect()->route('clients.index')->with('success', 'Cliente Registrado correctamente');
        } catch (QueryException $e) {
            Log::error('Fallo de base de datos en el registro', [
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ]);

            $isUniqueViolation = ($e->getCode() === '23000') ||
                (isset($e->errorInfo[1]) && $e->errorInfo[1] === 19);

            if ($isUniqueViolation) {
                return back()
                    ->withInput()
                    ->withErrors(['phone' => 'El número de teléfono ya está registrado con otro cliente.']);
            }

            return back()->withInput()->withErrors(['error' => 'Ocurrió un problema de consistencia en los datos.']);

        } catch (\Exception $e) {
            Log::error('Fallo critico en el registro', [
                'error' => $e->getMessage(),
                'linea' => $e->getLine()
            ]);

            return back()->withInput()->withErrors(['error' => 'Ocurrio un error inesperado al registrar...']);
        }
    }

    public function edit(Client $client)
    {

        return view('pages.clients.edit', ['client' => $client]);
    }
    public function update(UpdateClient $request, Client $client)
    {
        try {
            $this->clientService->updateClient($client, $request->validated());
            return redirect()->route('clients.index')->with('success', 'Cliente Actualizado correctamente');
        } catch (\Exception $e) {
            Log::error('Fallo critico al actualizar cliente', [
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ]);

            return back()->withInput()->withErrors(['error' => 'Ocurrio un error al actualizar... operaciones canceladas para proteger la integridad de los datos, contactar a jeider solano de inmediato']);
        }
    }
    public function softDelete(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Cliente Eliminado correctamente');
    }

}
