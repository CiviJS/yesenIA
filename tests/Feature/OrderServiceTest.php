<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_order_persists_items_with_polymorphic_relation(): void
    {
        $client = Client::create([
            'name' => 'Cliente prueba',
            'phone' => '3000000000',
            'address' => 'Calle 123',
        ]);

        $category = ProductCategory::create([
            'name' => 'Categoría prueba',
        ]);

        $product = Product::create([
            'name' => 'Producto prueba',
            'price' => 5000,
            'stock' => 100,
            'product_category_id' => $category->id,
        ]);

        $service = app(OrderService::class);

        $order = $service->createOrder([
            'client_id' => $client->id,
            'items' => [[
                'product_id' => $product->id,
                'quantity' => 50,
                'unit_price' => 5000,
            ]],
        ]);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'client_id' => $client->id,
        ]);
        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 50,
            'unit_price' => '5000.00',
            'orderable_id' => $order->id,
            'orderable_type' => Order::class,
        ]);
    }
}
