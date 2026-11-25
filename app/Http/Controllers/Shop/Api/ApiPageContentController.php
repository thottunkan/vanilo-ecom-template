<?php

namespace App\Http\Controllers\Shop\Api;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use Exception;

class ApiPageContentController extends Controller
{

    public function getHomePageContent()
    {
        return $this->getPageContent('Home');
    }

    public function getForgotPasswordContent(){
        return $this->getPageContent('forgot-password');
    }

    public function getResetPasswordContent(){
        return $this->getPageContent('reset-password');
    }

    public function getShopPageContent()
    {
        return $this->getPageContent('Shop');
    }

    public function getAboutUsPageContent()
    {
        return $this->getPageContent('About Us');
    }

    public function getContactUsPageContent()
    {
        return $this->getPageContent('Contact Us');
    }

    public function getBlogPageContent()
    {
        return $this->getPageContent('Blog');
    }

    public function getFaqPageContent()
    {
        return $this->getPageContent('FAQ');
    }

    public function getNotFoundPageContent()
    {
        return $this->getPageContent('404 Not Found');
    }

    public function getEmptyCartPageContent()
    {
        return $this->getPageContent('Emty Cart');
    }

    public function getEmptyWishlistPageContent()
    {
        return $this->getPageContent('Empty Wishlist');
    }

    public function getEmptyOrdersPageContent()
    {
        return $this->getPageContent('Empty Orders');
    }

    public function getOrderCompletePageContent()
    {
        return $this->getPageContent('Order Complete');
    }

    public function getSignInContent()
    {
        return $this->getPageContent('sign-in');
    }

    public function getSignUpPageContent()
    {
        return $this->getPageContent('sign-up');
    }
    public function getEmptyWishlistContent(){
        return $this->getPageContent('Empty Wishlist');
    }

    private function getPageContent(string $pageName)
    {
        try {
            $content = PageContent::where('page_name', $pageName)
                ->value('page_contents');

            if (!$content) {
                return response()->json([
                    'status' => false,
                    'message' => 'Content not found',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Content fetched successfully',
                'data' => json_decode($content, true)
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unable to fetch contents',
                'data' => null
            ], 500);
        }
    }
}
