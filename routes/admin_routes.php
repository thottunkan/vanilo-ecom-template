<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminBlogCategoryController;
use App\Http\Controllers\Admin\AdminBlogController;
use App\Http\Controllers\Admin\AdminBrandController;
use App\Http\Controllers\Admin\AdminContactMessageController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminDiscountController;
use App\Http\Controllers\Admin\AdminFaqCategoryController;
use App\Http\Controllers\Admin\AdminFAQController;
use App\Http\Controllers\Admin\AdminFontController;
use App\Http\Controllers\Admin\AdminGalleryController;
use App\Http\Controllers\Admin\AdminNewsletterController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminTestCartController;
use App\Http\Controllers\Admin\AdminTestOrderController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CustomCssController;
use App\Http\Controllers\Admin\CustomJsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmailSettingsController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\FontController;
use App\Http\Controllers\Admin\MenuController;

use App\Http\Controllers\Admin\PageContentController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SiteInfoController;
use App\Http\Controllers\Admin\TaxCategoryController;
use App\Http\Controllers\Admin\TaxRateController;
use App\Http\Controllers\Admin\TestEmailController;


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'login'])
        ->name('login');
    Route::post('/login', [AdminAuthController::class, 'authenticate'])
        ->name('authenticate');
});

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {

    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('gallery')->name('gallery.')->group(function () {
        Route::get('/create/', [AdminGalleryController::class, 'create'])->name('create');
        Route::put('/store', [AdminGalleryController::class, 'store'])->name('store');

    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit/{id}', [AdminProfileController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [AdminProfileController::class, 'update'])->name('update');
        Route::get('/change-password/{id}', [AdminProfileController::class, 'page_chagePassword'])->name('change-password');
        Route::put('/change-password/{id}', [AdminProfileController::class, 'changePassword'])->name('password.update');
    });

    Route::prefix('orders')->name('orders.')->group(function () {
        // Orders
        Route::get('/', [AdminTestOrderController::class, 'index'])->name('index');
        Route::get('/create', [AdminTestOrderController::class, 'create'])->name('create');
        Route::post('/store', [AdminTestOrderController::class, 'store'])->name('store');
        Route::delete('/delete/{id}', [AdminTestOrderController::class, 'delete'])->name('delete');
        Route::get('/products', [AdminTestOrderController::class, 'products'])->name('products');
        // Cart
        Route::get('/cart', [AdminTestCartController::class, 'index'])->name('cart');
        Route::post('/cart/add', [AdminTestCartController::class, 'addToCart'])->name('cart.add');
        Route::delete('/cart/remove/{itemId}', [AdminTestCartController::class, 'removeFromCart'])->name('cart.remove');
        Route::delete('/cart/clear', [AdminTestCartController::class, 'clear'])->name('cart.clear');
        Route::get('/cart/count', [AdminTestCartController::class, 'count'])->name('cart.count');
        Route::get('/cart/exists', [AdminTestCartController::class, 'exists'])->name('cart.exists');
    });

    Route::prefix('category')->name('category.')->group(function () {
        Route::get('create', [CategoryController::class, 'create'])->name('create');
        Route::post('store', [CategoryController::class, 'store'])->name('store');
        Route::get('/index', [CategoryController::class, 'index'])->name('index');
        Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CategoryController::class, 'delete'])->name('delete');
        Route::delete('/bulk/delete', [CategoryController::class, 'bulk_delete'])->name('bulk.delete');
        Route::get('/export-to-pdf', [CategoryController::class, 'exportToPdf'])->name('export-to-pdf');
        Route::get('/export-to-excel', [CategoryController::class, 'exportExcel'])->name('export-to-excel');
        Route::get('/download-template-excel', [CategoryController::class, 'downloadExcelTemplate'])->name('download-template-excel');
        Route::post('/import-excel', [CategoryController::class, 'importExcel'])->name('import-excel');
    });

    Route::prefix('product')->name('product.')->group(function () {
        Route::get('/index', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [ProductController::class, 'delete'])->name('delete');
        Route::put('/update/{id}', [ProductController::class, 'update'])->name('update');
        Route::post('/store', [ProductController::class, 'store'])->name('store');
        Route::delete('/bulk/delete', [ProductController::class, 'bulk_delete'])->name('products.bulk.delete');
        Route::get('/export', [ProductController::class, 'exportToExcel'])->name('export-to-excel');
        Route::post('/import-from-excel', [ProductController::class, 'inportFromExcel'])->name('import-from-excel');
        Route::post('/export-to-excel', [ProductController::class, 'exportToExcel'])->name('export-to-excel');
        Route::get('/download-import-excel-template', [ProductController::class, 'downloadExcelTemplate'])->name('download-import-excel-template');
        Route::delete('product/{product_id}/delete-image/{image_id}', [ProductController::class, 'removeImage'])->name('delete_product_image');
        Route::delete('product/{product_id}/delete-thumbnail-image/{image_id}', [ProductController::class, 'removeThumbnailImage'])->name('delete_product_thumbnail_image');
    });

    Route::prefix('taxcategory')->name('taxcategory.')->group(function () {
        Route::get('/index', [TaxCategoryController::class, 'index'])->name('index');
        Route::get('/create', [TaxCategoryController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [TaxCategoryController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [TaxCategoryController::class, 'delete'])->name('delete');
        Route::put('/update/{id}', [TaxCategoryController::class, 'update'])->name('update');
        Route::post('/store', [TaxCategoryController::class, 'store'])->name('store');
        Route::delete('/bulk/delete', [TaxCategoryController::class, 'bulk_delete'])->name('bulk.delete');
    });

    Route::prefix('taxrate')->name('taxrate.')->group(function () {
        Route::get('/index', [TaxRateController::class, 'index'])->name('index');
        Route::get('/create', [TaxRateController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [TaxRateController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [TaxRateController::class, 'delete'])->name('delete');
        Route::put('/update/{id}', [TaxRateController::class, 'update'])->name('update');
        Route::post('/store', [TaxRateController::class, 'store'])->name('store');
        Route::get('/taxrates/{tax_category_id}', [TaxRateController::class, 'get_taxrates'])->name('get_taxrates');
        Route::delete('/bulk/delete', [TaxRateController::class, 'bulk_delete'])->name('bulk.delete');
    });

    Route::prefix('pagecontent')->name('pagecontent.')->group(function () {
        Route::get('/index', [PageContentController::class, 'index'])->name('index');
        Route::get('/create', [PageContentController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [PageContentController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [PageContentController::class, 'delete'])->name('delete');
        Route::put('/update/{id}', [PageContentController::class, 'update'])->name('update');
        Route::post('/store', [PageContentController::class, 'store'])->name('store');

        Route::prefix('home')->name('home.')->group(function () {
            Route::get('/edit/{id}', [PageContentController::class, 'home_edit'])->name('edit');
            Route::put('/update/{id}', [PageContentController::class, 'home_content_update'])->name('update');
        });

        Route::prefix('shop')->name('shop.')->group(function () {
            Route::get('/edit/{id}', [PageContentController::class, 'shop_edit'])->name('edit');
        });

        Route::prefix('about-us')->name('about-us.')->group(function () {
            Route::get('/edit/{id}', [PageContentController::class, 'about_us_edit'])->name('edit');
            Route::put('/update/{id}', [PageContentController::class, 'about_us_update'])->name('update');
        });

        Route::prefix('forgot-password')->name('forgot-password.')->group(function () {
            Route::get('/edit/{id}', [PageContentController::class, 'forgot_password_edit'])->name('edit');
            Route::put('/update/{id}', [PageContentController::class, 'forgot_password_update'])->name('update');
        });

        Route::prefix('reset-password')->name('reset-password.')->group(function () {
            Route::get('/edit/{id}', [PageContentController::class, 'reset_password_edit'])->name('edit');
            Route::put('/update/{id}', [PageContentController::class, 'reset_password_update'])->name('update');
        });

        Route::prefix('contact-us')->name('contact-us.')->group(function () {
            Route::get('/edit/{id}', [PageContentController::class, 'contact_us_edit'])->name('edit');
            Route::put('/update/{id}', [PageContentController::class, 'contact_us_update'])->name('update');
        });

        Route::prefix('blog')->name('blog.')->group(function () {
            Route::get('/edit/{id}', [PageContentController::class, 'blog_edit'])->name('edit');
            Route::put('/update/{id}', [PageContentController::class, 'blog_content_update'])->name('update');
        });

        Route::prefix('faq')->name('faq.')->group(function () {
            Route::get('/edit/{id}', [PageContentController::class, 'faq_edit'])->name('edit');
            Route::put('/update/{id}', [PageContentController::class, 'faq_content_update'])->name('update');
        });

        Route::prefix('not-found')->name('not-found.')->group(function () {
            Route::get('/edit/{id}', [PageContentController::class, 'not_found_edit'])->name('edit');
        });

        Route::prefix('empty-cart')->name('empty-cart.')->group(function () {
            Route::get('/edit/{id}', [PageContentController::class, 'empty_cart_edit'])->name('edit');
        });

        Route::prefix('empty-wishlist')->name('empty-wishlist.')->group(function () {
            Route::get('/edit/{id}', [PageContentController::class, 'empty_wishlist_edit'])->name('edit');
            Route::put('/update/{id}', [PageContentController::class, 'empty_wishlist_update'])->name('update');
        });

        Route::prefix('empty-orders')->name('empty-orders.')->group(function () {
            Route::get('/edit/{id}', [PageContentController::class, 'empty_orders_edit'])->name('edit');
        });

        Route::prefix('order-complete')->name('order-complete.')->group(function () {
            Route::get('/edit/{id}', [PageContentController::class, 'order_complete_edit'])->name('edit');
        });

        Route::prefix('sign-in')->name('sign-in.')->group(function () {
            Route::get('/edit/{id}', [PageContentController::class, 'sign_in_content_edit'])->name('edit');
            Route::put('/update/{id}', [PageContentController::class, 'sign_in_content_update'])->name('update');
        });

        Route::prefix('sign-up')->name('sign-up.')->group(function () {
            Route::get('/edit/{id}', [PageContentController::class, 'sign_up_content_edit'])->name('edit');
            Route::put('/update/{id}', [PageContentController::class, 'sign_up_content_update'])->name('update');
        });

    });

    Route::prefix('brand')->name('brand.')->group(function () {
        Route::get('/index', [AdminBrandController::class, 'index'])->name('index');
        Route::get('/create', [AdminBrandController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [AdminBrandController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [AdminBrandController::class, 'delete'])->name('delete');
        Route::put('/update/{id}', [AdminBrandController::class, 'update'])->name('update');
        Route::post('/store', [AdminBrandController::class, 'store'])->name('store');
        Route::delete('/bulk/delete', [AdminBrandController::class, 'bulk_delete'])->name('bulk.delete');
    });

    Route::prefix('review')->name('review.')->group(function () {
        Route::get('/index', [AdminReviewController::class, 'index'])->name('index');
        Route::get('/create', [AdminReviewController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [AdminReviewController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [AdminReviewController::class, 'delete'])->name('delete');
        Route::put('/update/{id}', [AdminReviewController::class, 'update'])->name('update');
        Route::post('/store', [AdminReviewController::class, 'store'])->name('store');
        Route::delete('/bulk/delete', [AdminReviewController::class, 'bulk_delete'])->name('bulk.delete');
    });

    Route::prefix('site-settings')->name('site-settings.')->group(function () {
        Route::get('/index', [SiteInfoController::class, 'index'])->name('index');
        Route::get('/create', [SiteInfoController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [SiteInfoController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [SiteInfoController::class, 'delete'])->name('delete');
        Route::put('/update/{id}', [SiteInfoController::class, 'update'])->name('update');
        Route::post('/store', [SiteInfoController::class, 'store'])->name('store');
    });

    Route::prefix('email-settings')->name('email-settings.')->group(function () {
        Route::get('/index', [EmailSettingsController::class, 'index'])->name('index');
        Route::get('/create', [EmailSettingsController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [EmailSettingsController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [EmailSettingsController::class, 'delete'])->name('delete');
        Route::put('/update/{id}', [EmailSettingsController::class, 'update'])->name('update');
        Route::post('/store', [EmailSettingsController::class, 'store'])->name('store');
    });

    Route::prefix('email-template')->name('email-template.')->group(function () {
        Route::get('/index', [EmailTemplateController::class, 'index'])->name('index');
        Route::get('/create', [EmailTemplateController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [EmailTemplateController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [EmailTemplateController::class, 'delete'])->name('delete');
        Route::put('/update/{id}', [EmailTemplateController::class, 'update'])->name('update');
        Route::post('/store', [EmailTemplateController::class, 'store'])->name('store');
        Route::delete('/bulk/delete', [EmailTemplateController::class, 'bulk_delete'])->name('bulk.delete');
    });

    Route::prefix('menu')->name('menu.')->group(function () {
        Route::get('/index', [MenuController::class, 'index'])->name('index');
        Route::get('/create', [MenuController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [MenuController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [MenuController::class, 'delete'])->name('delete');
        Route::put('/update/{id}', [MenuController::class, 'update'])->name('update');
        Route::post('/store', [MenuController::class, 'store'])->name('store');
        Route::post('/update-order', [MenuController::class, 'updateOrder'])->name('updateOrder');
        Route::delete('/bulk/delete', [MenuController::class, 'bulk_delete'])->name('bulk.delete');
    });

    Route::prefix('policy')->name('policy.')->group(function () {
        Route::get('/index', [PolicyController::class, 'index'])->name('index');
        Route::get('/create', [PolicyController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [PolicyController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [PolicyController::class, 'delete'])->name('delete');
        Route::put('/update/{id}', [PolicyController::class, 'update'])->name('update');
        Route::post('/store', [PolicyController::class, 'store'])->name('store');
        Route::post('/update-order', [PolicyController::class, 'updateOrder'])->name('updateOrder');
        Route::delete('/bulk/delete', [PolicyController::class, 'bulk_delete'])->name('bulk.delete');
    });

    Route::prefix('blog')->name('blog.')->group(function () {
        Route::get('/index', [AdminBlogController::class, 'index'])->name('index');
        Route::get('/create', [AdminBlogController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [AdminBlogController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [AdminBlogController::class, 'delete'])->name('delete');
        Route::put('/update/{id}', [AdminBlogController::class, 'update'])->name('update');
        Route::post('/store', [AdminBlogController::class, 'store'])->name('store');
        Route::post('/update-order', [AdminBlogController::class, 'updateOrder'])->name('updateOrder');
        Route::delete('/bulk/delete', [AdminBlogController::class, 'bulk_delete'])->name('bulk.delete');
    });

    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/index', [AdminUserController::class, 'index'])->name('index');
        Route::get('/create', [AdminUserController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [AdminUserController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [AdminUserController::class, 'delete'])->name('delete');
        Route::put('/update/{id}', [AdminUserController::class, 'update'])->name('update');
        Route::post('/store', [AdminUserController::class, 'store'])->name('store');
        Route::post('/update-order', [AdminUserController::class, 'updateOrder'])->name('updateOrder');
        Route::delete('/bulk/delete', [AdminUserController::class, 'bulk_delete'])->name('bulk.delete');
        Route::get('/export-to-pdf', [AdminUserController::class, 'exportToPdf'])->name('export-to-pdf');
        Route::get('/export-to-excel', [AdminUserController::class, 'exportToExcel'])->name('export-to-excel');
        Route::post('/import-from-excel', [AdminUserController::class, 'importFromExcel'])->name('import-from-excel');
        Route::post('/import-from-csv', [AdminUserController::class, 'importFromCSV'])->name('import-from-csv');
        Route::get('/download-template-excel', [AdminUserController::class, 'downloadExcelTemplate'])->name('download-template-excel');
    });

    // Route::prefix('blog')->name('blog.')->group(function () {
    //     Route::get('/index', [AdminBlogController::class, 'index'])->name('index');
    //     Route::get('/create', [AdminBlogController::class, 'create'])->name('create');
    //     Route::get('/edit/{id}', [AdminBlogController::class, 'edit'])->name('edit');
    //     Route::delete('/delete/{id}', [AdminBlogController::class, 'delete'])->name('delete');
    //     Route::put('/update/{id}', [AdminBlogController::class, 'update'])->name('update');
    //     Route::post('/store', [AdminBlogController::class, 'store'])->name('store');
    //     Route::post('/update-order', [AdminBlogController::class, 'updateOrder'])->name('updateOrder');
    // });

    Route::prefix('blog-category')->name('blog-category.')->group(function () {
        Route::get('/index', [AdminBlogCategoryController::class, 'index'])->name('index');
        Route::get('/create', [AdminBlogCategoryController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [AdminBlogCategoryController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [AdminBlogCategoryController::class, 'delete'])->name('delete');
        Route::put('/update/{id}', [AdminBlogCategoryController::class, 'update'])->name('update');
        Route::post('/store', [AdminBlogCategoryController::class, 'store'])->name('store');
        Route::post('/update-order', [AdminBlogCategoryController::class, 'updateOrder'])->name('updateOrder');
        Route::delete('/bulk/delete', [AdminBlogCategoryController::class, 'bulk_delete'])->name('bulk.delete');
    });

    Route::prefix('font')->name('font.')->group(function () {
        Route::get('/index', [AdminFontController::class, 'index'])->name('index');
        Route::post('/store', [AdminFontController::class, 'update'])->name('store');
    });

    Route::prefix('color')->name('color.')->group(function () {
        Route::get('/create', [ColorController::class, 'create'])->name('create');
        Route::post('/store', [ColorController::class, 'store'])->name('store');
    });

    Route::prefix('custom-css')->name('custom-css.')->group(function () {
        Route::get('/index', [CustomCssController::class, 'index'])->name('index');
        Route::get('/create', [CustomCssController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [CustomCssController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [CustomCssController::class, 'delete'])->name('delete');
        Route::put('/update/{id}', [CustomCssController::class, 'update'])->name('update');
        Route::post('/store', [CustomCssController::class, 'store'])->name('store');
        Route::get('/{id}/unlock', [CustomCssController::class, 'unlock'])->name('unlock');
        Route::delete('/bulk/delete', [CustomCssController::class, 'bulk_delete'])->name('bulk.delete');
    });

    Route::prefix('custom-js')->name('custom-js.')->group(function () {
        Route::get('/index', [CustomJsController::class, 'index'])->name('index');
        Route::get('/create', [CustomJsController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [CustomJsController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [CustomJsController::class, 'delete'])->name('delete');
        Route::put('/update/{id}', [CustomJsController::class, 'update'])->name('update');
        Route::post('/store', [CustomJsController::class, 'store'])->name('store');
        Route::get('/{id}/unlock', [CustomJsController::class, 'unlock'])->name('unlock');
        Route::delete('/bulk/delete', [CustomJsController::class, 'bulk_delete'])->name('bulk.delete');
    });

    Route::prefix('payment-method')->name('payment-method.')->group(function () {
        Route::get('/index', [PaymentMethodController::class, 'index'])->name('index');
        Route::get('/create', [PaymentMethodController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [PaymentMethodController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [PaymentMethodController::class, 'delete'])->name('delete');
        Route::put('/update/{id}', [PaymentMethodController::class, 'update'])->name('update');
        Route::post('/store', [PaymentMethodController::class, 'store'])->name('store');
        Route::delete('/bulk/delete', [PaymentMethodController::class, 'bulk_delete'])->name('bulk.delete');
    });


    Route::prefix('faq')->name('faq.')->group(function () {
        Route::get('/index', [AdminFAQController::class, 'index'])->name('index');
        Route::get('/create', [AdminFAQController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [AdminFAQController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [AdminFAQController::class, 'delete'])->name('delete');
        Route::put('/update/{id}', [AdminFAQController::class, 'update'])->name('update');
        Route::post('/store', [AdminFAQController::class, 'store'])->name('store');
        Route::delete('/bulk/delete', [AdminFAQController::class, 'bulk_delete'])->name('bulk.delete');
    });

    Route::prefix('faq-category')->name('faq-category.')->group(function () {
        Route::get('/index', [AdminFaqCategoryController::class, 'index'])->name('index');
        Route::get('/create', [AdminFaqCategoryController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [AdminFaqCategoryController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [AdminFaqCategoryController::class, 'delete'])->name('delete');
        Route::put('/update/{id}', [AdminFaqCategoryController::class, 'update'])->name('update');
        Route::post('/store', [AdminFaqCategoryController::class, 'store'])->name('store');
        Route::delete('/bulk/delete', [AdminFaqCategoryController::class, 'bulk_delete'])->name('bulk.delete');
    });

    Route::prefix('discount')->name('discount.')->group(function () {
        Route::get('/index', [AdminDiscountController::class, 'index'])->name('index');
        Route::get('/create', [AdminDiscountController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [AdminDiscountController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [AdminDiscountController::class, 'delete'])->name('delete');
        Route::put('/update/{id}', [AdminDiscountController::class, 'update'])->name('update');
        Route::post('/store', [AdminDiscountController::class, 'store'])->name('store');
        Route::delete('/bulk/delete', [AdminDiscountController::class, 'bulk_delete'])->name('bulk.delete');
    });

    Route::prefix('newsletter')->name('newsletter.')->group(function () {
        Route::get('/index', [AdminNewsletterController::class, 'index'])->name('index');
        Route::get('/create', [AdminNewsletterController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [AdminNewsletterController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [AdminNewsletterController::class, 'delete'])->name('delete');
        Route::put('/update/{id}', [AdminNewsletterController::class, 'update'])->name('update');
        Route::post('/store', [AdminNewsletterController::class, 'store'])->name('store');
        Route::delete('/bulk/delete', [AdminNewsletterController::class, 'bulk_delete'])->name('bulk.delete');
    });

    Route::prefix('contact-messages')->name('contact-messages.')->group(function () {
        Route::get('/index', [AdminContactMessageController::class, 'index'])->name('index');
        Route::get('/create', [AdminContactMessageController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [AdminContactMessageController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [AdminContactMessageController::class, 'delete'])->name('delete');
        Route::put('/update/{id}', [AdminContactMessageController::class, 'update'])->name('update');
        Route::post('/store', [AdminContactMessageController::class, 'store'])->name('store');
        Route::delete('/bulk/delete', [AdminContactMessageController::class, 'bulk_delete'])->name('bulk.delete');
    });


    Route::prefix('import-from-csv')->name('import-from-csv.')->group(function () {
        Route::post('/import-from-csv', [ProductController::class, 'inportFromCSV'])->name('import-from-csv');
    });

    Route::prefix('test-email')->name('test-email.')->group(function () {
        Route::post('/test-email', [TestEmailController::class, 'sendTestEmail'])->name('test-email');
    });

    //filter routes
    Route::prefix('product-filter')->name('product-filter.')->group(function () {
        Route::post('/product-filter', [ProductController::class, 'index'])->name('product-filter');
    });

    Route::prefix('category-filter')->name('category-filter.')->group(function () {
        Route::post('/category-filter', [CategoryController::class, 'index'])->name('category-filter');
    });

    Route::prefix('pagecontent-filter')->name('pagecontent-filter.')->group(function () {
        Route::post('/pagecontent-filter', [PageContentController::class, 'index'])->name('pagecontent-filter');
    });

    Route::prefix('brand-filter')->name('brand-filter.')->group(function () {
        Route::post('/brand-filter', [AdminBrandController::class, 'index'])->name('brand-filter');
    });

    Route::prefix('tax-category-filter')->name('tax-category-filter.')->group(function () {
        Route::post('/tax-category-filter', [TaxCategoryController::class, 'index'])->name('tax-category-filter');
    });

    Route::prefix('tax-rate-filter')->name('tax-rate-filter.')->group(function () {
        Route::post('/tax-rate-filter', [TaxRateController::class, 'index'])->name('tax-rate-filter');
    });

    Route::prefix('customer-filter')->name('customer-filter.')->group(function () {
        Route::post('/customer-filter', [AdminUserController::class, 'index'])->name('customer-filter');
    });

    Route::prefix('review-filter')->name('review-filter.')->group(function () {
        Route::post('/review-filter', [AdminReviewController::class, 'index'])->name('review-filter');
    });

    Route::prefix('blog-filter')->name('blog-filter.')->group(function () {
        Route::post('/blog-filter', [AdminBlogController::class, 'index'])->name('blog-filter');
    });

    Route::prefix('blog-category-filter')->name('blog-category-filter.')->group(function () {
        Route::post('/blog-category-filter', [AdminBlogCategoryController::class, 'index'])->name('blog-category-filter');
    });

    Route::prefix('custom-js-filter')->name('custom-js-filter.')->group(function () {
        Route::post('/custom-js-filter', [CustomJsController::class, 'index'])->name('custom-js-filter');
    });

    Route::prefix('custom-css-filter')->name('custom-css-filter.')->group(function () {
        Route::post('/custom-css-filter', [CustomCssController::class, 'index'])->name('custom-css-filter');
    });

    Route::prefix('policy-filter')->name('policy-filter.')->group(function () {
        Route::post('/policy-filter', [PolicyController::class, 'index'])->name('policy-filter');
    });

    Route::prefix('menu-filter')->name('menu-filter.')->group(function () {
        Route::post('/menu-filter', [MenuController::class, 'index'])->name('menu-filter');
    });

    Route::prefix('page-filter')->name('menu-filter.')->group(function () {
        Route::post('/page-filter', [PageContentController::class, 'index'])->name('page-filter');
    });

    Route::prefix('faq-filter')->name('faq-filter.')->group(function () {
        Route::post('/faq-filter', [AdminFAQController::class, 'index'])->name('faq-filter');
    });

});