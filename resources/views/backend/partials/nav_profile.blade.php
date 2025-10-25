<li class="dropdown ms-2">
    <a class="rounded-circle" href="#!" role="button" id="dropdownUser" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">

        <div class="mb-3 position-relative d-inline-block" style="width: 50px; height: 50px; cursor: pointer;">
            <label class="position-relative w-100 h-100 m-0 p-0">
                <img src="{{ Auth::user() && Auth::user()->avatar ? asset('uploads/avatar/' . Auth::user()->avatar) : asset('images/default-avatar.png') }}"
                    class="rounded-circle border border-2 w-100 h-100" style="object-fit: cover;" alt="Avatar">
                <input type="file" name="avatar" id="avatarInput" hidden onchange="previewAvatar(this)">
                <!-- Hover overlay -->
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center rounded-circle"
                    style="background: rgba(0,0,0,0.3); opacity: 0; transition: opacity 0.3s;">
                    <i class="mdi mdi-camera text-white fs-4"></i>
                </div>
                <!-- Active green point -->
                <span class="position-absolute bg-success rounded-circle border border-white"
                    style="width: 12px; height: 12px; bottom: 0; right: 0;"></span>
            </label>
        </div>

    </a>
    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
        <div class="px-4 pb-0 pt-2">
            <div class="lh-1">
                {{-- <h5 class="mb-1">{{ $users->name}}</h5> --}}
                <h5 class="mb-1">{{ Auth::user()->name ?? 'Guest' }}</h5>

            </div>
            <div class="dropdown-divider mt-3 mb-2"></div>
        </div>

        <ul class="list-unstyled">
            <li>
                <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.edit') }}">
                    <i class="me-2 icon-xxs dropdown-item-icon" data-feather="user"></i>
                    Edit Profile
                </a>
            </li>
            {{-- <li>
                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dropdown-item d-flex align-items-center"
                        onclick="return confirm('Are you sure you want to delete your account?');">
                        <i class="me-2 icon-xxs dropdown-item-icon" data-feather="trash-2"></i>
                        Delete Profile
                    </button>
                </form>
            </li> --}}

            {{-- <li>
                <a class="dropdown-item d-flex align-items-center" href="">
                    <i class="me-2 icon-xxs dropdown-item-icon" data-feather="settings"></i>
                    Settings
                </a>
            </li> --}}
            <!-- Logout -->
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item d-flex align-items-center">
                        <i class="me-2 icon-xxs dropdown-item-icon" data-feather="power"></i>
                        Sign Out
                    </button>
                </form>
            </li>
        </ul>
    </div>
</li>
<script>
    const avatarLabel = document.querySelector('label.position-relative');
    const overlay = avatarLabel.querySelector('div');

    avatarLabel.addEventListener('mouseenter', () => overlay.style.opacity = 1);
    avatarLabel.addEventListener('mouseleave', () => overlay.style.opacity = 0);

    avatarLabel.querySelector('img').addEventListener('click', () => {
        document.getElementById('avatarInput').click();
    });

    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarLabel.querySelector('img').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
