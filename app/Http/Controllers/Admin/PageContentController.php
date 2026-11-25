<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use App\Utils\BulkDelete;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Storage;

class PageContentController extends Controller
{
    public function bulk_delete(Request $request)
    {
        return BulkDelete::perform($request, new PageContent());
    }

    public function index(Request $request)
    {
        $pages = PageContent::query()

            // Search
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('page_name', 'like', "%{$search}%")
                        // ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('url', 'like', "%{$search}%");
                });
            })

            // Status
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status); // Change to 'status' if your column name differs
            })

            // Created At
            ->when($request->filled('created_at'), function ($query) use ($request) {
                $query->whereDate('created_at', $request->created_at);
            })

            // Updated At
            ->when($request->filled('updated_at'), function ($query) use ($request) {
                $query->whereDate('updated_at', $request->updated_at);
            })

            // Date Range
            ->when($request->filled('from_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->from_date);
            })

            ->when($request->filled('to_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->to_date);
            });

        // Sorting
        switch ($request->get('sort')) {

            case 'created_at_asc':
                $pages->orderBy('created_at', 'asc');
                break;

            case 'updated_at_desc':
                $pages->orderBy('updated_at', 'desc');
                break;

            case 'name_asc':
                $pages->orderBy('title', 'asc');
                break;

            case 'name_desc':
                $pages->orderBy('title', 'desc');
                break;

            case 'created_at_desc':
            default:
                $pages->orderBy('created_at', 'desc');
                break;
        }

        $pages = $pages->get();

        return view('admin.pagecontents.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pagecontents.create');
    }



    public function edit($id)
    {
        $page = PageContent::findOrFail($id);

        switch (true) {

            case Str::contains($page->page_name, 'home', ignoreCase: true):
                return redirect()->route('admin.pagecontent.home.edit', $id);

            case Str::contains($page->page_name, 'shop', ignoreCase: true):
                return redirect()->route('admin.pagecontent.shop.edit', $id);

            case Str::contains($page->page_name, 'about us', ignoreCase: true):
                return redirect()->route('admin.pagecontent.about-us.edit', $id);

            case Str::contains($page->page_name, 'Contact Us', ignoreCase: true):
                return redirect()->route('admin.pagecontent.contact-us.edit', $id);

            case Str::contains($page->page_name, 'blog', ignoreCase: true):
                return redirect()->route('admin.pagecontent.blog.edit', $id);

            case Str::contains($page->page_name, 'faq', ignoreCase: true):
                return redirect()->route('admin.pagecontent.faq.edit', $id);

            case Str::contains($page->page_name, 'not found', ignoreCase: true):
                return redirect()->route('admin.pagecontent.not-found.edit', $id);

            case Str::contains($page->page_name, 'empty cart', ignoreCase: true):
                return redirect()->route('admin.pagecontent.empty-cart.edit', $id);

            case Str::contains($page->page_name, 'empty wishlist', ignoreCase: true):
                return redirect()->route('admin.pagecontent.empty-wishlist.edit', $id);

            case Str::contains($page->page_name, 'empty orders', ignoreCase: true):
                return redirect()->route('admin.pagecontent.empty-orders.edit', $id);

            case Str::contains($page->page_name, 'order complete', ignoreCase: true):
                return redirect()->route('admin.pagecontent.order-complete.edit', $id);

            case Str::contains($page->page_name, 'sign-in', ignoreCase: true):
                return redirect()->route('admin.pagecontent.sign-in.edit', $id);

            case Str::contains($page->page_name, 'sign-up', ignoreCase: true):
                return redirect()->route('admin.pagecontent.sign-up.edit', $id);

            case Str::contains($page->page_name, 'forgot-password', ignoreCase: true):
                return redirect()->route('admin.pagecontent.forgot-password.edit', $id);

            case Str::contains($page->page_name, 'reset-password', ignoreCase: true):
                return redirect()->route('admin.pagecontent.reset-password.edit', $id);

            default:
                return "Page type not found.";
        }
    }

    public function sign_in_content_edit($id)
    {
        $sign_in_page = PageContent::findOrFail($id);
        return view('admin.pagecontents.sign-in.edit', compact('sign_in_page'));
    }

    public function forgot_password_edit($id)
    {
        $forgot_password_page = PageContent::findOrFail($id);
        return view('admin.pagecontents.forgot-password.edit', compact('forgot_password_page'));
    }
    public function reset_password_edit($id)
    {
        $reset_password_page = PageContent::findOrFail($id);
        return view('admin.pagecontents.reset-password.edit', compact('reset_password_page'));
    }

    public function sign_up_content_edit($id)
    {
        $sign_up_page = PageContent::findOrFail($id);
        return view('admin.pagecontents.sign-up.edit', compact('sign_up_page'));
    }

    public function home_edit($id)
    {
        $home_page = PageContent::findOrFail($id);
        return view('admin.pagecontents.home.edit', compact('home_page'));
    }

    public function shop_edit($id)
    {
        $shop_page = PageContent::findOrFail($id);
        return view('admin.pagecontents.shop.edit', compact('shop_page'));
    }

    public function about_us_edit($id)
    {
        $about_us_page = PageContent::findOrFail($id);
        return view('admin.pagecontents.about-us.edit', compact('about_us_page'));
    }

    public function contact_us_edit($id)
    {
        $contact_us_page = PageContent::findOrFail($id);
        return view('admin.pagecontents.contact-us.edit', compact('contact_us_page'));
    }

    public function blog_edit($id)
    {
        $blog_page = PageContent::findOrFail($id);
        return view('admin.pagecontents.blog.edit', compact('blog_page'));
    }

    public function faq_edit($id)
    {
        $faq_page = PageContent::findOrFail($id);
        return view('admin.pagecontents.faq.edit', compact('faq_page'));
    }

    public function not_found_edit($id)
    {
        $not_found_page = PageContent::findOrFail($id);
        return view('admin.pagecontents.not-found.edit', compact('not_found_page'));
    }

    public function empty_cart_edit($id)
    {
        $empty_cart_page = PageContent::findOrFail($id);
        return view('admin.pagecontents.empty-cart.edit', compact('empty_cart_page'));
    }

    public function empty_wishlist_edit($id)
    {
        $empty_wishlist_page = PageContent::findOrFail($id);
        return view('admin.pagecontents.empty-wishlist.edit', compact('empty_wishlist_page'));
    }

    public function empty_orders_edit($id)
    {
        $empty_orders_page = PageContent::findOrFail($id);
        return view('admin.pagecontents.empty-orders.edit', compact('empty_orders_page'));
    }

    public function order_complete_edit($id)
    {
        $order_complete_page = PageContent::findOrFail($id);
        return view('admin.pagecontents.order-complete.edit', compact('order_complete_page'));
    }




    public function home_content_update(Request $request, $id)
    {
        $pageContent = PageContent::findOrFail($id);

        $existingData = $pageContent->page_contents ?? [];

        if (is_string($existingData)) {
            $existingData = json_decode($existingData, true) ?? [];
        }

        // Get all normal request data
        $data = $request->except(['_token', '_method']);

        // Image fields from Blade
        $imageFields = [
            'section1.hero_image',
            'section2.icon1',
            'section2.icon2',
            'section2.icon3',
            'section2.icon4',
            'section4.banner_image',
            'section5.image',
            'section8.image',
            'section10.icon1',
            'section10.icon2',
            'section10.icon3',
            'section10.icon4',
            'section11.image',
        ];

        foreach ($imageFields as $field) {

            // Get existing nested image
            $oldImage = data_get($existingData, $field);

            if ($request->hasFile($field)) {

                // Delete old image
                if (
                    !empty($oldImage) &&
                    is_string($oldImage) &&
                    Storage::disk('public')->exists($oldImage)
                ) {
                    Storage::disk('public')->delete($oldImage);
                }

                // Store new image
                $newImage = $request->file($field)->store(
                    'home-content',
                    'public'
                );

                // Set new image inside the nested section
                data_set($data, $field, $newImage);

            } else {

                // Keep old image
                if (!empty($oldImage)) {
                    data_set($data, $field, $oldImage);
                }
            }
        }

        // Keep existing status if no status is submitted
        $status = $data['status'] ?? $pageContent->status;

        $data['status'] = $status;

        $pageContent->update([
            'page_contents' => $data,
            'status' => $status,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Home page updated successfully.');
    }


    //faq_content_update
    public function faq_content_update(Request $request, $id)
    {
        $pageContent = PageContent::findOrFail($id);

        $existingData = $pageContent->page_contents ?? [];

        if (is_string($existingData)) {
            $existingData = json_decode($existingData, true) ?? [];
        }

        $data = $request->except(['_token', '_method']);

        // If JSON is stored as string
        if (is_string($existingData)) {
            $existingData = json_decode($existingData, true) ?? [];
        }

        $imageFields = [
            'faq_banner',
        ];


        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {

                // Delete old image if exists
                if (
                    !empty($existingData[$field]) &&
                    Storage::disk('public')->exists($existingData[$field])
                ) {
                    Storage::disk('public')->delete($existingData[$field]);
                }

                // Store new image
                $data[$field] = $request->file($field)->store('faq-content', 'public');

            } else {

                // Keep old image path
                if (isset($existingData[$field])) {
                    $data[$field] = $existingData[$field];
                }
            }
        }

        $status = $data['status'] ?? $pageContent->status;
        $data['status'] = $status;

        $pageContent->update([
            'page_contents' => $data,
            'status' => $status,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Page content updated successfully.');

    }

    public function sign_in_content_update(Request $request, $id)
    {
        $pageContent = PageContent::findOrFail($id);

        $existingData = $pageContent->page_contents ?? [];

        if (is_string($existingData)) {
            $existingData = json_decode($existingData, true) ?? [];
        }

        $data = $request->except(['_token', '_method']);

        // If JSON is stored as string
        if (is_string($existingData)) {
            $existingData = json_decode($existingData, true) ?? [];
        }

        // Image fields
        //'sign_in_image
        $imageFields = [
            'sign_in_image',
            'sign_in_logo'
        ];


        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {

                // Delete old image if exists
                if (
                    !empty($existingData[$field]) &&
                    Storage::disk('public')->exists($existingData[$field])
                ) {
                    Storage::disk('public')->delete($existingData[$field]);
                }

                // Store new image
                $data[$field] = $request->file($field)->store('sign-in', 'public');

            } else {

                // Keep old image path
                if (isset($existingData[$field])) {
                    $data[$field] = $existingData[$field];
                }
            }
        }

        $status = $data['status'] ?? $pageContent->status;
        $data['status'] = $status;

        $pageContent->update([
            'page_contents' => $data,
            'status' => $status,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Page content updated successfully.');
    }

    public function forgot_password_update(Request $request, $id)
    {
        $pageContent = PageContent::findOrFail($id);

        $existingData = $pageContent->page_contents ?? [];

        if (is_string($existingData)) {
            $existingData = json_decode($existingData, true) ?? [];
        }

        $data = $request->except(['_token', '_method']);

        // If JSON is stored as string
        if (is_string($existingData)) {
            $existingData = json_decode($existingData, true) ?? [];
        }

        // Image fields
        //'sign_in_image
        $imageFields = [
            'forgot_password_image',
        ];


        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {

                // Delete old image if exists
                if (
                    !empty($existingData[$field]) &&
                    Storage::disk('public')->exists($existingData[$field])
                ) {
                    Storage::disk('public')->delete($existingData[$field]);
                }

                // Store new image
                $data[$field] = $request->file($field)->store('forgot-password', 'public');

            } else {

                // Keep old image path
                if (isset($existingData[$field])) {
                    $data[$field] = $existingData[$field];
                }
            }
        }

        $status = $data['status'] ?? $pageContent->status;
        $data['status'] = $status;

        $pageContent->update([
            'page_contents' => $data,
            'status' => $status,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Page content updated successfully.');
    }

    public function reset_password_update(Request $request, $id)
    {
        $pageContent = PageContent::findOrFail($id);

        $existingData = $pageContent->page_contents ?? [];

        if (is_string($existingData)) {
            $existingData = json_decode($existingData, true) ?? [];
        }

        $data = $request->except(['_token', '_method']);

        // If JSON is stored as string
        if (is_string($existingData)) {
            $existingData = json_decode($existingData, true) ?? [];
        }

        // Image fields
        //'sign_in_image
        $imageFields = [
            'reset_password_image',
        ];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {

                // Delete old image if exists
                if (
                    !empty($existingData[$field]) &&
                    Storage::disk('public')->exists($existingData[$field])
                ) {
                    Storage::disk('public')->delete($existingData[$field]);
                }

                // Store new image
                $data[$field] = $request->file($field)->store('reset-password', 'public');

            } else {

                // Keep old image path
                if (isset($existingData[$field])) {
                    $data[$field] = $existingData[$field];
                }
            }
        }

        $status = $data['status'] ?? $pageContent->status;
        $data['status'] = $status;

        $pageContent->update([
            'page_contents' => $data,
            'status' => $status,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Page content updated successfully.');
    }

    public function sign_up_content_update(Request $request, $id)
    {
        $pageContent = PageContent::findOrFail($id);

        $existingData = $pageContent->page_contents ?? [];

        if (is_string($existingData)) {
            $existingData = json_decode($existingData, true) ?? [];
        }

        $data = $request->except(['_token', '_method']);

        // If JSON is stored as string
        if (is_string($existingData)) {
            $existingData = json_decode($existingData, true) ?? [];
        }

        // Image fields
        $imageFields = [
            'sign_up_image',
            'sign_up_logo'
        ];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {

                // Delete old image if exists
                if (
                    !empty($existingData[$field]) &&
                    Storage::disk('public')->exists($existingData[$field])
                ) {
                    Storage::disk('public')->delete($existingData[$field]);
                }

                // Store new image
                $data[$field] = $request->file($field)->store('sign-up', 'public');

            } else {

                // Keep old image path
                if (isset($existingData[$field])) {
                    $data[$field] = $existingData[$field];
                }
            }
        }

        $status = $data['status'] ?? $pageContent->status;
        $data['status'] = $status;

        $pageContent->update([
            'page_contents' => $data,
            'status' => $status,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Page content updated successfully.');
    }

    public function contact_us_update(Request $request, $id)
    {
        $pageContent = PageContent::findOrFail($id);

        $existingData = $pageContent->page_contents ?? [];

        if (is_string($existingData)) {
            $existingData = json_decode($existingData, true) ?? [];
        }

        $data = $request->except(['_token', '_method']);

        // If JSON is stored as string
        if (is_string($existingData)) {
            $existingData = json_decode($existingData, true) ?? [];
        }

        // Image fields
        $imageFields = [
            'section_1_image',
        ];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {

                // Delete old image if exists
                if (
                    !empty($existingData[$field]) &&
                    Storage::disk('public')->exists($existingData[$field])
                ) {
                    Storage::disk('public')->delete($existingData[$field]);
                }

                // Store new image
                $data[$field] = $request->file($field)->store('contact-us', 'public');

            } else {

                // Keep old image path
                if (isset($existingData[$field])) {
                    $data[$field] = $existingData[$field];
                }
            }
        }

        $status = $data['status'] ?? $pageContent->status;
        $data['status'] = $status;

        $pageContent->update([
            'page_contents' => $data,
            'status' => $status,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Page content updated successfully.');
    }


    public function about_us_update(Request $request, $id)
    {
        $pageContent = PageContent::findOrFail($id);

        $existingData = $pageContent->page_contents ?? [];

        if (is_string($existingData)) {
            $existingData = json_decode($existingData, true) ?? [];
        }

        $data = $request->except(['_token', '_method']);
        // All image fields
        $imageFields = [
            'section_1_image',
            'section2_image',
            'section2_icon1',
            'section2_icon2',
            'section2_icon3',
            'section2_icon4',
            'section5_icon1',
            'section5_icon2',
            'section6_image',
            'section7_image'
        ];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old image if exists
                if (!empty($existingData[$field]) && Storage::disk('public')->exists($existingData[$field])) {
                    Storage::disk('public')->delete($existingData[$field]);
                }
                // Store new image
                $data[$field] = $request->file($field)->store('about-us', 'public');
            } else {
                // Keep old image path
                if (isset($existingData[$field])) {
                    $data[$field] = $existingData[$field];
                }
            }
        }

        $status = $data['status'] ?? $pageContent->status;
        $data['status'] = $status;

        $pageContent->update([
            'page_contents' => $data,
            'status' => $status
        ]);

        return redirect()
            ->back()
            ->with('success', 'About Us page updated successfully.');

    }

    public function blog_update(Request $request, $id)
    {
        $pageContent = PageContent::findOrFail($id);

        $existingData = $pageContent->page_contents ?? [];

        if (is_string($existingData)) {
            $existingData = json_decode($existingData, true) ?? [];
        }

        $data = $request->except(['_token', '_method']);

        // If JSON is stored as string
        if (is_string($existingData)) {
            $existingData = json_decode($existingData, true) ?? [];
        }

        // Image fields
        $imageFields = [
            'section1.banner_image',
        ];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {

                // Delete old image if exists
                if (
                    !empty($existingData[$field]) &&
                    Storage::disk('public')->exists($existingData[$field])
                ) {
                    Storage::disk('public')->delete($existingData[$field]);
                }
                dd($request->file($field));
                // Store new image
                $data[$field] = $request->file($field)->store('blog-content', 'public');

            } else {

                // Keep old image path
                if (isset($existingData[$field])) {
                    $data[$field] = $existingData[$field];
                }
            }
        }

        $status = $data['status'] ?? $pageContent->status;
        $data['status'] = $status;

        $pageContent->update([
            'page_contents' => $data,
            'status' => $status,
        ]);

        return redirect()
            ->route('admin.pagecontent.index')
            ->with('success', 'Blog page updated successfully.');
    }

    public function delete($id)
    {
        $page = PageContent::findOrFail($id);
        $page->delete();

        return redirect()
            ->route('admin.pagecontent.index')
            ->with('success', 'Page deleted successfully.');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'status' => 'required|string'
        ]);

        PageContent::create([
            'page_name' => $data['name'],
            'status' => $data['status'],
        ]);

        return redirect()
            ->route('admin.pagecontent.index')
            ->with('success', 'Page created successfully.');
    }

    public function blog_content_update(Request $request, $id)
    {
        $pageContent = PageContent::findOrFail($id);

        $existingData = $pageContent->page_contents ?? [];

        if (is_string($existingData)) {
            $existingData = json_decode($existingData, true) ?? [];
        }

        // Get all normal request data
        $data = $request->except(['_token', '_method']);

        // Image fields from Blade
        $imageFields = [
            'section1.banner_image',
        ];

        foreach ($imageFields as $field) {

            // Get existing nested image
            $oldImage = data_get($existingData, $field);

            if ($request->hasFile($field)) {

                // Delete old image
                if (
                    !empty($oldImage) &&
                    is_string($oldImage) &&
                    Storage::disk('public')->exists($oldImage)
                ) {
                    Storage::disk('public')->delete($oldImage);
                }

                // Store new image
                $newImage = $request->file($field)->store(
                    'blog-content',
                    'public'
                );

                // Set inside section1
                data_set($data, $field, $newImage);

            } else {

                // Keep old image
                if (!empty($oldImage)) {
                    data_set($data, $field, $oldImage);
                }
            }
        }

        $status = $data['status'] ?? $pageContent->status;

        $data['status'] = $status;

        $pageContent->update([
            'page_contents' => $data,
            'status' => $status,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Blog page updated successfully.');
    }


    public function empty_wishlist_update(Request $request, $id)
    {
        $pageContent = PageContent::findOrFail($id);

        $existingData = $pageContent->page_contents ?? [];

        if (is_string($existingData)) {
            $existingData = json_decode($existingData, true) ?? [];
        }

        // Get all normal request data
        $data = $request->except(['_token', '_method']);

        // Image fields from Blade
        $imageFields = [
            'section1.image',
        ];

        foreach ($imageFields as $field) {

            // Get existing nested image
            $oldImage = data_get($existingData, $field);

            if ($request->hasFile($field)) {

                // Delete old image
                if (
                    !empty($oldImage) &&
                    is_string($oldImage) &&
                    Storage::disk('public')->exists($oldImage)
                ) {
                    Storage::disk('public')->delete($oldImage);
                }

                // Store new image
                $newImage = $request->file($field)->store(
                    'emptywishlist-content',
                    'public'
                );

                // Set new image inside the nested section
                data_set($data, $field, $newImage);

            } else {

                // Keep old image
                if (!empty($oldImage)) {
                    data_set($data, $field, $oldImage);
                }
            }
        }

        // Keep existing status if no status is submitted
        $status = $data['status'] ?? $pageContent->status;

        $data['status'] = $status;

        $pageContent->update([
            'page_contents' => $data,
            'status' => $status,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Empty wishlist page updated successfully.');
    }

    public function storeImages($request, $existingData, $imageFields, $dirname = 'sign-in', array $data = [])
    {

        foreach ($imageFields as $field) {

            if ($request->hasFile($field)) {

                // Delete old image if it exists
                if (
                    !empty($existingData->$field) &&
                    Storage::disk('public')->exists($existingData->$field)
                ) {
                    Storage::disk('public')->delete($existingData->$field);
                }

                // Store new image
                $data[$field] = $request->file($field)->store('sign-in', 'public');

            } else {

                // Keep old image
                if (!empty($existingData->$field)) {
                    $data[$field] = $existingData->$field;
                }
            }
        }

        return $data;
    }
}