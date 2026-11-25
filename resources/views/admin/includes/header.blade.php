  <header class="pc-header">
      <div class="m-header">
          <a href="{{ route('admin.dashboard') }}" class="b-brand text-primary">
              <!-- ========   Change your logo from here   ============ -->
              {{-- <img src=" {{ asset('admin-assets/images/logo-white.svg') }}" alt="logo image" class="logo-lg" /> --}}
              <h5 style="color: white;">Hi {{ Auth::user()->name ?? 'Super admin' }}</h5>
          </a>
      </div>
      <div class="header-wrapper"> <!-- [Mobile Media Block] start -->
          <div class="me-auto pc-mob-drp">
              <ul class="list-unstyled">
                  <!-- ======= Menu collapse Icon ===== -->
                  <li class="pc-h-item pc-sidebar-collapse">
                      <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                          <i class="ph ph-list"></i>
                      </a>
                  </li>
                  <li class="pc-h-item pc-sidebar-popup">
                      <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                          <i class="ph ph-list"></i>
                      </a>
                  </li>
                  <li class="dropdown pc-h-item">
                      <a class="pc-head-link dropdown-toggle arrow-none m-0" data-bs-toggle="dropdown" href="#"
                          role="button" aria-haspopup="false" aria-expanded="false">
                          <i class="ph ph-magnifying-glass"></i>
                      </a>
                      <div class="dropdown-menu pc-h-dropdown drp-search">
                          <form class="px-3">
                              <div class="form-group mb-0 d-flex align-items-center">
                                  <input type="search" class="form-control border-0 shadow-none"
                                      placeholder="Search here. . ." />
                                  <button class="btn btn-light-secondary btn-search">Search</button>
                              </div>
                          </form>
                      </div>

                  </li>
                  <li class="pc-h-item text-end">
                      <a href="{{ route('shop.home') }}" class="btn btn-sm btn-primary">
                        {{-- <i class="ph ph-eye"></i> --}}
                        visit site
                      </a>

                      {{-- <a href="{{ url('/') }}" class="pc-head-link ms-0" target="_blank" title="View Site">
                          <i class="ph ph-eye"></i>
                          <span class="d-none d-md-inline ms-1">View Site</span>
                      </a> --}}
                  </li>
              </ul>
          </div>
          <!-- [Mobile Media Block end] -->
          <div class="ms-auto">

              <ul class="list-unstyled">
                  <li class="dropdown pc-h-item header-user-profile">

                      <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                          role="button" aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
                          <img src="{{ asset('admin-assets/images/user/avatar-2.jpg') }}" alt="user-image"
                              class="user-avtar" />
                      </a>
                      <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                          <div class="dropdown-body">
                              <div class="profile-notification-scroll position-relative"
                                  style="max-height: calc(100vh - 225px)">
                                  <ul class="list-group list-group-flush w-100">
                                      {{-- <li class="list-group-item">
                                          <a href="{{ route('admin.dashboard') }}"
                                              target="_blank" class="dropdown-item">
                                              <span class="d-flex align-items-center">
                                                  <i class="ph ph-arrow-circle-down"></i>
                                                  <span>Dashboard</span>
                                              </span>
                                          </a>
                                      </li> --}}
                                      <li class="list-group-item">

                                          <a href="{{ route('admin.profile.edit', Auth::id()) }}" class="dropdown-item">
                                              <span class="d-flex align-items-center">
                                                  <i class="ph ph-user-circle"></i>
                                                  <span>Edit profile</span>
                                              </span>
                                          </a>
                                          <a href="{{ route('admin.profile.change-password', Auth::id()) }}"
                                              class="dropdown-item">
                                              <span class="d-flex align-items-center">
                                                  <i class="ph ph-bell"></i>
                                                  <span>Change password</span>
                                              </span>
                                          </a>
                                          <a href="#" class="dropdown-item">
                                              <span class="d-flex align-items-center">
                                                  <i class="ph ph-gear-six"></i>
                                                  <span>Settings</span>
                                              </span>
                                          </a>
                                      </li>
                                      <li class="list-group-item">
                                          {{-- <a href="#" class="dropdown-item">
                                              <span class="d-flex align-items-center">
                                                  <i class="ph ph-plus-circle"></i>
                                                  <span>Add account</span>
                                              </span>
                                          </a> --}}
                                          <div href="#" class="dropdown-item">
                                              <form action="{{ route('admin.logout') }}" method="POST">
                                                  @csrf

                                                  <button type="submit"
                                                      class="dropdown-item border-0 bg-transparent w-100 text-start">
                                                      <span class="d-flex align-items-center">
                                                          <i class="ph ph-power"></i>
                                                          <span class="ms-2">Logout</span>
                                                      </span>
                                                  </button>
                                              </form>
                                          </div>
                                      </li>
                                  </ul>
                              </div>
                          </div>
                      </div>
                  </li>
              </ul>
          </div>
      </div>
  </header>
