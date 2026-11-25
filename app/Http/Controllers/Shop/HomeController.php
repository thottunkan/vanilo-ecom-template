<?php

namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Gallery;
use App\Models\Menu;

use App\Models\PageContent;
use App\Models\SiteInfo;
use Illuminate\Http\Request;
use Vanilo\Category\Models\Taxon;
use Vanilo\Foundation\Models\MasterProduct;

class HomeController extends Controller
{
    public function index()
    {
        
        return view('shop.home', [
            'site' => SiteInfo::first(),
            'menus' => Menu::where('status', 'active')->orderBy('order')->get(),
            'categories' => Taxon::whereNull('parent_id')->orderBy('name')->get(),
            // 'featuredProducts' => MasterProduct::with(['media', 'taxons', 'discount'])
            //     ->where('state', 'active')->where('is_featured', true)->latest()->take(8)->get(),
            'latestProducts' => MasterProduct::with(['media', 'taxons', 'discount'])
                ->where('state', 'active')->latest()->take(8)->get(),
            
            'brands' => Brand::orderBy('name')->take(12)->get(),
            'content' => (PageContent::where('page_name', 'Home')->value('page_contents')),

            'gallery' => Gallery::first(),
        ]);
    }
}
