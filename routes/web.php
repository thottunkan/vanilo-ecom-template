<?php

use App\Http\Controllers\Shop\Api\ApiCartController;
use App\Http\Controllers\Shop\Api\ApiWishlistController;
use App\Http\Controllers\Shop\AuthController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\FilterController;
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\OrderController;
use App\Http\Controllers\Shop\PasswordResetOtpController;
use App\Http\Controllers\Shop\ShopController;
use App\Http\Controllers\Shop\WishlistController;
use App\Utils\ProductUtility;
use Illuminate\Support\Facades\Route;

use Vanilo\Cart\Facades\Cart;
use Vanilo\Category\Models\Taxon;
use Vanilo\Foundation\Models\MasterProduct;
use Vanilo\Order\Contracts\OrderFactory;

include 'admin_routes.php';


Route::get('/', [HomeController::class, 'index'])->name('shop.home');
Route::get('/products', [ShopController::class, 'index'])->name('shop.products');
Route::get('/products/search', [ShopController::class, 'search'])->name('shop.search');
Route::get('/category/{slug}', [ShopController::class, 'index'])->name('shop.category');
Route::get('/product/{slug}', [ShopController::class, 'show'])->name('shop.product.show');

Route::get('/login', [AuthController::class, 'showLogin'])->name('shop.login');
Route::post('/login', [AuthController::class, 'login'])->name('shop.login.store');
Route::get('/register', [AuthController::class, 'showRegister'])->name('shop.register');
Route::post('/register', [AuthController::class, 'register'])->name('shop.register.store');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('customer')->name('shop.logout');
Route::post('/sign-out', [AuthController::class, 'signOut'])->middleware('auth:sanctum');

Route::prefix('password')->group(function () {
    Route::post('/otp/send', [PasswordResetOtpController::class, 'sendOtp']);
    Route::post('/otp/verify', [PasswordResetOtpController::class, 'verifyOtp']);
    Route::post('/reset', [PasswordResetOtpController::class, 'resetPassword']);
});

Route::middleware(['customer','api-customer'])->prefix('cart')->name('shop.cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    //Route::post('/add', [CartController::class, 'add'])->name('add');
    //Route::post('cart/add', [ApiCartController::class, 'add'])->name('add');
    Route::post('add', [ApiCartController::class, 'add'])->name('add');
    Route::patch('/{itemId}', [CartController::class, 'update'])->name('update');
    Route::delete('/{itemId}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/', [CartController::class, 'clear'])->name('clear');
    Route::get('/count', [CartController::class, 'count'])->name('count');
});

Route::middleware('customer')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('shop.wishlist');
    // Route::post('/wishlist', [WishlistController::class, 'addToWishlist'])->name('shop.wishlist.add');
    Route::post('/addtowishlist', [ApiWishlistController::class, 'addToWishlist'])->name('shop.add-to-wishlist');
    Route::get('wishlist-count', [ApiWishlistController::class,'count'])->name('shop.wishlist.count');
    Route::delete('/wishlist/{productId}', [WishlistController::class, 'delete'])->name('shop.wishlist.delete');
    Route::get('/account', fn () => view('shop.account.index', ['site' => \App\Models\SiteInfo::first()]))->name('shop.account');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('shop.checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('shop.checkout.store');
    Route::get('/checkout/success/{orderId}', [CheckoutController::class, 'success'])->name('checkout.success');

    Route::get('/orders',[OrderController::class,'index'])->name('shop.orders');

    Route::get('change-password',[AuthController::class,'showChangePassword'])->name('shop.showchange-password');
    Route::put('change-password',[AuthController::class,'chagePassword'])->name('account.password.update');
});

Route::get('/token', fn () => response()->json(['token' => csrf_token()]));

// Route::get('/cart-items', function () {
//     $cart = Cart::model();
//     return response()->json([
//         'cart_id' => $cart?->id,
//         'state' => $cart?->state,
//         'user_id' => $cart?->user_id,
//         'items' => Cart::getItems()->map(function ($item) {
//             $product = $item->getBuyable();
//             return ['id'=>$item->id,'product_id'=>$product?->id,'name'=>$product?->name,'price'=>$item->price,'quantity'=>$item->quantity];
//         })->values(),
//     ]);
// });


// Route::prefix('/')->name('shop.')->group(function () {
//   //  Route::get('/products', [FilterController::class, 'filterProducts'])->name('products');
//     // Route::post('cart/add', [ApiCartController::class, 'add'])->name('cart.add');
//     // Route::post('/addtowishlist', [ApiWishlistController::class, 'addToWishlist'])->name('add-to-wishlist');
// });

Route::get('/token', function () {
    return response()->json([
        'token' => csrf_token()
    ]);
});

Route::get('cart-items', function () {
    $cart = Cart::model();

    return response()->json([
        'cart_id' => $cart?->id,
        'state' => $cart?->state,
        'user_id' => $cart?->user_id,
        'items' => $cart?->items->map(function ($item) {
            $product = $item->product;

            return [
                'id' => $item->id,
                'product_id' => $product?->id,
                'name' => $product?->name,
                'price' => $product?->price,
                'image' => $product?->image,
                'quantity' => $item->quantity,
            ];
        })->values(),
    ]);
});

Route::get('/order', function () {
    $factory = app(OrderFactory::class);
    $master_product_id = 14;
    $master_product_varient_id = null;
    $buyable = ProductUtility::has_product_varients($master_product_id, $master_product_varient_id);
    $type = get_class($buyable);
    if ($type == 'Vanilo\MasterProduct\Models\MasterProduct') {
        $type = 'master_product';
    } else {
        $type = 'master_product_varient';
    }

    $order = $factory->createFromDataArray([], [
        // [
        //     'user_id' => auth()->id(), // Order attributes
        // ],
        [
            'product' => $buyable,
            'product_type' => $type,
            'product_id' => $buyable->id,
            'price' => $buyable->price,
            'configuration' => [
                'color' => 'Red',
                'size' => 'M',
                'tax' => 18.00,
                'grand_total' => 0
            ],
        ],
        [
            'product' => $buyable,
            'product_type' => $type,
            'product_id' => $buyable->id,
            'price' => $buyable->price,
            'configuration' => [
                'color' => 'Red',
                'size' => 'XL',
                'tax' => 200.00,
                'grand_total' => 123
            ],
        ]
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Order placed successfully..!',
        'order' => $order,
    ]);

});


//Route::post('/add_to_cart', [CartController::class, 'add'])->name('shop.cart.add');
