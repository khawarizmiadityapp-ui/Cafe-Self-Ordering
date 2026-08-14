<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic table & product data
        $this->table = Table::create([
            'table_number' => '07',
            'name' => 'Meja 07',
            'code' => '07',
            'status' => 'active',
        ]);

        $this->category = Category::create([
            'name' => 'Kopi',
            'slug' => 'kopi',
            'is_active' => true,
        ]);

        $this->product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Es Kopi Susu',
            'slug' => 'es-kopi-susu',
            'price' => 15000,
            'is_available' => true,
        ]);
    }

    public function test_customer_can_view_menu_with_table_query(): void
    {
        $response = $this->get('/order?table=07');
        $response->assertStatus(200);
        $response->assertSee('Meja 07');
        $response->assertSee('Es Kopi Susu');
    }

    public function test_customer_can_place_cash_order_and_server_recalculates_price(): void
    {
        $cartPayload = json_encode([
            [
                'id' => $this->product->id,
                'name' => 'Tampered Name',
                'qty' => 2,
                'price' => 1, // Tampered frontend price! Server must ignore this!
                'notes' => 'Less ice',
            ]
        ]);

        $response = $this->post('/order/checkout', [
            'table_id' => $this->table->id,
            'customer_name' => 'Rizmi',
            'payment_method' => 'cash',
            'cart_items' => $cartPayload,
        ]);

        $this->assertDatabaseHas('orders', [
            'table_id' => $this->table->id,
            'customer_name' => 'Rizmi',
            'total_amount' => 30000, // 15000 * 2 = 30000
            'payment_method' => 'cash',
            'payment_status' => 'UNPAID',
            'order_status' => 'PENDING',
        ]);

        $order = Order::where('customer_name', 'Rizmi')->first();
        $response->assertRedirect(route('customer.order.status', ['order_number' => $order->order_number]));
    }

    public function test_cashier_can_confirm_payment_and_send_to_kitchen(): void
    {
        $cashier = User::create([
            'name' => 'Kasir Test',
            'email' => 'kasir@test.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        $order = Order::create([
            'order_number' => 'ORD-20260814-0001',
            'table_id' => $this->table->id,
            'customer_name' => 'Rizmi',
            'total_amount' => 30000,
            'payment_method' => 'cash',
            'payment_status' => 'UNPAID',
            'order_status' => 'PENDING',
        ]);

        // Cashier confirms cash payment
        $response = $this->actingAs($cashier)->post(route('cashier.orders.confirm-payment', $order->id));
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'PAID',
        ]);

        // Cashier sends order to kitchen
        $response = $this->actingAs($cashier)->post(route('cashier.orders.send-kitchen', $order->id));
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => 'WAITING_KITCHEN',
        ]);
    }

    public function test_admin_can_export_reports_to_excel(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        Order::create([
            'order_number' => 'ORD-20260814-0002',
            'table_id' => $this->table->id,
            'customer_name' => 'Budi',
            'total_amount' => 45000,
            'payment_method' => 'qris',
            'payment_status' => 'PAID',
            'order_status' => 'COMPLETED',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.reports.export'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertTrue(str_contains($response->headers->get('Content-Disposition'), 'Laporan_Penjualan_Cafe'));
    }
}
