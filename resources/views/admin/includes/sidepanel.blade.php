<nav class="pc-sidebar">
    <div class="navbar-wrapper">

        <div class="m-header">
            <a href="../dashboard/index.html" class="b-brand text-primary">
                <img src="../assets/images/logo-white.svg" alt="logo image" class="logo-lg" />
            </a>
        </div>

        <div class="navbar-content">
            <ul class="pc-navbar">

                <!-- Dashboard -->
                <li class="pc-item pc-">
                    <a href="{{ route('admin.dashboard') }}" class="pc-link">
                        <span class="pc-micon">
                            <i class="ph ph-gauge"></i>
                        </span>
                        <span class="pc-mtext">Dashboard</span>
                    </a>
                </li>

                <!-- Catalog -->
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <i class="ph ph-package"></i>
                        </span>
                        <span class="pc-mtext">Catelog</span>
                        <span class="pc-arrow">
                            <i data-feather="chevron-right"></i>
                        </span>
                    </a>

                    <ul class="pc-submenu">

                        <li class="pc-item">
                            <a href="{{ route('admin.product.index') }}" class="pc-link">
                                <i class="ph ph-cube pc-micon-sm"></i> Products
                            </a>
                        </li>

                        <li class="pc-item">
                            <a href="{{ route('admin.category.index') }}" class="pc-link">
                                <i class="ph ph-squares-four pc-micon-sm"></i> Categories
                            </a>
                        </li>

                        <li class="pc-item">
                            <a href="{{ route('admin.brand.index') }}" class="pc-link">
                                <i class="ph ph-tag pc-micon-sm"></i> Brands
                            </a>
                        </li>

                    </ul>
                </li>

                <!-- Sales -->
                <li class="pc-item pc-hasmenu">

                    <a href="#!" class="pc-link">

                        <span class="pc-micon">
                            <i class="ph ph-shopping-cart"></i>
                        </span>

                        <span class="pc-mtext">Sales</span>

                        <span class="pc-arrow">
                            <i data-feather="chevron-right"></i>
                        </span>

                    </a>


                    <ul class="pc-submenu">
                        <li class="pc-item">
                            <a href="{{ route('admin.orders.index') }}" class="pc-link">
                                <i class="ph ph-receipt pc-micon-sm"></i> Orders
                            </a>
                        </li>
                        <li class="pc-item">
                            <a href="{{ route('admin.taxcategory.index') }}" class="pc-link">
                                <i class="ph ph-percent pc-micon-sm"></i> TAX Categories
                            </a>
                        </li>

                        <li class="pc-item">
                            <a href="{{ route('admin.taxrate.index') }}" class="pc-link">
                                <i class="ph ph-calculator pc-micon-sm"></i> Tax Rates
                            </a>
                        </li>

                        <li class="pc-item">
                            <a href="{{ route('admin.discount.index') }}" class="pc-link">
                                <i class="ph ph-percent pc-micon-sm"></i> Discount
                            </a>
                        </li>

                    </ul>

                </li>

                <!-- Customer -->
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <i class="ph ph-users"></i>
                        </span>
                        <span class="pc-mtext">Customer</span>
                        <span class="pc-arrow">
                            <i data-feather="chevron-right"></i>
                        </span>
                    </a>


                    <ul class="pc-submenu">
                        <li class="pc-item">
                            <a href="{{ route('admin.customer.index') }}" class="pc-link">
                                <i class="ph ph-user pc-micon-sm"></i> Customers
                            </a>
                        </li>
                        <li class="pc-item">
                            <a href="{{ route('admin.review.index') }}" class="pc-link">
                                <i class="ph ph-star pc-micon-sm"></i> Reviews
                            </a>
                        </li>
                    </ul>
                </li>


                <!-- Settings -->
                <li class="pc-item pc-hasmenu">

                    <a href="#!" class="pc-link">

                        <span class="pc-micon">
                            <i class="ph ph-gear-six"></i>
                        </span>

                        <span class="pc-mtext">Settings</span>

                        <span class="pc-arrow">
                            <i data-feather="chevron-right"></i>
                        </span>

                    </a>


                    <ul class="pc-submenu">


                        <li class="pc-item">
                            <a href="{{ route('admin.site-settings.create') }}" class="pc-link">
                                <i class="ph ph-sliders pc-micon-sm"></i> Site Settings
                            </a>
                        </li>

                        <li class="pc-item">
                            <a href="{{ route('admin.email-settings.create') }}" class="pc-link">
                                <i class="ph ph-envelope pc-micon-sm"></i> Email Settings
                            </a>
                        </li>

                    </ul>
                </li>

                <!-- Blog -->
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <i class="ph ph-newspaper-clipping"></i>
                        </span>
                        <span class="pc-mtext">Blog</span>
                        <span class="pc-arrow">
                            <i data-feather="chevron-right"></i>
                        </span>
                    </a>


                    <ul class="pc-submenu">
                        <li class="pc-item">
                            <a href="{{ route('admin.blog-category.index') }}" class="pc-link">
                                <i class="ph ph-folder-simple pc-micon-sm"></i> Blog Categories
                            </a>
                        </li>
                        <li class="pc-item">
                            <a href="{{ route('admin.blog.index') }}" class="pc-link">
                                <i class="ph ph-article pc-micon-sm"></i> Blogs
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Design -->
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <i class="ph ph-palette"></i>
                        </span>
                        <span class="pc-mtext">Design</span>
                        <span class="pc-arrow">
                            <i data-feather="chevron-right"></i>
                        </span>
                    </a>


                    <ul class="pc-submenu">
                        <li class="pc-item">
                            <a href="{{ route('admin.color.create') }}" class="pc-link">
                                <i class="ph ph-drop pc-micon-sm"></i> Colors
                            </a>
                        </li>
                        <li class="pc-item">
                            <a href="{{ route('admin.font.index') }}" class="pc-link">
                                <i class="ph ph-text-aa pc-micon-sm"></i> Fonts
                            </a>
                        </li>
                        <li class="pc-item">
                            <a href="{{ route('admin.custom-css.index') }}" class="pc-link">
                                <i class="ph ph-code pc-micon-sm"></i> Custom CSS
                            </a>
                        </li>
                        <li class="pc-item">
                            <a href="{{ route('admin.custom-js.index') }}" class="pc-link">
                                <i class="ph ph-file-js pc-micon-sm"></i> Custom JS
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Payments -->
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <i class="ph ph-credit-card"></i>
                        </span>
                        <span class="pc-mtext">Payments</span>
                        <span class="pc-arrow">
                            <i data-feather="chevron-right"></i>
                        </span>
                    </a>

                    <ul class="pc-submenu">
                        <li class="pc-item">
                            <a href="" class="pc-link">
                                <i class="ph ph-clock-counter-clockwise pc-micon-sm"></i> Payment history
                            </a>
                        </li>
                        <li class="pc-item">
                            <a href="{{ route('admin.payment-method.index') }}" class="pc-link">
                                <i class="ph ph-wallet pc-micon-sm"></i> Payment methods
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <i class="ph ph-question"></i>
                        </span>
                        <span class="pc-mtext">FAQ</span>
                        <span class="pc-arrow">
                            <i data-feather="chevron-right"></i>
                        </span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item">
                            <a href="{{ route('admin.faq-category.index') }}" class="pc-link">
                                <i class="ph ph-folder pc-micon-sm"></i> FAQ Categories
                            </a>
                        </li>

                        <li class="pc-item">
                            <a href="{{ route('admin.faq.index') }}" class="pc-link">
                                <i class="ph ph-question pc-micon-sm"></i> FAQs
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="pc-item pc-">
                    <a href="{{ route('admin.policy.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <i class="ph ph-shield-check"></i>
                        </span>
                        <span class="pc-mtext">Policies</span>

                    </a>
                </li>
                <li class="pc-item pc-">
                    <a href="{{ route('admin.menu.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <i class="ph ph-list"></i>
                        </span>
                        <span class="pc-mtext">Menus</span>

                    </a>
                </li>
                <li class="pc-item pc-">
                    <a href="{{ route('admin.pagecontent.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <i class="ph ph-file-text"></i>
                        </span>
                        <span class="pc-mtext">Pages</span>

                    </a>
                </li>


                <li class="pc-item pc-">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <i class="ph ph-image"></i>
                        </span>
                        <span class="pc-mtext">Banners</span>

                    </a>
                </li>
                <li class="pc-item pc-">
                    <a href="{{ route('admin.gallery.create') }}" class="pc-link">
                        <span class="pc-micon">
                            <i class="ph ph-image"></i>
                        </span>
                        <span class="pc-mtext">Gallery</span>
                    </a>
                </li>
                <li class="pc-item pc-">
                    <a href="{{ route('admin.newsletter.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <i class="ph ph-envelope-simple"></i>
                        </span>
                        <span class="pc-mtext">Newsletters</span>
                    </a>
                </li>
                <li class="pc-item pc-">
                    <a href="{{ route('admin.contact-messages.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <i class="ph ph-chat-circle-text"></i>
                        </span>
                        <span class="pc-mtext">Contact messages</span>

                    </a>
                </li>

            </ul>

            {{-- <div class="card nav-action-card bg-brand-color-1">

                <div class="card-body" style="background-image:url('../assets/images/layout/nav-card-bg.svg')">

                    <h5 class="text-white">
                        Upgrade to Pro
                    </h5>

                    <p class="text-white text-opacity-75">
                        To get more features and components
                    </p>

                    <a href="https://codedthemes.com/item/gradient-able-admin-template/" class="btn btn-light"
                        target="_blank">
                        Buy now
                    </a>

                </div>

            </div> --}}

        </div>
    </div>
</nav>
