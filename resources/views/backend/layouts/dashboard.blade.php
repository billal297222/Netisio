@extends('backend.master')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">

    <!-- Greeting & Info Cards Row (Row 1) -->
    <div class="row g-3">
        <!-- Welcome Card -->
        <div class="col-md-6">
            <div class="card h-100 shadow-sm text-center">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <h3 class="fw-bold">👋 Hey {{ Auth::user()->name ?? 'Guest' }}!</h3>
                    <p class="mb-2" id="greetingText">🌞 Have a wonderful day ahead!</p>
                </div>
            </div>
        </div>

        <!-- Date & Time Card -->
        <div class="col-md-6">
            <div class="card h-100 shadow-sm text-center">
                <div class="card-body d-flex flex-column justify-content-center align-items-center position-relative">
                    <i class="mdi mdi-calendar icon position-absolute top-0 end-0 fs-1 text-muted" style="opacity: 0.15;"></i>
                    <h5 class="card-title">Date & Time</h5>
                    <h2 id="currentTime" class="mb-1"></h2>
                    <p id="currentDate"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards Row (Row 2) -->
    <div class="row g-3 mt-3">

        <!-- Total PDFs -->
        <div class="col-md-6">
            <div class="card h-100 shadow-sm text-center">
                <div class="card-body position-relative">
                    <i class="mdi mdi-file-pdf-box icon position-absolute top-0 end-0 fs-1 text-danger" style="opacity: 0.15;"></i>
                    <h5 class="card-title">Total PDFs</h5>
                    <h2>{{ $totalPdfs ?? 0 }}</h2>
                    <p>All uploaded PDF files</p>
                </div>
            </div>
        </div>
        
        <!-- Motivational Card -->
        <div class="col-md-6">
            <div class="card h-100 shadow-sm text-center">
                <div class="card-body d-flex flex-column justify-content-center align-items-center position-relative">
                    <i class="mdi mdi-lightbulb-on-outline icon position-absolute top-0 end-0 fs-1 text-warning" style="opacity: 0.15;"></i>
                    <h5 class="card-title">Tip of the Day</h5>
                    <p class="mb-0">Manage your PDFs efficiently and boost your productivity every day! 💡</p>
                </div>
            </div>
        </div>


    </div>
</div>

<script>
    // Greeting logic based on time
    function updateGreeting() {
        const hour = new Date().getHours();
        let greeting = "🌞 Have a wonderful day ahead!";
        if(hour >= 5 && hour < 12) greeting = "☀️ Good Morning! Wishing you a productive day!";
        else if(hour >= 12 && hour < 15) greeting = "🌤️ Good Noon! Keep up the great work!";
        else if(hour >= 15 && hour < 17) greeting = "🌤️ Good Afternoon! Keep up the great work!";
        else if(hour >= 17 && hour < 19) greeting = "🌙 Good Evening! Hope you had a great day!";
        else greeting = "🌙 Good Night! Relax and recharge!";
        document.getElementById("greetingText").innerText = greeting;
    }

    // Current Time & Date
    function updateTime() {
        const now = new Date();
        document.getElementById("currentTime").innerText = now.toLocaleTimeString([], { hour:'2-digit', minute:'2-digit', second:'2-digit' });
        document.getElementById("currentDate").innerText = now.toLocaleDateString([], { weekday: 'long', year:'numeric', month:'short', day:'numeric' });
    }

    updateGreeting();
    updateTime();
    setInterval(updateTime, 1000);
</script>
@endsection
