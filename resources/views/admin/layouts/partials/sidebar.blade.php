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
                <img src="{{ asset( Auth::user()->profilePicture ) }}" class="img-circle elevation-2" alt="User Image">
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
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::currentRouteName() == 'admin.dashboard' ? 'active' : null }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-header">Dropshipper</li>
                <li class="nav-item">
                    <a href="{{ route('admin.dropshipper') }}" class="nav-link {{ Route::currentRouteName() == 'admin.dropshipper' ? 'active' : null }}">
                        <i class="nav-icon far fa-user"></i>
                        <p>
                            Dropshippers
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.toko') }}" class="nav-link">
                        <i class="nav-icon far fa-building"></i>
                        <p>
                            Toko
                        </p>
                    </a>
                </li>
                <li class="nav-header">Supplier</li>
                <li class="nav-item">
                    <a href="{{ route('admin.supplier') }}" class="nav-link">
                        <i class="nav-icon far fa-user"></i>
                        <p>
                            Suppliers
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.withdraw') }}" class="nav-link">
                        <i class="nav-icon far fa-arrow-alt-circle-right"></i>
                        <p>
                            Withdraw
                        </p>
                    </a>
                </li>
                <li class="nav-header">Data</li>
                <li class="nav-item">
                    <a href="{{ route('admin.category') }}" class="nav-link">
                        <i class="nav-icon far fa-bookmark"></i>
                        <p>
                            Category
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.variant') }}" class="nav-link">
                        <i class="nav-icon far fa-bookmark"></i>
                        <p>
                            Variants
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.design') }}" class="nav-link">
                        <i class="nav-icon far fa-file-image"></i>
                        <p>
                            Design
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.testimony') }}" class="nav-link">
                        <i class="nav-icon far fa-user-circle"></i>
                        <p>
                            Testimony
                        </p>
                    </a>
                </li>
                <li class="nav-header">Logs</li>
                <li class="nav-item">
                    <a href="{{ route('admin.mutation') }}" class="nav-link">
                        <i class="nav-icon far fa-file"></i>
                        <p>
                            Moota Mutation
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.logs') }}" class="nav-link">
                        <i class="nav-icon far fa-file"></i>
                        <p>
                            Logs
                        </p>
                    </a>
                </li>
                <li class="nav-header">Settings</li>
                <li class="nav-item">
                    <a href="{{ route('admin.settings') }}" class="nav-link">
                        <i class="nav-icon far fa-circle"></i>
                        <p>
                            Settings
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
