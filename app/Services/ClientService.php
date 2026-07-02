<?php

namespace App\Services;
use App\Models\Client;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ClientService
{
    public function getClients(int $perPage = 10): LengthAwarePaginator
    {
        return Client::paginate($perPage);
    }
    public function createClient(array $data)
    {  
        return DB::transaction(function () use ($data) {
            return Client::create($data);
        });
        
    }
    public function updateClient(Client $client, array $data)
    {
        return DB::transaction(function () use ($client, $data) {
            return  $client->update($data);
        });
    }
    public function getClientById(int $id): Client
    {
        return Client::findOrFail($id);
    }
}