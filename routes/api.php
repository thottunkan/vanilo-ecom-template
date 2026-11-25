<?php
use App\Http\Controllers\Shop\Api\ApiAddressController;
use App\Http\Controllers\Shop\Api\ApiAuthController;
use App\Http\Controllers\Shop\Api\ApiCartController;
use App\Http\Controllers\Shop\Api\ApiMenuController;
use App\Http\Controllers\Shop\Api\ApiNewsletterController;
use App\Http\Controllers\Shop\Api\ApiOrderController;
use App\Http\Controllers\Shop\Api\ApiOrderReturnController;
use App\Http\Controllers\Shop\Api\ApiOrderStatusHistoryController;
use App\Http\Controllers\Shop\Api\ApiPageContentController;
use App\Http\Controllers\Shop\Api\ApiPolicyController;
use App\Http\Controllers\Shop\Api\ApiProductController;
use App\Http\Controllers\Shop\Api\ApiProfileController;
use App\Http\Controllers\Shop\Api\ApiReviewController;
use App\Http\Controllers\Shop\Api\ApiShopController;
use App\Http\Controllers\Shop\Api\ApiSiteInfoController;
use App\Http\Controllers\Shop\Api\ApiWishlistController;
use App\Http\Controllers\Shop\CategoryController;
use App\Http\Controllers\Shop\PasswordResetOtpController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'api-customer'])->group(function () {

    #profile  
    Route::get('customer/profile', [ApiProfileController::class, 'getProfile'])->name('api.shop.prifile');
    Route::put('customer/profile/{id}/update', [ApiProfileController::class, 'updateProfile'])->name('api.shop.updateprofile');
    Route::put('customer/profile/change-password', [ApiProfileController::class, 'changePassword']);

    #cart
    Route::get("cart/all",[ApiCartController::class,"getcartItems"])->name("apo.cart.list");
    Route::post('cart/add', [ApiCartController::class, 'add'])->name('api.cart.add');
    Route::get('cart/clear', [ApiCartController::class, 'clearCartItems'])->name('api.cartiten.clear');
    Route::put('cart/{prod_id}/remove', [ApiCartController::class, 'remove'])->name('api.cartitem.remove');
    Route::get('cart/delete', [ApiCartController::class, 'deleteCart'])->name('api.cart.delete');

    #address
    Route::get('', [ApiAddressController::class,'all'])->name('shop.api.address.all');
    Route::post('/address/add', [ApiAddressController::class, 'add'])->name('shop.api.address.add');
    Route::put('/address/update/{id}', [ApiAddressController::class, 'update'])->name('shop.api.address.update');
    Route::delete('/address/delete/{id}', [ApiAddressController::class, 'delete'])->name('shop.api.address.delete');
    Route::get('/address/all', [ApiAddressController::class,'getCustomerAddress'])->name('shop.api.address.get');

    #review
    Route::get('/reviews', [ApiReviewController::class,'getReviewsByUser'])->name('shop.api.review.myreviews');
    Route::post('/review/add', [ApiReviewController::class,'addReview'])->name('shop.api.review.add');
    Route::put('/review/{id}/update', [ApiReviewController::class,'update'])->name('shop.api.review.update');
    Route::delete('/review/{id}/delete', [ApiReviewController::class,'deleteReview'])->name('shop.api.review.add');

    #order
    Route::post('/placeorder', [ApiOrderController::class, 'placeOrderFromCart'])->name('shop.api.placeorder');
    Route::get('/orders/{orderId}/status-history',[ApiOrderStatusHistoryController::class, 'index']);
    Route::post('/orders/{orderId}/updatestatus',[ApiOrderStatusHistoryController::class, 'updateStatus']);
    Route::post('/orders/{orderId}/find',[ApiOrderController::class, 'findOrder']);
    #order return
    Route::post('/orders/{orderId}/return',[ApiOrderReturnController::class, 'store'])->name('shop.api.order.return');
    Route::get('/orders/{orderId}/returns',[ApiOrderReturnController::class, 'index'])->name('shop.api.orders.return.items');
    Route::get('/returns/{returnId}',[ApiOrderReturnController::class, 'show'])->name('shop.api.orders.return.show');
    Route::post('/returns/{returnId}/status',[ApiOrderReturnController::class, 'updateStatus'])->name('shop.api.order.return.status');

    #wishlist
    Route::get('/wishlist/items',[ApiWishlistController::class,'mywishlist']);
    Route::get('/wishlist/add',[ApiWishlistController::class, 'addToWishlist']);
    Route::delete('/wishlist/{id}/remove',[ApiWishlistController::class, 'removeFromWishlist']);
    Route::get('/wishlist/clear',[ApiWishlistController::class, 'clearWishlist']);

    #newsletter
    Route::post('/newsletter/subscribe',[ApiNewsletterController::class, 'subscribe']);
    Route::put('/newsletter/unsubscribe',[ApiNewsletterController::class, 'unsubscribe']);

    #logout
    Route::post('/logout', [ApiAuthController::class, 'logout'])->name('shop.api.logout');

});

Route::prefix('user/password')->group(function () {
    Route::post('/otp/send', [PasswordResetOtpController::class, 'sendOtp']);
    Route::post('/otp/verify', [PasswordResetOtpController::class, 'verifyOtp']);
    Route::post('/reset', [PasswordResetOtpController::class, 'resetPassword']);
});

#links
Route::get('/menus',[ApiMenuController::class,'index'])->name('shop.api.menu');
Route::get('/policies',[ApiPolicyController::class,'index'])->name('shop.api.policies');
Route::get('/siteinfo',[ApiSiteInfoController::class,'index'])->named('shop.api.siteinfo');

Route::post('/register', [ApiAuthController::class, 'register'])->name('shop.api.register');
Route::post('/login', [ApiAuthController::class, 'login'])->name('shop.api.login');

//GET http://localhost:8000/api/shop?search=shirt&categories=men,shoes&min_price=100&max_price=500&colors=red,blue&sizes=M,L&sort=price_low_high&per_page=12&page=2
//GET http://localhost:8000/api/product
Route::get('product', [ApiShopController::class, 'index']);
//GET http://localhost:8000/api/product/search?q=nike
Route::get('product/search', [ApiShopController::class, 'search']);
Route::get('product/category/{category}', [ApiShopController::class, 'index']);
Route::get('product/{slug}', [ApiShopController::class, 'show']);


//filter?categories=electronics,mobile,laptop&min_price=100&max_price=500&colors=Black,Blue&sizes=M,L
//http://localhost/vaniloapp/public/api/filter?categories=mobile,electronics&min_price=100&max_price=50000&colors=black&sizes=m
// Route::get('/filter', [ApiProductController::class, 'filter'])->name('shop.products.filter');
// Route::get('/products/{slug}', [ApiProductController::class, 'find_product_by_slug'])->name('shop.product.findbyslug');
// Route::get('/products', [ApiProductController::class, 'products'])->name('shop.api.products');
// Route::get('/product/{category}', [ApiProductController::class, 'find_by_category'])->name('shop.products.category');
// Route::post('/addtocart', [ApiCartController::class, 'add']);
// Route::get('/addtowishlist', [ApiWishlistController::class, 'addToWislist'])->name('addtowishlist');
//Route::post('cart/add', [ApiCartController::class, 'add'])->name('api.cart.add');

Route::get('/categories', [CategoryController::class, 'getCategories'])->name('shop.api.categories');

Route::get('home-content', [ApiPageContentController::class, 'getHomePageContent'])->name('shop.api.home-content');
Route::get('forgot-password-content', [ApiPageContentController::class, 'getForgotPasswordContent'])->name('shop.api.forgot-password-content');
Route::get('shop-content', [ApiPageContentController::class, 'getShopPageContent'])->name('shop.api.shop-content');
Route::get('about-us-content', [ApiPageContentController::class, 'getAboutUsPageContent'])->name('shop.api.about-us-content');
Route::get('contact-us-content', [ApiPageContentController::class, 'getContactUsPageContent'])->name('shop.api.contact-us-content');
Route::get('blog-content', [ApiPageContentController::class, 'getBlogPageContent'])->name('shop.api.blog-content');
Route::get('faq-content', [ApiPageContentController::class, 'getFaqPageContent'])->name('shop.api.faq-content');
Route::get('not-found-content', [ApiPageContentController::class, 'getNotFoundPageContent'])->name('shop.api.not-found-content');
Route::get('empty-cart-content', [ApiPageContentController::class, 'getEmptyCartPageContent'])->name('shop.api.empty-cart-content');
Route::get('empty-wishlist-content', [ApiPageContentController::class, 'getEmptyWishlistPageContent'])->name('shop.api.empty-wishlist-content');
Route::get('empty-orders-content', [ApiPageContentController::class, 'getEmptyOrdersPageContent'])->name('shop.api.empty-orders-content');
Route::get('order-complete-content', [ApiPageContentController::class, 'getOrderCompletePageContent'])->name('shop.api.order-complete-content');
Route::get('sign-in-content', [ApiPageContentController::class, 'getSignInContent'])->name('shop.api.sign-in-content');
Route::get('sign-up-content', [ApiPageContentController::class, 'getSignUpPageContent'])->name('shop.api.sign-up-content');
Route::get('empty-wishlist', [ApiPageContentController::class, 'getEmptyWishlistContent'])->name('shop.api.empty-wishlist-content');

 
