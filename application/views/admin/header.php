<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Admin Panel'; ?> - RT 9 Sambiroto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/admin.css'); ?>">
    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
            --success: #00b884;
            --danger: #ff6b6b;
            --warning: #ffa502;
            --info: #2196F3;
            --light: #f5f7fa;
            --dark: #2c3e50;
        }
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: var(--light);
        }
        .navbar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 20px;
        }
        .main-wrapper {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 260px;
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            overflow-y: auto;
            position: fixed;
            height: 100vh;
            top: 0;
            left: 0;
            z-index: 999;
        }
        main {
            margin-left: 260px;
            flex: 1;
            padding: 30px;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }
            main {
                margin-left: 0;
                padding: 15px;
            }
        }
        .navbar-nav .nav-link {
            color: rgba(255,255,255,0.9);
            margin: 0 10px;
        }
        .navbar-nav .nav-link:hover {
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <button class="btn btn-link text-white d-lg-none" id="toggle-sidebar">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand" href="<?php echo site_url('admin/dashboard'); ?>">
                <i class="fas fa-home"></i> RT 9 Sambiroto
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> <?php echo $this->session->userdata('nama_lengkap'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Pengaturan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo site_url('auth/logout'); ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-wrapper">
