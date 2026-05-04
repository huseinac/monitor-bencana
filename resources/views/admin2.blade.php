<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Admin — Monitoring Bencana</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet"/>
    <!-- Bootstrap Datepicker -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>

    <style>
        :root {
            --primary:   #1a4b8c;
            --primary-dk:#12336a;
            --primary-lt:#e8f0fb;
            --accent:    #f0a500;
            --sidebar-w: 220px;
            --header-h:  64px;
            --text:      #1e293b;
            --muted:     #64748b;
            --border:    #e2e8f0;
            --bg:        #f4f7fc;
            --card:      #ffffff;
            --danger:    #dc3545;
            --success:   #198754;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }

        /* ── SIDEBAR ─────────────────────────────────── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--primary);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            z-index: 100;
            box-shadow: 4px 0 20px rgba(0,0,0,.18);
        }

        .sidebar-brand {
            height: var(--header-h);
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 18px;
            background: var(--primary-dk);
            text-decoration: none;
        }
        .sidebar-brand img {
            width: 34px; height: 34px;
            border-radius: 6px;
        }
        .sidebar-brand-text {
            line-height: 1.2;
        }
        .sidebar-brand-text strong {
            display: block;
            font-size: .85rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: .4px;
        }
        .sidebar-brand-text span {
            font-size: .62rem;
            color: rgba(255,255,255,.6);
            font-weight: 500;
            letter-spacing: .3px;
        }

        .nav-section {
            padding: 20px 12px 6px;
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: 1.2px;
            color: rgba(255,255,255,.4);
            text-transform: uppercase;
        }

        .sidebar-nav { flex: 1; overflow-y: auto; padding-bottom: 16px; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 4px; }

        .nav-item { padding: 2px 10px; }
        .nav-link-sb {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            color: rgba(255,255,255,.75);
            text-decoration: none;
            font-size: .82rem;
            font-weight: 500;
            transition: background .15s, color .15s;
        }
        .nav-link-sb i { font-size: 1rem; flex-shrink: 0; }
        .nav-link-sb:hover { background: rgba(255,255,255,.1); color: #fff; }
        .nav-link-sb.active { background: rgba(255,255,255,.18); color: #fff; font-weight: 600; }
        .nav-link-sb.active i { color: var(--accent); }

        /* ── HEADER ──────────────────────────────────── */
        .topbar {
            position: fixed;
            top: 0; left: var(--sidebar-w);
            right: 0;
            height: var(--header-h);
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 90;
            box-shadow: 0 2px 12px rgba(0,0,0,.12);
        }
        .topbar-title {
            color: #fff;
            font-size: .95rem;
            font-weight: 600;
            letter-spacing: .3px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .topbar-title i { color: var(--accent); font-size: 1.1rem; }
        .topbar-right { display: flex; align-items: center; gap: 12px; }
        .btn-topbar {
            width: 36px; height: 36px;
            border-radius: 8px;
            background: rgba(255,255,255,.12);
            border: none;
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
            cursor: pointer;
            transition: background .15s;
        }
        .btn-topbar:hover { background: rgba(255,255,255,.22); }
        .avatar {
            width: 36px; height: 36px;
            border-radius: 8px;
            background: var(--accent);
            color: var(--primary-dk);
            font-size: .8rem;
            font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
        }

        /* ── MAIN ────────────────────────────────────── */
        .main {
            margin-left: var(--sidebar-w);
            margin-top: var(--header-h);
            flex: 1;
            padding: 28px 28px 40px;
            min-width: 0;
        }

        /* ── PAGE HEADER ─────────────────────────────── */
        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .page-header h1 {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text);
            margin: 0;
        }
        .page-header p {
            font-size: .82rem;
            color: var(--muted);
            margin: 2px 0 0;
        }
        .breadcrumb-bar {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .75rem;
            color: var(--muted);
            margin-bottom: 4px;
        }
        .breadcrumb-bar a { color: var(--primary); text-decoration: none; }
        .breadcrumb-bar i { font-size: .65rem; }

        /* ── STAT CARDS ──────────────────────────────── */
        .stat-card {
            background: var(--card);
            border-radius: 12px;
            padding: 18px 20px;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .stat-icon {
            width: 46px; height: 46px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }
        .stat-card .value { font-size: 1.6rem; font-weight: 700; line-height: 1; }
        .stat-card .label { font-size: .75rem; color: var(--muted); margin-top: 2px; }

        /* ── CARD ────────────────────────────────────── */
        .panel {
            background: var(--card);
            border-radius: 14px;
            border: 1px solid var(--border);
            overflow: hidden;
        }
        .panel-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }
        .panel-header h5 {
            font-size: .92rem;
            font-weight: 700;
            margin: 0;
            display: flex; align-items: center; gap: 8px;
        }
        .panel-header h5 i { color: var(--primary); }
        .panel-body { padding: 20px; }

        /* ── TABLE ───────────────────────────────────── */
        .tbl-wrap { overflow-x: auto; }
        .tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: .82rem;
        }
        .tbl thead th {
            background: var(--primary-lt);
            color: var(--primary-dk);
            font-weight: 700;
            font-size: .73rem;
            letter-spacing: .5px;
            text-transform: uppercase;
            padding: 11px 14px;
            border: none;
            white-space: nowrap;
        }
        .tbl thead th:first-child { border-radius: 8px 0 0 8px; }
        .tbl thead th:last-child  { border-radius: 0 8px 8px 0; }
        .tbl tbody tr { border-bottom: 1px solid var(--border); }
        .tbl tbody tr:last-child { border-bottom: none; }
        .tbl tbody tr:hover { background: #f8faff; }
        .tbl tbody td { padding: 11px 14px; vertical-align: middle; }
        .tbl tbody td.no { color: var(--muted); font-size: .75rem; }

        .badge-akses {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 600;
        }
        .badge-superadmin { background: #fef3c7; color: #92400e; }
        .badge-admin      { background: #dbeafe; color: #1e40af; }
        .badge-operator   { background: #dcfce7; color: #166534; }

        .btn-act {
            width: 30px; height: 30px;
            border-radius: 7px;
            border: none;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: .85rem;
            cursor: pointer;
            transition: background .12s;
        }
        .btn-act.edit   { background: #e0f2fe; color: #0369a1; }
        .btn-act.edit:hover { background: #bae6fd; }
        .btn-act.del    { background: #fee2e2; color: #dc2626; }
        .btn-act.del:hover  { background: #fecaca; }

        /* search box */
        .search-box {
            position: relative;
        }
        .search-box i {
            position: absolute; left: 10px; top: 50%;
            transform: translateY(-50%);
            color: var(--muted); font-size: .85rem;
            pointer-events: none;
        }
        .search-box input {
            padding-left: 32px;
            font-size: .82rem;
            border-radius: 8px;
            border: 1px solid var(--border);
            height: 36px;
            width: 220px;
        }
        .search-box input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(26,75,140,.1);
        }

        /* ── FORM ────────────────────────────────────── */
        .form-label {
            font-size: .8rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 5px;
        }
        .form-label .req { color: var(--danger); margin-left: 2px; }
        .form-control, .form-select {
            font-size: .83rem;
            border-radius: 8px;
            border: 1px solid var(--border);
            color: var(--text);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26,75,140,.12);
        }
        .form-text { font-size: .73rem; color: var(--muted); }
        textarea.form-control { resize: vertical; min-height: 90px; }

        /* password eye */
        .input-pw { position: relative; }
        .input-pw input { padding-right: 38px; }
        .pw-toggle {
            position: absolute; right: 10px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: var(--muted); font-size: .9rem; cursor: pointer;
            padding: 0;
        }
        .pw-toggle:focus { outline: none; }

        /* Select2 override */
        .select2-container--bootstrap-5 .select2-selection {
            border-radius: 8px !important;
            font-size: .83rem !important;
        }

        /* Datepicker override */
        .datepicker { font-size: .82rem; }

        /* btn primary */
        .btn-primary-custom {
            background: var(--primary);
            border: none;
            color: #fff;
            font-weight: 600;
            font-size: .83rem;
            padding: 9px 20px;
            border-radius: 9px;
            display: inline-flex; align-items: center; gap: 6px;
            transition: background .15s;
        }
        .btn-primary-custom:hover { background: var(--primary-dk); color: #fff; }
        .btn-secondary-custom {
            background: var(--bg);
            border: 1px solid var(--border);
            color: var(--text);
            font-weight: 600;
            font-size: .83rem;
            padding: 9px 20px;
            border-radius: 9px;
            display: inline-flex; align-items: center; gap: 6px;
            transition: background .15s;
        }
        .btn-secondary-custom:hover { background: #e4e9f0; }

        /* modal */
        .modal-content { border-radius: 14px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,.18); }
        .modal-header {
            background: var(--primary);
            border-radius: 14px 14px 0 0;
            padding: 16px 22px;
            border: none;
        }
        .modal-title { color: #fff; font-size: .95rem; font-weight: 700; }
        .modal-header .btn-close { filter: invert(1); opacity: .8; }
        .modal-body { padding: 24px 22px; }
        .modal-footer { border-top: 1px solid var(--border); padding: 14px 22px; }

        /* divider */
        .form-divider {
            display: flex; align-items: center; gap: 10px;
            margin: 20px 0 16px;
            color: var(--muted); font-size: .72rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .8px;
        }
        .form-divider::before, .form-divider::after {
            content: ''; flex: 1; height: 1px; background: var(--border);
        }

        /* toast */
        .toast-container { z-index: 9999; }

        /* pagination */
        .page-link {
            font-size: .8rem; border-radius: 7px !important;
            margin: 0 2px; border-color: var(--border); color: var(--primary);
        }
        .page-item.active .page-link { background: var(--primary); border-color: var(--primary); }

        /* confirm modal */
        .confirm-icon {
            width: 60px; height: 60px; border-radius: 50%;
            background: #fee2e2; color: #dc2626;
            font-size: 1.6rem;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
        }
    </style>
</head>
<body>

<!-- ════════ SIDEBAR ════════ -->
<aside class="sidebar">
    <a class="sidebar-brand" href="#">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9f/BNPB_Logo.png/240px-BNPB_Logo.png" alt="Logo"/>
        <div class="sidebar-brand-text">
            <strong>Monitoring Bencana</strong>
            <span>ACEH · SUMUT · SUMBAR</span>
        </div>
    </a>

    <nav class="sidebar-nav">
        <div class="nav-section">Dashboard</div>
        <div class="nav-item">
            <a href="#" class="nav-link-sb"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
        </div>
        <div class="nav-item">
            <a href="#" class="nav-link-sb"><i class="bi bi-map-fill"></i> Wilayah</a>
        </div>
        <div class="nav-item">
            <a href="#" class="nav-link-sb"><i class="bi bi-bar-chart-fill"></i> Indikator</a>
        </div>

        <div class="nav-section">Pelaksana</div>
        <div class="nav-item">
            <a href="#" class="nav-link-sb"><i class="bi bi-building-fill"></i> K/L Pelaksana</a>
        </div>
        <div class="nav-item">
            <a href="#" class="nav-link-sb"><i class="bi bi-briefcase-fill"></i> Paket Pekerjaan</a>
        </div>
        <div class="nav-item">
            <a href="#" class="nav-link-sb"><i class="bi bi-award-fill"></i> TKD</a>
        </div>

        <div class="nav-section">Master Data</div>
        <div class="nav-item">
            <a href="#" class="nav-link-sb active"><i class="bi bi-people-fill"></i> Pengguna</a>
        </div>
        <div class="nav-item">
            <a href="#" class="nav-link-sb"><i class="bi bi-shield-lock-fill"></i> Hak Akses</a>
        </div>
        <div class="nav-item">
            <a href="#" class="nav-link-sb"><i class="bi bi-gear-fill"></i> Pengaturan</a>
        </div>
    </nav>
</aside>

<!-- ════════ TOPBAR ════════ -->
<header class="topbar">
    <div class="topbar-title">
        <i class="bi bi-people-fill"></i>
        Manajemen Pengguna
    </div>
    <div class="topbar-right">
        <button class="btn-topbar"><i class="bi bi-bell"></i></button>
        <button class="btn-topbar"><i class="bi bi-question-circle"></i></button>
        <div class="avatar" title="Super Admin">SA</div>
    </div>
</header>

<!-- ════════ MAIN ════════ -->
<main class="main">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <div class="breadcrumb-bar">
                <a href="#">Dashboard</a>
                <i class="bi bi-chevron-right"></i>
                <span>Pengguna</span>
            </div>
            <h1>Daftar Pengguna</h1>
            <p>Kelola akun pengguna sistem Monitoring Bencana</p>
        </div>
        <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalForm">
            <i class="bi bi-plus-lg"></i> Tambah Pengguna
        </button>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#e0f2fe; color:#0369a1;"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="value">12</div>
                    <div class="label">Total Pengguna</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#fef3c7; color:#92400e;"><i class="bi bi-star-fill"></i></div>
                <div>
                    <div class="value">2</div>
                    <div class="label">Super Admin</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#dbeafe; color:#1e40af;"><i class="bi bi-shield-fill"></i></div>
                <div>
                    <div class="value">4</div>
                    <div class="label">Admin</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#dcfce7; color:#166534;"><i class="bi bi-person-fill-gear"></i></div>
                <div>
                    <div class="value">6</div>
                    <div class="label">Operator</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Panel -->
    <div class="panel">
        <div class="panel-header">
            <h5><i class="bi bi-table"></i> Data Pengguna</h5>
            <div class="d-flex align-items-center gap-2">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari nama / email…" oninput="filterTable()"/>
                </div>
                <select id="filterAkses" class="form-select form-select-sm" style="width:130px;font-size:.8rem;border-radius:8px;" onchange="filterTable()">
                    <option value="">Semua Akses</option>
                    <option>Super Admin</option>
                    <option>Admin</option>
                    <option>Operator</option>
                </select>
                <button class="btn-secondary-custom" style="padding:6px 12px;font-size:.78rem;">
                    <i class="bi bi-download"></i> Export
                </button>
            </div>
        </div>

        <div class="tbl-wrap">
            <table class="tbl" id="userTable">
                <thead>
                <tr>
                    <th style="width:44px">#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Hak Akses</th>
                    <th>Tgl. Dibuat</th>
                    <th style="width:80px; text-align:center">Aksi</th>
                </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>

        <div class="d-flex align-items-center justify-content-between px-4 py-3" style="border-top:1px solid var(--border);">
            <div style="font-size:.78rem; color:var(--muted);" id="tableInfo">Menampilkan 1–10 dari 12 data</div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a></li>
                </ul>
            </nav>
        </div>
    </div>
</main>

<!-- ════════ MODAL FORM ════════ -->
<div class="modal fade" id="modalForm" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"><i class="bi bi-person-plus-fill me-2"></i>Tambah Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">

                    <!-- Nama -->
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap <span class="req">*</span></label>
                        <input type="text" class="form-control" id="f_nama" placeholder="Masukkan nama lengkap"/>
                        <div class="form-text">Gunakan nama lengkap sesuai identitas.</div>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label class="form-label">Alamat Email <span class="req">*</span></label>
                        <input type="email" class="form-control" id="f_email" placeholder="contoh@email.com"/>
                    </div>

                    <!-- Hak Akses (Select2) -->
                    <div class="col-md-6">
                        <label class="form-label">Hak Akses <span class="req">*</span></label>
                        <select class="form-select select2" id="f_akses" style="width:100%">
                            <option value="">-- Pilih Hak Akses --</option>
                            <option value="Super Admin">Super Admin</option>
                            <option value="Admin">Admin</option>
                            <option value="Operator">Operator</option>
                        </select>
                    </div>

                    <!-- Tanggal Aktif (Datepicker) -->
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Aktif <span class="req">*</span></label>
                        <div class="input-group">
              <span class="input-group-text" style="border-radius:8px 0 0 8px; background:#f1f5f9; border-color:var(--border);">
                <i class="bi bi-calendar3" style="color:var(--primary);font-size:.85rem;"></i>
              </span>
                            <input type="text" class="form-control datepicker" id="f_tgl" placeholder="dd/mm/yyyy" readonly style="border-radius:0 8px 8px 0;"/>
                        </div>
                    </div>

                    <div class="col-12"><div class="form-divider">Keamanan</div></div>

                    <!-- Password -->
                    <div class="col-md-6">
                        <label class="form-label">Password <span class="req">*</span></label>
                        <div class="input-pw">
                            <input type="password" class="form-control" id="f_pass" placeholder="Min. 8 karakter"/>
                            <button class="pw-toggle" type="button" onclick="togglePw('f_pass', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="col-md-6">
                        <label class="form-label">Konfirmasi Password <span class="req">*</span></label>
                        <div class="input-pw">
                            <input type="password" class="form-control" id="f_pass2" placeholder="Ulangi password"/>
                            <button class="pw-toggle" type="button" onclick="togglePw('f_pass2', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-12"><div class="form-divider">Informasi Tambahan</div></div>

                    <!-- Catatan (Textarea) -->
                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control" id="f_catatan" rows="3" placeholder="Tambahkan catatan atau keterangan mengenai pengguna ini…"></textarea>
                        <div class="form-text">Opsional — maks. 500 karakter.</div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i> Batal
                </button>
                <button type="button" class="btn-primary-custom" onclick="submitForm()">
                    <i class="bi bi-check-lg"></i> <span id="btnSubmitLabel">Simpan Pengguna</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ════════ MODAL CONFIRM DELETE ════════ -->
<div class="modal fade" id="modalDelete" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:380px;">
        <div class="modal-content">
            <div class="modal-body text-center py-4 px-4">
                <div class="confirm-icon"><i class="bi bi-trash3-fill"></i></div>
                <h5 style="font-weight:700; font-size:.97rem; margin-bottom:6px;">Hapus Pengguna?</h5>
                <p style="font-size:.82rem; color:var(--muted); margin:0;">Data <strong id="deleteTarget"></strong> akan dihapus secara permanen dan tidak dapat dipulihkan.</p>
            </div>
            <div class="modal-footer justify-content-center border-0 pt-0 pb-4 gap-2">
                <button class="btn-secondary-custom" data-bs-dismiss="modal" style="padding:8px 24px;">Batal</button>
                <button class="btn-primary-custom" onclick="confirmDelete()" style="background:#dc2626;padding:8px 24px;">
                    <i class="bi bi-trash3"></i> Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="mainToast" class="toast align-items-center text-white border-0" role="alert" style="border-radius:10px; min-width:260px;">
        <div class="d-flex">
            <div class="toast-body fw-semibold" id="toastMsg" style="font-size:.83rem;"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/locales/bootstrap-datepicker.id.min.js"></script>

<script>
    // ── Sample Data ──────────────────────────────────────────────
    let users = [
        { id:1, nama:'Ahmad Fauzi',       email:'ahmad.fauzi@bnpb.go.id',   akses:'Super Admin', tgl:'01/01/2026', catatan:'Koordinator utama wilayah Aceh' },
        { id:2, nama:'Siti Rahma',        email:'siti.rahma@bnpb.go.id',    akses:'Admin',       tgl:'05/01/2026', catatan:'' },
        { id:3, nama:'Budi Santoso',      email:'budi.santoso@bnpb.go.id',  akses:'Operator',    tgl:'10/01/2026', catatan:'Operator lapangan Medan' },
        { id:4, nama:'Dewi Kusuma',       email:'dewi.k@bnpb.go.id',        akses:'Admin',       tgl:'12/01/2026', catatan:'' },
        { id:5, nama:'Rizky Pratama',     email:'rizky.p@bnpb.go.id',       akses:'Operator',    tgl:'15/01/2026', catatan:'Wilayah Padang' },
        { id:6, nama:'Rina Marlina',      email:'rina.m@bnpb.go.id',        akses:'Operator',    tgl:'18/01/2026', catatan:'' },
        { id:7, nama:'Hendra Wijaya',     email:'hendra.w@bnpb.go.id',      akses:'Admin',       tgl:'20/01/2026', catatan:'' },
        { id:8, nama:'Nurul Hidayah',     email:'nurul.h@bnpb.go.id',       akses:'Operator',    tgl:'22/01/2026', catatan:'Tim darurat Sumbar' },
        { id:9, nama:'Fajar Maulana',     email:'fajar.m@bnpb.go.id',       akses:'Admin',       tgl:'25/01/2026', catatan:'' },
        { id:10,nama:'Yanti Permatasari', email:'yanti.p@bnpb.go.id',       akses:'Operator',    tgl:'28/01/2026', catatan:'' },
        { id:11,nama:'Dimas Ardianto',    email:'dimas.a@bnpb.go.id',       akses:'Super Admin', tgl:'01/02/2026', catatan:'Backup super admin' },
        { id:12,nama:'Wulandari',         email:'wulandari@bnpb.go.id',     akses:'Operator',    tgl:'05/02/2026', catatan:'' },
    ];
    let editId = null, deleteId = null;

    // ── Init ─────────────────────────────────────────────────────
    $(function(){
        // Select2
        $('.select2').select2({ theme:'bootstrap-5', placeholder:'-- Pilih Hak Akses --', allowClear:true });

        // Datepicker
        $('.datepicker').datepicker({ format:'dd/mm/yyyy', autoclose:true, language:'id', todayHighlight:true });

        renderTable(users);

        // Reset form on modal open
        $('#modalForm').on('show.bs.modal', function(){
            if(!editId){ clearForm(); }
        });
        $('#modalForm').on('hidden.bs.modal', function(){
            editId = null;
            $('#modalTitle').html('<i class="bi bi-person-plus-fill me-2"></i>Tambah Pengguna');
            $('#btnSubmitLabel').text('Simpan Pengguna');
            clearForm();
        });
    });

    // ── Render ───────────────────────────────────────────────────
    function aksesToBadge(a){
        if(a==='Super Admin') return `<span class="badge-akses badge-superadmin"><i class="bi bi-star-fill"></i>${a}</span>`;
        if(a==='Admin')       return `<span class="badge-akses badge-admin"><i class="bi bi-shield-fill"></i>${a}</span>`;
        return `<span class="badge-akses badge-operator"><i class="bi bi-person-fill-gear"></i>${a}</span>`;
    }

    function renderTable(data){
        const tbody = document.getElementById('tableBody');
        if(!data.length){
            tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5" style="color:var(--muted);font-size:.83rem;"><i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:8px;"></i>Tidak ada data ditemukan</td></tr>`;
            return;
        }
        tbody.innerHTML = data.map((u,i)=>`
    <tr>
      <td class="no">${i+1}</td>
      <td>
        <div style="display:flex;align-items:center;gap:10px;">
          <div style="width:32px;height:32px;border-radius:8px;background:var(--primary-lt);color:var(--primary);font-weight:700;font-size:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            ${u.nama.split(' ').slice(0,2).map(n=>n[0]).join('')}
          </div>
          <div>
            <div style="font-weight:600;font-size:.83rem;">${u.nama}</div>
            ${u.catatan?`<div style="font-size:.71rem;color:var(--muted);">${u.catatan.substring(0,40)}${u.catatan.length>40?'…':''}</div>`:''}
          </div>
        </div>
      </td>
      <td style="color:var(--muted);">${u.email}</td>
      <td>${aksesToBadge(u.akses)}</td>
      <td style="color:var(--muted);font-size:.78rem;">${u.tgl}</td>
      <td style="text-align:center;">
        <button class="btn-act edit" onclick="openEdit(${u.id})" title="Edit"><i class="bi bi-pencil-fill"></i></button>
        <button class="btn-act del" onclick="openDelete(${u.id})" title="Hapus"><i class="bi bi-trash3-fill"></i></button>
      </td>
    </tr>
  `).join('');
        document.getElementById('tableInfo').textContent = `Menampilkan 1–${data.length} dari ${data.length} data`;
    }

    // ── Filter ───────────────────────────────────────────────────
    function filterTable(){
        const q  = document.getElementById('searchInput').value.toLowerCase();
        const ak = document.getElementById('filterAkses').value;
        renderTable(users.filter(u=>
            (!q  || u.nama.toLowerCase().includes(q) || u.email.toLowerCase().includes(q)) &&
            (!ak || u.akses === ak)
        ));
    }

    // ── Form helpers ─────────────────────────────────────────────
    function clearForm(){
        ['f_nama','f_email','f_pass','f_pass2','f_tgl','f_catatan'].forEach(id=>document.getElementById(id).value='');
        $('#f_akses').val('').trigger('change');
    }

    function openEdit(id){
        const u = users.find(x=>x.id===id);
        editId = id;
        document.getElementById('modalTitle').innerHTML='<i class="bi bi-pencil-fill me-2"></i>Edit Pengguna';
        document.getElementById('btnSubmitLabel').textContent = 'Simpan Perubahan';
        document.getElementById('f_nama').value    = u.nama;
        document.getElementById('f_email').value   = u.email;
        document.getElementById('f_tgl').value     = u.tgl;
        document.getElementById('f_catatan').value = u.catatan;
        $('#f_akses').val(u.akses).trigger('change');
        const m = new bootstrap.Modal(document.getElementById('modalForm'));
        m.show();
    }

    function submitForm(){
        const nama    = document.getElementById('f_nama').value.trim();
        const email   = document.getElementById('f_email').value.trim();
        const akses   = document.getElementById('f_akses').value;
        const tgl     = document.getElementById('f_tgl').value;
        const pass    = document.getElementById('f_pass').value;
        const pass2   = document.getElementById('f_pass2').value;
        const catatan = document.getElementById('f_catatan').value.trim();

        if(!nama || !email || !akses || !tgl){ showToast('Lengkapi semua field wajib!','danger'); return; }
        if(!editId && (!pass || pass.length<8)){ showToast('Password minimal 8 karakter!','danger'); return; }
        if(!editId && pass !== pass2){ showToast('Konfirmasi password tidak cocok!','danger'); return; }

        if(editId){
            const idx = users.findIndex(x=>x.id===editId);
            users[idx] = { ...users[idx], nama, email, akses, tgl, catatan };
            showToast('Data pengguna berhasil diperbarui.','success');
        } else {
            users.push({ id: Date.now(), nama, email, akses, tgl, catatan });
            showToast('Pengguna baru berhasil ditambahkan.','success');
        }

        bootstrap.Modal.getInstance(document.getElementById('modalForm')).hide();
        renderTable(users);
    }

    // ── Delete ───────────────────────────────────────────────────
    function openDelete(id){
        deleteId = id;
        const u = users.find(x=>x.id===id);
        document.getElementById('deleteTarget').textContent = u.nama;
        new bootstrap.Modal(document.getElementById('modalDelete')).show();
    }

    function confirmDelete(){
        users = users.filter(x=>x.id!==deleteId);
        bootstrap.Modal.getInstance(document.getElementById('modalDelete')).hide();
        renderTable(users);
        showToast('Pengguna berhasil dihapus.','success');
    }

    // ── Toast ────────────────────────────────────────────────────
    function showToast(msg, type='success'){
        const el  = document.getElementById('mainToast');
        const msgEl = document.getElementById('toastMsg');
        el.className = `toast align-items-center text-white border-0 bg-${type==='success'?'success':'danger'}`;
        msgEl.innerHTML = `<i class="bi bi-${type==='success'?'check-circle':'exclamation-triangle'}-fill me-2"></i>${msg}`;
        bootstrap.Toast.getOrCreateInstance(el,{delay:3000}).show();
    }

    // ── Password toggle ──────────────────────────────────────────
    function togglePw(id, btn){
        const inp = document.getElementById(id);
        const show = inp.type === 'password';
        inp.type = show ? 'text' : 'password';
        btn.querySelector('i').className = `bi bi-eye${show?'-slash':''}`;
    }
</script>
</body>
</html>
