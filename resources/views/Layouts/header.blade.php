<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Skydash Admin</title>

    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">

    <!-- Include Toastify CSS & JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome Latest CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Plugin css for this page -->
    <!-- <link rel="stylesheet" href="assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css"> -->
    <link rel="stylesheet" href="assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="assets/js/select.dataTables.min.css">
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}


    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- endinject -->

    <link rel="shortcut icon" href="assets/images/favicon.png" />
</head>

<body>
    <div class="container-scroller">
            <!-- partial:partials/_navbar.html -->
            <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row ">
                <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                    <a class="navbar-brand brand-logo me-5" href="index.html"><img src="assets/images/logo.svg"
                            class="me-2" alt="logo" /></a>
                    <a class="navbar-brand brand-logo-mini" href="index.html"><img src="assets/images/logo-mini.svg"
                            alt="logo" /></a>
                </div>
                <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                        <span class="icon-menu"></span>
                    </button>

                    <ul class="navbar-nav navbar-nav-right">
                        <!-- Profile Dropdown -->
                        <li class="nav-item nav-profile dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="true">
                                <span class="me-2">{{ Auth::user()->name }}</span>
                            </a>
                            <li class="nav-item nav-profile dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                                <img src="{{ Auth::check() && Auth::user()->profile_image ? asset('/storage/' . Auth::user()->profile_image) : asset('assets/images/faces/face28.jpg') }}"
                                    alt="profile" class="rounded-circle" width="35" height="35" />
                                </a>
                                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                                    @if(Auth::check())
                                        @if(Auth::user()->role === 'admin')
                                            <a href="{{ route('admin.profile') }}" class="dropdown-item">
                                                <i class="ti-settings text-primary"></i> Profile
                                            </a>
                                        @elseif(Auth::user()->role === 'manager')
                                            <a href="{{ route('manager.profile') }}" class="dropdown-item">
                                                <i class="ti-settings text-primary"></i> Profile
                                            </a>
                                        @endif
                                    @endif

                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="ti-power-off text-primary"></i> Logout
                                        </button>
                                    </form>
                                </div>

                            </li>
                        </li>
                    </ul>

                    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                        data-toggle="offcanvas">
                        <span class="icon-menu"></span>
                    </button>
                </div>

            </nav>
            <!-- partial -->
            <div class="container-fluid page-body-wrapper" >
                <!-- partial:partials/_sidebar.html -->
                <nav class="sidebar sidebar-offcanvas"  id="sidebar" style=" display: flex; flex-direction: column; height: 100vh;">
                    <ul class="nav" style="flex-grow: 1; poistion: fixed;">
                        <!-- Other Sidebar Items -->
                        <li class="nav-item">
                            @if(Auth::check() && Auth::user()->role === 'admin')
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="icon-grid menu-icon"></i>
                                    <span class="menu-title">Admin Dashboard</span>
                                </a>
                            @elseif(Auth::check() && Auth::user()->role === 'manager')
                                <a class="nav-link" href="{{ route('manager.dashboard') }}">
                                    <i class="icon-grid menu-icon"></i>
                                    <span class="menu-title">Manager Dashboard</span>
                                </a>
                            @endif
                        </li>

                        <li class="nav-item">
                            @if(Auth::check() && Auth::user()->role === 'admin')
                                <a class="nav-link" href="{{ route('admin.SupplierManagement') }}">
                                    <i class="fa fa-industry menu-icon"></i>
                                    <span class="menu-title">Supply Management</span>
                                </a>
                            @elseif(Auth::check() && Auth::user()->role === 'manager')
                                <a class="nav-link" href="{{ route('manager.SupplierManagement') }}">
                                    <i class="fa fa-industry menu-icon"></i>
                                    <span class="menu-title">Supply Management</span>
                                </a>
                            @endif
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa fa-warehouse menu-icon"></i>
                                <span class="menu-title">Stock Management</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa fa-credit-card menu-icon"></i>
                                <span class="menu-title">Purchases</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa fa-map menu-icon"></i>
                                <span class="menu-title">Inventory Tracking</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa fa-shopping-cart menu-icon"></i>
                                <span class="menu-title">Sales</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa fa-first-order menu-icon"></i>
                                <span class="menu-title">Orders Management</span>
                            </a>
                        </li>
                    </ul>
                    {{-- <ul class="nav user-pages" style="margin-top: auto;">
                        <li class="nav-item">
                            <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#auth" aria-expanded="false"
                                aria-controls="auth">
                                <div>
                                    <i class="icon-head menu-icon"></i>
                                    <span class="menu-title">User Pages</span>
                                </div>
                                <i class="menu-arrow"></i>
                            </a> --}}
                            {{-- <div class="collapse" id="auth">
                                <ul class="nav flex-column sub-menu">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-white">
                                            <i class="ti-power-off text-white"></i> Logout
                                        </button>
                                    </form>
                                    @if(Auth::check())
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('admin.profile') }}" class="dropdown-item text-white">
                                            <i class="fa fa-user" aria-hidden="true"></i> Profile
                                        </a>
                                    @elseif(Auth::user()->role === 'manager')
                                        <a href="{{ route('manager.profile') }}" class="dropdown-item text-white">
                                            <i class="fa fa-user" aria-hidden="true"></i> Profile
                                        </a>
                                    @endif
                                @endif

                                </ul>
                            </div> --}}
                        {{-- </li>
                    </ul> --}}

                </nav>

