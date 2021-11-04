<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="https://pvotdigital.com/" class="brand-link elevation-4 text-center">
        <span class="brand-text font-weight-light"><strong>PVOT</strong> DIGITAL</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset(Auth::user()->profilePicture) }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('supplier.dashboard') }}" class="nav-link {{ Route::currentRouteName() == 'supplier.dashboard' ? 'active' : null }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-header">Dropshipper</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Dropshipper List
                        </p>
                    </a>
                </li>
                <li class="nav-header">Product</li>
                <li class="nav-item">
                    <a href="{{ route('supplier.product') }}" class="nav-link {{ Route::currentRouteName() == 'supplier.product' ? 'active' : null }}">
                        <i class="nav-icon fas fa-gift"></i>
                        <p>
                            Product
                        </p>
                    </a>
                </li>
                <li class="nav-header">Transaction</li>
                <li class="nav-item">
                    <a href="{{ route('supplier.orders') }}" class="nav-link {{ Route::currentRouteName() == 'supplier.orders' ? 'active' : null }}">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>
                            Orders
                            <span class="badge badge-info right">0</span>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('supplier.history') }}" class="nav-link {{ Route::currentRouteName() == 'supplier.history' ? 'active' : null }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            History
                        </p>
                    </a>
                </li>
                <li class="nav-header">E-Wallet</li>
                <li class="nav-item">
                    <a href="{{ route('supplier.withdraw') }}" class="nav-link {{ Route::currentRouteName() == 'supplier.withdraw' ? 'active' : null }}">
                        <i class="nav-icon fas fa-money-bill"></i>
                        <p>
                            Withdraw
                        </p>
                    </a>
                </li>
                <li class="nav-header">Settings</li>
                <li class="nav-item">
                    <a href="{{ route('supplier.profile') }}" class="nav-link">
                        <i class="nav-icon far fa-user"></i>
                        <p>
                            Profile
                        </p>
                    </a>
                </li>
                <li class="nav-item">

                    <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon far fa-arrow-alt-circle-left"></i>
                        <p>
                            Logout
                        </p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
