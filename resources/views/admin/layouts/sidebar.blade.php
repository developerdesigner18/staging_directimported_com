@php
    $user = auth()->user();
@endphp
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{route('admin.dashboard')}}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{asset('assets/logo/' . getSetting()->admin_logo)}}" alt="admin dark logo" height="20"
                    width="200">
            </span>2
            <span class="logo-lg">
                <img src="{{asset('assets/logo/' . getSetting()->admin_logo)}}" alt="admin dark logo" height="100"
                    width="200">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{route('admin.dashboard')}}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{asset('assets/logo/' . getSetting()->admin_logo)}}" alt="admin light logo" height="20"
                    width="200">
            </span>
            <span class="logo-lg">
                <img src="{{asset('assets/logo/' . getSetting()->admin_logo)}}" alt="admin light logo" height="100"
                    width="200">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                @if($user->hasRole('admin') || $user->can('dashboard'))

                    <li class="nav-item">
                        <a href="{{route('admin.dashboard')}}"
                            class="nav-link menu-link @if(request()->routeIs('admin.dashboard')) active @endif">
                            <i class="bx bx-grid-alt"></i> <span data-key="t-dashboards">Dashboards</span>
                        </a>
                    </li>
                @endif

                @if($user->hasRole('admin') || $user->can('bookings'))

                    <li class="nav-item">
                        <a href="{{route('admin.booking.index')}}"
                            class="nav-link menu-link  @if(request()->routeIs('admin.booking.*')) active @endif">
                            <i class="bx bx-bookmark"></i> <span data-key="t-dashboards">Bookings</span>
                        </a>
                    </li>
                @endif

                @if($user->hasRole('admin') || $user->can('emails'))
                    <li class="nav-item">
                        <a href="{{route('admin.contact_requests.index')}}"
                            class="nav-link menu-link @if(request()->routeIs('admin.contact_requests.*')) active @endif">
                            <i class="ri-question-answer-line"></i> <span>Contact Requests</span>
                        </a>
                    </li>
                @endif

                @if($user->hasRole('admin') || $user->can('cars'))
                    <li class="nav-item">
                        <a class="nav-link menu-link @if(request()->is('admin/car*') || request()->is('admin/car/category*') || request()->routeIs('admin.manufacturer.*') || request()->routeIs('admin.auctiongrade.*')) active @endif"
                            href="#sidebarCars" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ (request()->is('admin/car*') || request()->is('admin/car/category*') || request()->routeIs('admin.manufacturer.*') || request()->routeIs('admin.auctiongrade.*')) ? 'true' : 'false' }}"
                            aria-controls="sidebarCars">
                            <i class="ri-car-line"></i> <span data-key="t-multi-level">Cars</span>
                        </a>
                        <div class="menu-dropdown collapse @if(request()->is('admin/car*') || request()->is('admin/car/category*') || request()->routeIs('admin.manufacturer.*') || request()->routeIs('admin.auctiongrade.*')) show @endif"
                            id="sidebarCars">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{route('admin.car.index')}}"
                                        class="nav-link @if(request()->routeIs('admin.car.index')) active @endif"
                                        data-key="t-level-1.1">Cars</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('admin.category.car.index', ['type' => 'car'])}}"
                                        class="nav-link @if(request()->is('admin/car/category*')) active @endif"
                                        data-key="t-level-1.1">Cars Categories</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('admin.manufacturer.index')}}"
                                        class="nav-link @if(request()->routeIs('admin.manufacturer.index')) active @endif"
                                        data-key="t-level-1.1">Manage Manufacturers</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('admin.car.configuration')}}"
                                        class="nav-link @if(request()->routeIs('admin.car.configuration')) active @endif"
                                        data-key="t-level-1.1">Cars
                                        Configuration</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('admin.auctiongrade.auction-grade')}}"
                                        class="nav-link @if(request()->routeIs('admin.auctiongrade.*')) active @endif"
                                        data-key="t-level-1.1">Manage Auction Grades</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if($user->hasRole('admin') || $user->can('services'))
                    <li class="nav-item">
                        <a href="{{route('admin.service.index')}}"
                            class="nav-link menu-link @if(request()->routeIs('admin.service.*')) active @endif">
                            <i class="bx bx-layer"></i> <span data-key="t-dashboards">Services</span>
                        </a>
                    </li>
                @endif

                @if($user->hasRole('admin') || $user->can('about_us'))
                    <li class="nav-item">
                        <a href="{{route('admin.home_section.edit')}}"
                            class="nav-link menu-link @if(request()->routeIs('admin.home_section.*')) active @endif">
                            <i class="ri-information-line"></i> <span data-key="t-dashboards">About Us</span>
                        </a>
                    </li>
                @endif



                @if($user->hasRole('admin') || $user->can('accessories_equipments'))
                    <li class="nav-item">
                        <a href="{{route('admin.accessory.index')}}"
                            class="nav-link menu-link @if(request()->routeIs('admin.accessory.*')) active @endif">
                            <i class="ri-tools-line"></i> <span data-key="t-dashboards">Accessories & Equipments</span>
                        </a>
                    </li>
                @endif

                @if($user->hasRole('admin') || $user->can('location'))

                    <li class="nav-item">
                        <a href="{{route('admin.location.index')}}"
                            class="nav-link menu-link @if(request()->routeIs('admin.location.*')) active @endif">
                            <i class="ri-map-pin-fill"></i><span data-key="t-dashboards">Location</span>
                        </a>
                    </li>
                @endif

                @if($user->hasRole('admin') || $user->can('users'))
                    <li class="nav-item">
                        <a href="{{route('admin.user.index')}}"
                            class="nav-link menu-link @if(request()->routeIs('admin.user.*')) active @endif">
                            <i class="bx bx-group"></i> <span data-key="t-dashboards">Users</span>
                        </a>
                    </li>
                @endif
                @if($user->hasRole('admin') || $user->can('employee'))

                    <li class="nav-item">
                        <a href="{{route('admin.employee.index')}}"
                            class="nav-link menu-link @if(request()->routeIs('admin.employee.*')) active @endif">
                            <i class="bx bx-user-pin"></i> <span data-key="t-dashboards">Employee</span>
                        </a>
                    </li>
                @endif

                @if($user->hasRole('admin') || $user->can('gallery'))

                    <li class="nav-item">
                        <a href="{{route('admin.gallery.index')}}"
                            class="nav-link menu-link @if(request()->routeIs('admin.gallery.*')) active @endif">
                            <i class="bx bx-images"></i> <span data-key="t-dashboards">Gallery</span>
                        </a>
                    </li>
                @endif

                @if($user->hasRole('admin') || $user->can('bookings'))

                    <li class="nav-item">
                        <a href="{{route('admin.slider.index')}}"
                            class="nav-link menu-link @if(request()->routeIs('admin.slider.*')) active @endif">
                            <i class="bx bx-slideshow"></i> <span data-key="t-dashboards">Slider</span>
                        </a>
                    </li>
                @endif

                @if($user->hasRole('admin') || $user->can('manage_information'))
                    <li class="nav-item">
                        <a class="nav-link menu-link @if(request()->routeIs('admin.rental-policies.*') || request()->routeIs('admin.faq.*')) active @endif"
                            href="#sidebarInfo" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ (request()->routeIs('admin.rental-policies.*') || request()->routeIs('admin.faq.*')) ? 'true' : 'false' }}"
                            aria-controls="sidebarInfo">
                            <i class="bx bx-info-circle"></i> <span data-key="t-multi-level">Manage Information</span>
                        </a>
                        <div class="menu-dropdown collapse @if(request()->routeIs('admin.rental-policies.*') || request()->routeIs('admin.faq.*')) show @endif"
                            id="sidebarInfo">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('admin.rental-policies.index') }}"
                                        class="nav-link @if(request()->routeIs('admin.rental-policies.*')) active @endif"
                                        data-key="t-level-1.1">Rental Policies</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.faq.index') }}"
                                        class="nav-link @if(request()->routeIs('admin.faq.*')) active @endif"
                                        data-key="t-level-1.1">FAQ</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if($user->hasRole('admin') || $user->can('newsletters'))

                    <li class="nav-item">
                        <a href="#" class="nav-link menu-link">
                            <i class="bx bx-mail-send"></i> <span data-key="t-dashboards">Newsletters</span>
                        </a>
                    </li>
                @endif

                @if($user->hasRole('admin') || $user->can('emails'))

                    <li class="nav-item">
                        <a class="nav-link menu-link @if(request()->routeIs('admin.email.*') || request()->routeIs('admin.custom-mails.*')) active @endif"
                            href="#sidebarUI" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ (request()->routeIs('admin.email.*') || request()->routeIs('admin.custom-mails.*')) ? 'true' : 'false' }}"
                            aria-controls="sidebarUI">
                            <i class="bx bx-envelope"></i> <span data-key="t-base-ui">Emails</span>
                        </a>
                        <div class="collapse menu-dropdown mega-dropdown-menu @if(request()->routeIs('admin.email.*') || request()->routeIs('admin.custom-mails.*')) show @endif"
                            id="sidebarUI">
                            <div class="row">
                                <div class="col-lg-4">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="{{ route('admin.email.index') }}"
                                                class="nav-link @if(request()->routeIs('admin.email.*')) active @endif">
                                                <i class="bx bx-cog me-1"></i>
                                                <span data-key="t-dashboards">System Template</span>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="{{ route('admin.custom-mails.index') }}"
                                                class="nav-link @if(request()->routeIs('admin.custom-mails.*')) active @endif">
                                                <i class="bx bx-paper-plane me-1"></i>
                                                <span data-key="t-dashboards">Custom Mail</span>
                                            </a>
                                        </li>

                                    </ul>
                                </div>

                            </div>
                        </div>
                    </li>
                @endif

                @if($user->hasRole('admin') || $user->can('system_settings'))

                    <li class="nav-item">
                        <a href="{{route('admin.system.settings')}}"
                            class="nav-link menu-link @if(request()->routeIs('admin.system.settings')) active @endif">
                            <i class="bx bx-cog"></i> <span data-key="t-dashboards">System Settings</span>
                        </a>
                    </li>

                    {{-- <li class="nav-item">
                        <a href="{{route('admin.labels.index')}}"
                            class="nav-link menu-link @if(request()->routeIs('admin.labels.*')) active @endif">
                            <i class="bx bx-tag"></i> <span>Manage Labels</span>
                        </a>
                    </li> --}}
                @endif
            </ul>

        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>