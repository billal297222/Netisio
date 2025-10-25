<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-M8S4MT3EYG"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-M8S4MT3EYG');
</script>

<script src="{{asset('backend')}}/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('backend')}}/assets/libs/feather-icons/dist/feather.min.js"></script>
<script src="{{asset('backend')}}/assets/libs/simplebar/dist/simplebar.min.js"></script>

<!-- Theme JS -->
<script src="{{asset('backend')}}/assets/js/theme.min.js"></script>

<!-- jsvectormap -->
<script src="{{asset('backend')}}/assets/libs/jsvectormap/dist/js/jsvectormap.min.js"></script>
<script src="{{asset('backend')}}/assets/libs/jsvectormap/dist/maps/world.js"></script>
<script src="{{asset('backend')}}/assets/libs/apexcharts/dist/apexcharts.min.js"></script>
<script src="{{asset('backend')}}/assets/js/vendors/chart.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    @endif

    @if(session('error'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: "{{ session('error') }}",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    @endif
</script>

{{-- PDF.js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.239/pdf.min.js"></script>
<script>
    // worker অবশ্যই set করতে হবে
    pdfjsLib.GlobalWorkerOptions.workerSrc ="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.239/pdf.worker.min.js";

</script>
<script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const eyeIcon = document.getElementById("eyeIcon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("mdi-eye-off");
                eyeIcon.classList.add("mdi-eye");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("mdi-eye");
                eyeIcon.classList.add("mdi-eye-off");
            }
        }
    </script>

