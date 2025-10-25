<div class="app-menu"><!-- Sidebar -->
    <div class="navbar-vertical navbar nav-dashboard">
        <div class="h-100" data-simplebar>
            <!-- Brand logo -->
            <a class="navbar-brand" href="index.html">
                <img src="{{ asset('backend/assets/images/brand/logo/logo-2.svg') }}"
                    alt="dash ui - bootstrap 5 admin dashboard template" />
            </a>
            <!-- Navbar nav -->
            <ul class="navbar-nav flex-column" id="sideNavbar">

                <!-- Dashboard -->
                <!-- Dashboard -->
                <div class="nav flex-column">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active text-white fw-bold bg-primary rounded-pill px-3 py-2' : 'text-dark' }}">
                        <i data-feather="home" class="me-2 icon-xxs"></i>
                        Dashboard
                    </a>
                </div>

                <!-- CMS Heading -->
                {{-- <li class="nav-item">
                    <div class="navbar-heading">CMS</div>
                </li> --}}

                <!-- Single PDF Link -->
                <div class="nav flex-column">
                    <a href="{{ route('pdf.index') }}"
                        class="nav-link {{ request()->routeIs('pdf.index') ? 'active text-white fw-bold bg-primary rounded-pill px-3 py-2' : 'text-dark' }}">
                        <i data-feather="folder" class="me-2 icon-xxs"></i>
                        PDF
                    </a>
                </div>

                {{-- <!-- Category -->
                <div class="nav">
                    <a href="{{ route('category.index') }}"
                        class="nav-link {{ request()->routeIs('category.index') ? 'active text-white fw-bold bg-primary' : 'text-dark' }}">
                        <i data-feather="folder" class="me-2 icon-xxs"></i>
                        Category
                    </a>
                </div>

                <!-- FQA -->
                <div class="nav">
                    <a href="{{ route('fqa.index') }}"
                        class="nav-link {{ request()->routeIs('fqa.index') ? 'active text-white fw-bold bg-primary' : 'text-dark' }}">
                        <i data-feather="help-circle" class="me-2 icon-xxs"></i>
                        FQA
                    </a>
                </div> --}}

                {{-- <!-- Dynamic Pages -->
                <div class="nav">
                    <a href="{{ route('dynamic.index') }}"
                        class="nav-link {{ request()->routeIs('dynamic.index') ? 'active text-white fw-bold bg-primary' : 'text-dark' }}">
                        <i data-feather="file-text" class="me-2 icon-xxs"></i>
                        Dynamic Pages
                    </a>
                </div> --}}




                <!-- User Setting -->
                {{-- <div class="nav flex-column">
                    <a class="nav-link d-flex justify-content-between align-items-center
                       {{ request()->routeIs('user.create', 'user.list') ? '' : 'collapsed' }}"
                        href="#!" data-bs-toggle="collapse" data-bs-target="#navusers"
                        aria-expanded="{{ request()->routeIs('user.create', 'user.list') ? 'true' : 'false' }}"
                        aria-controls="navusers">

                        <!-- Parent text stays default color -->
                        <div class="text-dark">
                            <i data-feather="users" class="me-2 icon-xxs"></i>
                            Users
                        </div>

                        <!-- Arrow icon black -->
                        <i data-feather="chevron-down" class="arrow-icon text-dark"></i>
                    </a>

                    <div id="navusers"
                        class="collapse {{ request()->routeIs('user.create', 'user.list') ? 'show' : '' }}"
                        data-bs-parent="#sideNavbar">
                        <div class="nav flex-column ms-3">
                            <!-- Child links full-color active field -->
                            <a href="{{ route('user.create') }}"
                                class="nav-link {{ request()->routeIs('user.create') ? 'active text-white fw-bold bg-primary rounded-pill px-3 py-2' : 'text-dark' }}">
                                <i data-feather="user-plus" class="me-2 icon-xxs"></i>
                                Create Users
                            </a>

                            <a href="{{ route('user.list') }}"
                                class="nav-link {{ request()->routeIs('user.list') ? 'active text-white fw-bold bg-primary rounded-pill px-3 py-2' : 'text-dark' }}">
                                <i data-feather="list" class="me-2 icon-xxs"></i>
                                User List
                            </a>
                        </div>
                    </div>
                </div> --}}




                <!-- System Settings -->
                <div class="nav flex-column">
                    <a class="nav-link d-flex justify-content-between align-items-center
        {{ request()->routeIs('profile.edit', 'system.setting', 'admin.setting', 'mail.setting', 'directory.setting') ? '' : 'collapsed' }}"
                        href="#!" data-bs-toggle="collapse" data-bs-target="#navsystem"
                        aria-expanded="{{ request()->routeIs('profile.edit', 'system.setting', 'admin.setting', 'mail.setting', 'directory.setting') ? 'true' : 'false' }}"
                        aria-controls="navsystem">

                        <div class="text-dark">
                            <i data-feather="settings" class="me-2 icon-xxs"></i>
                            System Settings
                        </div>

                        <!-- Arrow icon -->
                        <i data-feather="chevron-down" class="arrow-icon text-dark"></i>
                    </a>

                    <!-- Collapse div must match data-bs-target -->
                    <div id="navsystem"
                        class="collapse {{ request()->routeIs('profile.edit', 'system.setting', 'admin.setting', 'mail.setting', 'directory.setting') ? 'show' : '' }}">
                        <div class="nav flex-column ms-3">
                            <a href="{{ route('profile.edit') }}"
                                class="nav-link {{ request()->routeIs('profile.edit') ? 'active text-white fw-bold bg-primary rounded-pill px-3 py-2' : 'text-dark' }}">
                                <i data-feather="user" class="me-2 icon-xxs"></i>
                                Profile Setting
                            </a>

                            <a href="{{ route('system.setting') }}"
                                class="nav-link {{ request()->routeIs('system.setting') ? 'active text-white fw-bold bg-primary rounded-pill px-3 py-2' : 'text-dark' }}">
                                <i data-feather="tool" class="me-2 icon-xxs"></i>
                                System Setting
                            </a>

                            <a href="{{ route('directory.setting') }}"
                                class="nav-link {{ request()->routeIs('directory.setting') ? 'active text-white fw-bold bg-primary rounded-pill px-3 py-2' : 'text-dark' }}">
                                <i data-feather="tool" class="me-2 icon-xxs"></i>
                                Active Directory Setting
                            </a>

                            <a href="{{ route('admin.setting') }}"
                                class="nav-link {{ request()->routeIs('admin.setting') ? 'active text-white fw-bold bg-primary rounded-pill px-3 py-2' : 'text-dark' }}">
                                <i data-feather="shield" class="me-2 icon-xxs"></i>
                                Admin Setting
                            </a>

                            <a href="{{ route('mail.setting') }}"
                                class="nav-link {{ request()->routeIs('mail.setting') ? 'active text-white fw-bold bg-primary rounded-pill px-3 py-2' : 'text-dark' }}">
                                <i data-feather="mail" class="me-2 icon-xxs"></i>
                                Mail Setting
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Role Management
                <div class="nav">
                    <a href="{{ route('admin.role.list') }}"
                    class="nav-link {{ request()->routeIs('admin.role.list') ? 'active text-white fw-bold bg-primary' : 'text-dark' }}">
                        <i data-feather="key" class="me-2 icon-xxs"></i>
                        Role Management
                    </a>
                </div> --}}




                <!-- Logout -->
                <li class="nav-item mt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link d-flex align-items-center btn btn-link p-0">
                            <i data-feather="power" class="nav-icon me-2 icon-xxs text-danger"></i>
                            Sign Out
                        </button>
                    </form>
                </li>


            </ul>
        </div>
    </div>
</div>
