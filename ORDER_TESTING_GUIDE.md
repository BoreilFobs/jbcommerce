# 🧪 Testing Your Order System

## Quick Database Test

Run these commands to verify everything is working:

```bash
# Enter Laravel Tinker
php artisan tinker
```

Then run these tests:

### 1. Check Database Tables
```php
// Check if tables exist
Schema::hasTable('orders')
Schema::hasTable('order_items')

// Check columns
Schema::getColumnListing('orders')
Schema::getColumnListing('order_items')
```

### 2. Test Relationships
```php
// Get a user
$user = User::first();

// Test user->orders relationship
$user->orders; // Should return empty collection (no orders yet)

// Test Order model
$order = new Order();
$order->user; // Should return null (no order yet)

// Test if generateOrderNumber works
Order::generateOrderNumber(); // Should return something like "ORD-20251020-ABC123"
```

### 3. Create Test Order (Optional)
```php
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\offers;

// Get first user
$user = User::first();

// Get first product
$product = offers::first();

// Create test order
$order = Order::create([
    'user_id' => $user->id,
    'order_number' => Order::generateOrderNumber(),
    'subtotal' => 10000,
    'shipping_cost' => 2000,
    'discount_amount' => 0,
    'total_amount' => 12000,
    'status' => 'pending',
    'payment_method' => 'cash_on_delivery',
    'payment_status' => 'pending',
    'shipping_name' => 'Test Customer',
    'shipping_phone' => '+237 600000000',
    'shipping_email' => 'test@example.com',
    'shipping_address' => '123 Test Street',
    'shipping_city' => 'Douala',
    'shipping_region' => 'Littoral',
]);

// Create test order item
OrderItem::create([
    'order_id' => $order->id,
    'offer_id' => $product->id,
    'product_name' => $product->name,
    'quantity' => 2,
    'unit_price' => $product->price,
    'discount_percentage' => 0,
    'discount_amount' => 0,
    'subtotal' => $product->price * 2,
]);

// Test relationships
$order->items; // Should show the order item
$order->user; // Should show the user
$order->items->first()->offer; // Should show the product

// Test helper methods
$order->status_badge; // Should show HTML badge
$order->payment_method_name; // Should show "Paiement à la livraison"
$order->canBeCancelled(); // Should return true (status is pending)

echo "✅ Test order created successfully!";
echo "Order Number: " . $order->order_number;
```

### 4. Test User->Orders Relationship
```php
// Get the user
$user = User::first();

// Get their orders
$userOrders = $user->orders;

echo "User has " . $userOrders->count() . " orders";

// Get orders with items
$user->orders()->with('items')->get();
```

### 5. Clean Up (Delete Test Order)
```php
// Find and delete test order
$order = Order::where('order_number', 'like', 'ORD-%')->first();
if ($order) {
    $order->delete(); // Will also delete order_items (cascade)
    echo "✅ Test order deleted";
}
```

---

## Test Routes in Browser

### Customer Routes (Need to be logged in):

1. **Checkout Page**
   - URL: `http://your-domain/checkout`
   - Should show: Cart items and checkout form
   - If cart empty: Redirect with error

2. **Order History**
   - URL: `http://your-domain/orders`
   - Should show: List of user's orders
   - If no orders: Empty state

3. **Order Tracking**
   - URL: `http://your-domain/track-order`
   - Should show: Form to enter order number

### Admin Routes (Need admin account):

4. **Admin Orders List**
   - URL: `http://your-domain/admin/orders`
   - Should show: All orders with statistics
   - Filters: Status, payment status, date range, search

5. **Admin Order Details**
   - URL: `http://your-domain/admin/orders/1`
   - Should show: Full order details
   - Actions: Update status, payment, tracking

---

## Expected Results

### ✅ What Should Work:

1. **Database:**
   - ✅ `orders` table created with all fields
   - ✅ `order_items` table created with all fields
   - ✅ Foreign keys working
   - ✅ Indexes created

2. **Models:**
   - ✅ Order model with relationships
   - ✅ OrderItem model with relationships
   - ✅ User model has orders() relationship
   - ✅ Offer model has orderItems() relationship

3. **Routes:**
   - ✅ 13 order routes registered
   - ✅ 2 checkout routes registered
   - ✅ Middleware protection working

4. **Controllers:**
   - ✅ CheckoutController with index() and process()
   - ✅ OrderController with customer methods
   - ✅ Admin\OrderController with admin methods

### ❌ What Won't Work Yet (Views Not Created):

- ❌ Cannot view checkout page (view doesn't exist)
- ❌ Cannot view order history (view doesn't exist)
- ❌ Cannot view order details (view doesn't exist)
- ❌ Cannot view admin orders (view doesn't exist)

But the **backend is 100% ready**!

---

## Common Issues & Solutions

### Issue: "orders() method not found"
**Solution:** This is just an IDE warning. The relationship works at runtime because we added it to the User model.

### Issue: "Table doesn't exist"
**Solution:** Run migrations: `php artisan migrate`

### Issue: "Class not found"
**Solution:** Clear cache: `php artisan cache:clear && composer dump-autoload`

### Issue: "Route not found"
**Solution:** Clear route cache: `php artisan route:clear`

---

## Next Steps

Now that the backend is complete, you need to create the views:

1. **Start with Checkout Form** (Most important)
   - File: `resources/views/checkout/index.blade.php`
   - Show cart summary, shipping form, payment selection

2. **Order Confirmation Page**
   - File: `resources/views/orders/confirmation.blade.php`
   - Thank you message, order number, next steps

3. **Order History Page**
   - File: `resources/views/orders/index.blade.php`
   - List of user's orders with status

4. **Order Details Page**
   - File: `resources/views/orders/show.blade.php`
   - Full order information

5. **Admin Order Management**
   - File: `resources/views/admin/orders/index.blade.php`
   - List all orders with filters
   - File: `resources/views/admin/orders/show.blade.php`
   - Admin order details with actions

---

**Status:** ✅ Backend Complete | ⏳ Views Pending

Would you like me to create the views next?
