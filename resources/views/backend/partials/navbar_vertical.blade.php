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
                    <a href="{{ route('family.index') }}"
                        class="nav-link {{ request()->routeIs('family.index') ? 'active text-white fw-bold bg-primary rounded-pill px-3 py-2' : 'text-dark' }}">
                        <i data-feather="users" class="me-2 icon-xxs"></i>
                        Family
                    </a>
                </div>

                 <div class="nav">
                    <a href="{{ route('backend.edit') }}"
                        class="nav-link {{ request()->routeIs('backend.edit') ? 'active text-white fw-bold bg-primary' : 'text-dark' }}">
                        <i data-feather="dollar-sign" class="me-2 icon-xxs"></i>

                        Monthly Limit
                    </a>
                </div>




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


                <!-- Logout -->

            </ul>
        </div>
    </div>
</div>
