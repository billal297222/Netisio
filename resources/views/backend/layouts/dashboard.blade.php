@extends('backend.master')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid mt-4">
   <div class="row g-3 mt-3">

    <!-- Total Parents -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm text-center">
            <div class="card-body position-relative">
                <i class="mdi mdi-account-multiple icon position-absolute top-0 end-0 fs-1 text-primary" style="opacity: 0.15;"></i>
                <h5 class="card-title">Total Parents</h5>
                <h2>{{ $totalParents ?? 0 }}</h2>
                <p>All registered parents</p>
            </div>
        </div>
    </div>

    <!-- Total Families -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm text-center">
            <div class="card-body position-relative">
                <i class="mdi mdi-home-group icon position-absolute top-0 end-0 fs-1 text-success" style="opacity: 0.15;"></i>
                <h5 class="card-title">Total Families</h5>
                <h2>{{ $totalFamilies ?? 0 }}</h2>
                <p>All registered families</p>
            </div>
        </div>
    </div>

    <!-- Total Kids -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm text-center">
            <div class="card-body position-relative">
                <i class="mdi mdi-baby icon position-absolute top-0 end-0 fs-1 text-warning" style="opacity: 0.15;"></i>
                <h5 class="card-title">Total Kids</h5>
                <h2>{{ $totalKids ?? 0 }}</h2>
                <p>All registered kids</p>
            </div>
        </div>
    </div>

    <!-- Total Tasks -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm text-center">
            <div class="card-body position-relative">
                <i class="mdi mdi-clipboard-text icon position-absolute top-0 end-0 fs-1 text-info" style="opacity: 0.15;"></i>
                <h5 class="card-title">Total Tasks</h5>
                <h2>{{ $totalTasks ?? 0 }}</h2>
                <p>All tasks assigned to kids</p>
            </div>
        </div>
    </div>

    <!-- Total Saving Goals -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm text-center">
            <div class="card-body position-relative">
                <i class="mdi mdi-piggy-bank icon position-absolute top-0 end-0 fs-1 text-secondary" style="opacity: 0.15;"></i>
                <h5 class="card-title">Saving Goals</h5>
                <h2>{{ $totalGoals ?? 0 }}</h2>
                <p>All saving goals of kids</p>
            </div>
        </div>
    </div>

    <!-- Total Weekly Payments -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm text-center">
            <div class="card-body position-relative">
                <i class="mdi mdi-cash-multiple icon position-absolute top-0 end-0 fs-1 text-danger" style="opacity: 0.15;"></i>
                <h5 class="card-title">Weekly Payments</h5>
                <h2>{{ $totalWeeklyPayments ?? 0 }}</h2>
                <p>Total weekly payments</p>
            </div>
        </div>
    </div>

    <!-- Backend Monthly Limit -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm text-center">
            <div class="card-body position-relative">
                <i class="mdi mdi-chart-line icon position-absolute top-0 end-0 fs-1 text-dark" style="opacity: 0.15;"></i>
                <h5 class="card-title">Monthly Limit</h5>
                <h2>${{ number_format($monthlyLimit ?? 0, 2) }}</h2>
                <p>System monthly limit</p>
            </div>
        </div>
    </div>

</div>

</div>
@endsection
