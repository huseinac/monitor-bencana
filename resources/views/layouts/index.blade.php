<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title') {{ env('APP_NAME') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

@include('layouts._sidebar')

<header class="topbar">
    <div class="topbar-title">
        <img src="{{ asset('logo2.png') }}" alt="" style="height: 40px;width: auto;">
    </div>
    <div class="topbar-right">
        <button class="btn-topbar"><i class="bi bi-bell"></i></button>
        <button class="btn-topbar"><i class="bi bi-question-circle"></i></button>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="avatar" title="Super Admin">SA
                </div>
            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                <li><h6 class="dropdown-header">Super Admin</h6></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-danger" href="{{ route('logout') }}">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>

<main class="main">
    @yield('content')
</main>

<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="mainToast" class="toast align-items-center text-white border-0" role="alert" style="border-radius:10px; min-width:260px;">
        <div class="d-flex">
            <div class="toast-body fw-semibold" id="toastMsg" style="font-size:.83rem;"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

@stack('modals')

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/locales/bootstrap-datepicker.id.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/auto-numeric.js') }}"></script>
<script src="{{ asset('js/io.js') }}"></script>
<script>
    function showToast(msg, type='success'){
        const el = document.getElementById('mainToast');
        const msgEl = document.getElementById('toastMsg');
        el.className = `toast align-items-center text-white border-0 bg-${type==='success'?'success':'danger'}`;
        msgEl.innerHTML = `<i class="bi bi-${type==='success'?'check-circle':'exclamation-triangle'}-fill me-2"></i>${msg}`;
        bootstrap.Toast.getOrCreateInstance(el,{delay:3000}).show();
    }
    function showLoadingToast(msg, type='success'){
        const el  = document.getElementById('mainToast');
        const msgEl = document.getElementById('toastMsg');
        el.className = `toast align-items-center text-white border-0 bg-warning}`;
        msgEl.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-2"></i>${msg}`;
        bootstrap.Toast.getOrCreateInstance(el,{delay:3000}).show();
    }
</script>
@stack('scripts')
</body>
</html>
