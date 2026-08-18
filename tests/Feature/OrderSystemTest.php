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

    public function test_cashier_can_access_pos_screen(): void
    {
        $cashier = User::create([
            'name' => 'Kasir POS',
            'email' => 'pos@test.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        $response = $this->actingAs($cashier)->get(route('cashier.pos'));
        $response->assertStatus(200);
        $response->assertSee('Kasir POS Terminal');
        $response->assertSee('Es Kopi Susu');
    }

    public function test_cashier_can_checkout_direct_pos_order_with_cash(): void
    {
        $cashier = User::create([
            'name' => 'Kasir POS 2',
            'email' => 'pos2@test.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        $cartItems = [
            [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'qty' => 3,
                'notes' => 'Less sugar',
            ]
        ];

        $response = $this->actingAs($cashier)->postJson(route('cashier.pos.checkout'), [
            'customer_name' => 'Doni Walk-in',
            'order_type' => 'dine_in',
            'table_id' => $this->table->id,
            'cart_items' => $cartItems,
            'payment_method' => 'cash',
            'cash_received' => 50000,
            'order_action' => 'send_kitchen',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Doni Walk-in',
            'total_amount' => 45000, // 15000 * 3 = 45000
            'payment_method' => 'cash',
            'payment_status' => 'PAID',
            'order_status' => 'WAITING_KITCHEN',
        ]);

        $order = Order::where('customer_name', 'Doni Walk-in')->first();
        $this->assertNotNull($order->payment);
        $this->assertEquals(50000, $order->payment->payload['cash_received']);
        $this->assertEquals(5000, $order->payment->payload['cash_change']);
    }

    public function test_cashier_can_checkout_takeaway_order_without_table(): void
    {
        $cashier = User::create([
            'name' => 'Kasir Takeaway',
            'email' => 'takeaway@test.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        $cartItems = [
            [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'qty' => 1,
            ]
        ];

        $response = $this->actingAs($cashier)->postJson(route('cashier.pos.checkout'), [
            'customer_name' => 'Siti Bungkus',
            'order_type' => 'takeaway',
            'table_id' => null,
            'cart_items' => $cartItems,
            'payment_method' => 'qris',
            'order_action' => 'direct_complete',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Siti Bungkus',
            'table_id' => null,
            'payment_method' => 'qris',
            'payment_status' => 'PAID',
            'order_status' => 'COMPLETED',
        ]);
    }

    public function test_cashier_can_confirm_cash_payment_with_change_for_qr_order(): void
    {
        $cashier = User::create([
            'name' => 'Kasir Confirm',
            'email' => 'confirm@test.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        $order = Order::create([
            'order_number' => 'ORD-20260814-0099',
            'table_id' => $this->table->id,
            'customer_name' => 'Andi QR',
            'total_amount' => 30000,
            'payment_method' => 'cash',
            'payment_status' => 'UNPAID',
            'order_status' => 'PENDING',
        ]);

        $response = $this->actingAs($cashier)->postJson(route('cashier.orders.confirm-cash', $order->id), [
            'cash_received' => 50000,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'cash_received' => 50000,
            'cash_change' => 20000,
        ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'PAID',
        ]);
    }

    public function test_cashier_can_view_receipt(): void
    {
        $cashier = User::create([
            'name' => 'Kasir Receipt',
            'email' => 'receipt@test.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        $order = Order::create([
            'order_number' => 'ORD-20260814-0100',
            'table_id' => $this->table->id,
            'customer_name' => 'Rina',
            'total_amount' => 15000,
            'payment_method' => 'cash',
            'payment_status' => 'PAID',
            'order_status' => 'WAITING_KITCHEN',
        ]);

        $response = $this->actingAs($cashier)->get(route('cashier.orders.receipt', $order->id));
        $response->assertStatus(200);
        $response->assertSee('CAFE SELF-ORDERING');
        $response->assertSee('ORD-20260814-0100');
    }
}
