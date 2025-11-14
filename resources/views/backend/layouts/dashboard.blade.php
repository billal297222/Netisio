@extends('backend.master')

@section('title', 'Dashboard')

@section('content')

<style>
    .stat-card {
        border: none;
        border-radius: 18px;
        transition: 0.3s;
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
    }
    .stat-icon {
        font-size: 65px;
        position: absolute;
        right: -15px;
        top: -15px;
        opacity: 0.10;
    }
    .stat-title {
        font-size: 17px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .stat-value {
        font-size: 35px;
        font-weight: 700;
        margin-top: 8px;
    }

    .gradient-1 { background: linear-gradient(135deg,#4b79a1,#283e51); color:#fff; }
    .gradient-2 { background: linear-gradient(135deg,#11998e,#38ef7d); color:#fff; }
    .gradient-3 { background: linear-gradient(135deg,#f7971e,#ffd200); color:#fff; }
    .gradient-4 { background: linear-gradient(135deg,#00c6ff,#0072ff); color:#fff; }
    .gradient-5 { background: linear-gradient(135deg,#bdc3c7,#2c3e50); color:#fff; }
    .gradient-6 { background: linear-gradient(135deg,#ff5858,#f09819); color:#fff; }
    .gradient-7 { background: linear-gradient(135deg,#8E2DE2,#4A00E0); color:#fff; }

    .chart-box {
        height: 280px !important;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    canvas {
        max-height: 100% !important;
    }
</style>

<div class="container-fluid mt-4">

    <h3 class="fw-bold mb-4">📊 Admin Dashboard</h3>

    <div class="row g-4">

        <div class="col-md-6 col-lg-4">
            <div class="card stat-card gradient-1">
                <i class="mdi mdi-account-group stat-icon"></i>
                <div class="card-body">
                    <div class="stat-title">Total Parents</div>
                    <div class="stat-value">{{ $totalParents }}</div>
                    <small>All registered parents</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card stat-card gradient-2">
                <i class="mdi mdi-home-group stat-icon"></i>
                <div class="card-body">
                    <div class="stat-title">Total Families</div>
                    <div class="stat-value">{{ $totalFamilies }}</div>
                    <small>Registered families</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card stat-card gradient-3">
                <i class="mdi mdi-baby stat-icon"></i>
                <div class="card-body">
                    <div class="stat-title">Total Kids</div>
                    <div class="stat-value">{{ $totalKids }}</div>
                    <small>Registered kids</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card stat-card gradient-4">
                <i class="mdi mdi-clipboard-text stat-icon"></i>
                <div class="card-body">
                    <div class="stat-title">Total Tasks</div>
                    <div class="stat-value">{{ $totalTasks }}</div>
                    <small>Kids task management</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card stat-card gradient-5">
                <i class="mdi mdi-piggy-bank stat-icon"></i>
                <div class="card-body">
                    <div class="stat-title">Saving Goals</div>
                    <div class="stat-value">{{ $totalGoals }}</div>
                    <small>Goals created</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card stat-card gradient-6">
                <i class="mdi mdi-cash-multiple stat-icon"></i>
                <div class="card-body">
                    <div class="stat-title">Weekly Payments</div>
                    <div class="stat-value">{{ $totalWeeklyPayments }}</div>
                    <small>Total weekly payments</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card stat-card gradient-7">
                <i class="mdi mdi-chart-line stat-icon"></i>
                <div class="card-body">
                    <div class="stat-title">Monthly Limit</div>
                    <div class="stat-value">${{ number_format($monthlyLimit, 2) }}</div>
                    <small>System monthly limit</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mt-5">

        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">📈 Weekly Payments Trend</div>
                <div class="card-body chart-box">
                    <canvas id="weeklyPaymentsChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">📊 Tasks Status Summary</div>
                <div class="card-body chart-box">
                    <canvas id="tasksStatusChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">💰 Savings Goals Status</div>
                <div class="card-body chart-box">
                    <canvas id="savingGoalsChart"></canvas>
                </div>
            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    new Chart(document.getElementById('weeklyPaymentsChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($weeklyPaymentsChartData['labels']) !!},
            datasets: [{
                label: "Weekly Payments",
                data: {!! json_encode($weeklyPaymentsChartData['data']) !!},
                borderWidth: 3,
                fill: true,
            }]
        }
    });

    new Chart(document.getElementById('tasksStatusChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($tasksStatusData['labels']) !!},
            datasets: [{
                data: {!! json_encode($tasksStatusData['data']) !!},
                backgroundColor: ['#f39c12','#3498db','#2ecc71','#9b59b6'],
                borderWidth: 2
            }]
        },
        options: {
        plugins: {
            legend: {
                position: 'bottom',
                align: 'center',
                labels: {
                    boxWidth: 15,
                    padding: 10
                }
            }
        }
    }
    });

    // Saving Goals Doughnut Chart
    new Chart(document.getElementById('savingGoalsChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($savingGoalsStatusData['labels']) !!},
            datasets: [{
                data: {!! json_encode($savingGoalsStatusData['data']) !!},
                backgroundColor: ['#f1c40f','#2ecc71'],
                borderWidth: 2
            }]
        },

        options: {
        plugins: {
            legend: {
                position: 'bottom',
                align: 'center',
                labels: {
                    boxWidth: 15,
                    padding: 10
                }
            }
        }
    }
    });
</script>

@endsection
