<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png"
        href="<?= base_url('assets/img/' . get_setting('site_favicon', 'favicon.png')) ?>">
    <title>
        <?= isset($title) ? $title : 'Admin - ' . get_setting('site_name', 'Peweka') ?>
    </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Dynamic Theme Styling -->
    <?php
    $theme_color = get_setting('theme_color', '#FFD700');
    $font_heading = get_setting('theme_font_heading', 'Outfit');
    $font_body = get_setting('theme_font_body', 'Inter');
    $theme_preset = get_setting('theme_preset', 'peweka-gold');
    $theme_bg = get_setting('theme_bg_color', '#0a0a0a');
    $theme_text = get_setting('theme_text_color', '#ffffff');

    // Preset configurations
    $bg_color = $theme_bg;
    $text_color = $theme_text;

    if ($theme_preset === 'peweka-gold') {
        $theme_color = '#FFD700';
        $bg_color = '#0a0a0a';
        $text_color = '#ffffff';
    } else if ($theme_preset === 'midnight-ocean') {
        $theme_color = '#3b82f6';
        $bg_color = '#020617';
        $text_color = '#ffffff';
    } else if ($theme_preset === 'forest-emerald') {
        $theme_color = '#22c55e';
        $bg_color = '#061a11';
        $text_color = '#ffffff';
    } else if ($theme_preset === 'rose-velvet') {
        $theme_color = '#ec4899';
        $bg_color = '#1a0610';
        $text_color = '#ffffff';
    } else if ($theme_preset === 'modern-light') {
        $theme_color = '#111827';
        $bg_color = '#f9fafb';
        $text_color = '#111827';
    }

    if (!function_exists('adjustBrightness')) {
        function adjustBrightness($hex, $steps)
        {
            $steps = max(-255, min(255, $steps));
            $hex = str_replace('#', '', $hex);
            if (strlen($hex) == 3) {
                $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
            }
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));

            $r = max(0, min(255, $r + $steps));
            $g = max(0, min(255, $g + $steps));
            $b = max(0, min(255, $b + $steps));

            return sprintf("#%02x%02x%02x", $r, $g, $b);
        }
    }

    $theme_light = adjustBrightness($theme_color, 40);
    $theme_dark = adjustBrightness($theme_color, -50);
    $bg_light = ($theme_preset === 'modern-light') ? adjustBrightness($bg_color, -10) : adjustBrightness($bg_color, 20);
    $bg_mid = ($theme_preset === 'modern-light') ? adjustBrightness($bg_color, -20) : adjustBrightness($bg_color, 40);
    ?>

    <?php if ($font_heading !== 'Outfit' || $font_body !== 'Inter'): ?>
        <link
            href="https://fonts.googleapis.com/css2?family=<?= str_replace(' ', '+', str_replace("'", "", $font_heading)) ?>:wght@400;500;600;700;800;900&family=<?= str_replace(' ', '+', str_replace("'", "", $font_body)) ?>:wght@300;400;500;600;700;800;900&display=swap"
            rel="stylesheet">
    <?php endif; ?>

    <style>
        :root {
            --yellow:
                <?= $theme_color ?>
            ;
            --yellow-light:
                <?= $theme_light ?>
            ;
            --yellow-dark:
                <?= $theme_dark ?>
            ;
            --black:
                <?= $bg_color ?>
            ;
            --black-light:
                <?= $bg_light ?>
            ;
            --black-mid:
                <?= $bg_mid ?>
            ;
            --black-card:
                <?= $bg_light ?>
            ;
            --white:
                <?= $text_color ?>
            ;
            --gray-100: #f5f5f5;
            --gray-300: #d4d4d4;
            --gray-400: #a3a3a3;
            --gray-500: #737373;
            --gray-600: #525252;
            --gray-700: #404040;
            --red: #ef4444;
            --green: #22c55e;
            --blue: #3b82f6;
            --purple: #8b5cf6;
            --font-primary:
                <?= $font_heading ?>
                , sans-serif;
            --font-body:
                <?= $font_body ?>
                , sans-serif;
        }

        body {
            font-family: var(--font-body);
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .sidebar-brand,
        .main-header h1 {
            font-family: var(--font-primary);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--black);
            color: var(--white);
            min-height: 100vh;
            display: flex;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--black-light);
            border-right: 1px solid rgba(255, 215, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            padding: 20px 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            padding: 10px 25px 30px;
            font-family: 'Outfit', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--yellow);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-brand .icon {
            width: 36px;
            height: 36px;
            background: var(--yellow);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--black);
            font-size: 1rem;
            font-weight: 900;
        }

        .sidebar-brand small {
            display: block;
            font-size: 0.65rem;
            color: var(--gray-500);
            font-weight: 400;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .sidebar-menu {
            flex: 1;
            padding: 0 15px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.1) transparent;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-menu::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb:hover {
            background: var(--yellow);
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            border-radius: 10px;
            color: var(--gray-400);
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 4px;
            transition: all 0.2s ease;
        }

        .sidebar-menu a:hover {
            background: rgba(255, 215, 0, 0.08);
            color: var(--white);
        }

        .sidebar-menu a.active {
            background: rgba(255, 215, 0, 0.15);
            color: var(--yellow);
            font-weight: 600;
        }

        .sidebar-menu a i {
            width: 20px;
            text-align: center;
        }

        .sidebar-menu .menu-label {
            font-size: 0.7rem;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 15px 15px 8px;
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 15px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-footer a {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--gray-500);
            font-size: 0.85rem;
            padding: 10px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .sidebar-footer a:hover {
            color: var(--red);
            background: rgba(239, 68, 68, 0.1);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
            min-height: 100vh;
        }

        .main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .main-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.8rem;
            font-weight: 700;
        }

        .main-header .admin-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--gray-400);
            font-size: 0.9rem;
        }

        .toggle-sidebar {
            display: none;
            background: none;
            border: none;
            color: var(--yellow);
            font-size: 1.3rem;
            cursor: pointer;
            padding: 5px 10px;
        }

        /* Cards */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--black-card);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 14px;
            padding: 22px;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            border-color: rgba(255, 215, 0, 0.2);
            transform: translateY(-2px);
        }

        .stat-card .stat-icon {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .stat-card .stat-value {
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            font-weight: 800;
        }

        .stat-card .stat-label {
            font-size: 0.8rem;
            color: var(--gray-500);
            margin-top: 3px;
        }

        /* Table */
        .table-card {
            background: var(--black-card);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 14px;
            overflow: hidden;
        }

        .table-card .card-header {
            padding: 20px 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-card .card-header h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            text-align: left;
            padding: 14px 20px;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--gray-500);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            font-weight: 600;
        }

        tbody td {
            padding: 14px 20px;
            font-size: 0.9rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            vertical-align: middle;
        }

        tbody tr:hover {
            background: rgba(255, 215, 0, 0.03);
        }

        /* Status badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-pending {
            background: rgba(255, 215, 0, 0.15);
            color: var(--yellow);
        }

        .badge-confirmed {
            background: rgba(59, 130, 246, 0.15);
            color: var(--blue);
        }

        .badge-shipped {
            background: rgba(139, 92, 246, 0.15);
            color: var(--purple);
        }

        .badge-delivered {
            background: rgba(34, 197, 94, 0.15);
            color: var(--green);
        }

        .badge-rejected {
            background: rgba(239, 68, 68, 0.15);
            color: var(--red);
        }

        .badge-active {
            background: rgba(34, 197, 94, 0.15);
            color: var(--green);
        }

        .badge-inactive {
            background: rgba(239, 68, 68, 0.15);
            color: var(--red);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: var(--yellow);
            color: var(--black);
            border-color: var(--yellow);
        }

        .btn-primary:hover {
            background: var(--yellow-light);
        }

        .btn-outline {
            background: transparent;
            color: var(--yellow);
            border-color: var(--yellow);
        }

        .btn-outline:hover {
            background: var(--yellow);
            color: var(--black);
        }

        .btn-danger {
            background: var(--red);
            color: var(--white);
        }

        .btn-danger:hover {
            opacity: 0.9;
        }

        .btn-success {
            background: var(--green);
            color: var(--white);
        }

        .btn-info {
            background: var(--blue);
            color: var(--white);
        }

        .btn-purple {
            background: var(--purple);
            color: var(--white);
        }

        .btn-sm {
            padding: 6px 14px;
            font-size: 0.8rem;
        }

        .btn-block {
            width: 100%;
            justify-content: center;
        }

        /* Forms */
        .form-card {
            background: var(--black-card);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 14px;
            padding: 30px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--gray-300);
            font-size: 0.85rem;
        }

        .form-control {
            width: 100%;
            padding: 11px 15px;
            background: var(--black-mid);
            border: 2px solid var(--gray-700);
            border-radius: 8px;
            color: var(--white);
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: var(--yellow);
            outline: none;
        }

        .form-control::placeholder {
            color: var(--gray-600);
        }

        select.form-control {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12'
 viewBox='0 0 12 12' %3E%3Cpath fill='%23FFD700' d='M6 8L1 3h10z' /%3E%3C/svg%3E");
 background-repeat: no-repeat;
                    background-position: right 12px center;
                    padding-right: 35px;
            }

            textarea.form-control {
                min-height: 100px;
                resize: vertical;
            }

            .form-row {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 15px;
            }

            .form-inline {
                display: flex;
                gap: 10px;
                align-items: end;
            }

            /* Alerts */
            .alert {
                padding: 14px 18px;
                border-radius: 10px;
                margin-bottom: 20px;
                font-size: 0.9rem;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .alert-danger {
                background: rgba(239, 68, 68, 0.12);
                border: 1px solid rgba(239, 68, 68, 0.3);
                color: #fca5a5;
            }

            .alert-success {
                background: rgba(34, 197, 94, 0.12);
                border: 1px solid rgba(34, 197, 94, 0.3);
                color: #86efac;
            }

            /* Tab filters */
            .tab-filters {
                display: flex;
                gap: 8px;
                margin-bottom: 20px;
                flex-wrap: wrap;
            }

            .tab-filters a {
                padding: 8px 18px;
                border-radius: 8px;
                font-size: 0.85rem;
                font-weight: 500;
                color: var(--gray-400);
                background: var(--black-mid);
                border: 1px solid rgba(255, 255, 255, 0.06);
                transition: all 0.2s;
            }

            .tab-filters a:hover,
            .tab-filters a.active {
                background: rgba(255, 215, 0, 0.15);
                color: var(--yellow);
                border-color: rgba(255, 215, 0, 0.3);
            }

            /* Detail card */
            .detail-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 25px;
            }

            .detail-section {
                background: var(--black-mid);
                border-radius: 12px;
                padding: 20px;
                border: 1px solid rgba(255, 255, 255, 0.04);
            }

            .detail-section h4 {
                font-family: 'Outfit', sans-serif;
                color: var(--yellow);
                margin-bottom: 15px;
                font-size: 1rem;
            }

            .detail-row {
                display: flex;
                justify-content: space-between;
                padding: 8px 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.03);
                font-size: 0.9rem;
            }

            .detail-row .dl {
                color: var(--gray-500);
            }

            .detail-row .dv {
                font-weight: 600;
                text-align: right;
                max-width: 60%;
            }

            .payment-img {
                max-width: 100%;
                border-radius: 10px;
                border: 2px solid var(--gray-700);
                margin-top: 10px;
            }

            /* Variant row in product form */
            .variant-row {
                display: grid;
                grid-template-columns: 1fr 1fr 100px 100px 40px;
                gap: 8px;
                align-items: end;
                margin-bottom: 8px;
            }

            .variant-row .form-group {
                margin-bottom: 0;
            }

            .remove-variant {
                width: 36px;
                height: 36px;
                background: rgba(239, 68, 68, 0.15);
                border: none;
                border-radius: 8px;
                color: var(--red);
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s;
            }

            .remove-variant:hover {
                background: var(--red);
                color: var(--white);
            }


            /* Responsive */
            @media (max-width: 768px) {
                .sidebar {
                    transform: translateX(-100%);
                }

                .sidebar.active {
                    transform: translateX(0);
                }

                .main-content {
                    margin-left: 0;
                    padding: 15px;
                }

                .toggle-sidebar {
                    display: block;
                }

                .stat-grid {
                    grid-template-columns: 1fr 1fr;
                }

                .detail-grid {
                    grid-template-columns: 1fr;
                }

                .form-row {
                    grid-template-columns: 1fr;
                }

                .variant-row {
                    grid-template-columns: 1fr 1fr;
                }

                table {
                    font-size: 0.8rem;
                }

                thead th,
                tbody td {
                    padding: 10px 12px;
                }
            }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="<?= base_url('assets/img/' . get_setting('site_logo', 'logo.png')) ?>" alt="Logo"
                style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; background: #fff;">
            <div><?= strtolower(get_setting('site_name', 'peweka')) ?><small>Admin Panel</small></div>
        </div>
        <nav class="sidebar-menu">
            <div class="menu-label">Menu</div>
            <a href="<?= base_url('admin') ?>" class="<?= $page == 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>
            <a href="<?= base_url('admin/orders') ?>"
                class="<?= in_array($page, ['orders', 'order_detail']) ? 'active' : '' ?>">
                <i class="fas fa-shopping-bag"></i> Pesanan
            </a>
            <div class="menu-label">Kelola</div>
            <a href="<?= base_url('admin/categories') ?>"
                class="<?= in_array($page, ['categories', 'category_form']) ? 'active' : '' ?>">
                <i class="fas fa-tags"></i> Kategori
            </a>
            <a href="<?= base_url('admin/products') ?>"
                class="<?= in_array($page, ['products', 'product_form']) ? 'active' : '' ?>">
                <i class="fas fa-tshirt"></i> Produk
            </a>
            <a href="<?= base_url('admin/vouchers') ?>"
                class="<?= in_array($page, ['vouchers', 'voucher_form']) ? 'active' : '' ?>">
                <i class="fas fa-ticket-alt"></i> Voucher
            </a>
            <a href="<?= base_url('admin/stores') ?>"
                class="<?= in_array($page, ['stores', 'store_form']) ? 'active' : '' ?>">
                <i class="fas fa-store"></i> Toko
            </a>
            <div class="menu-label">Lainnya</div>
            <a href="<?= base_url('admin/settings') ?>" class="<?= $page == 'settings' ? 'active' : '' ?>">
                <i class="fas fa-cog"></i> Pengaturan
            </a>
            <a href="<?= base_url('admin/change_password') ?>"
                class="<?= $page == 'change_password' ? 'active' : '' ?>">
                <i class="fas fa-key"></i> Ganti Password
            </a>
            <a href="<?= base_url() ?>" target="_blank">
                <i class="fas fa-external-link-alt"></i> Lihat Toko
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="<?= base_url('admin/logout') ?>">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </aside>

    <!-- Main -->
    <div class="main-content">
        <div class="main-header">
            <div style="display:flex; align-items:center; gap:10px;">
                <button class="toggle-sidebar" onclick="document.getElementById('sidebar').classList.toggle('active')">
                    <i class="fas fa-bars"></i>
                </button>
                <h1>
                    <?= $title ?>
                </h1>
            </div>
            <div class="admin-info">
                <i class="fas fa-user-circle"></i>
                <?= $this->session->userdata('admin_name') ?>
            </div>
        </div>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i>
                <?= $this->session->flashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i>
                <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php $this->load->view('admin/' . $page); ?>
    </div>

    <!-- Final Robust Styles for Admin Pagination -->
    <style>
        .pagination-container,
        body .pagination-container,
        .main-content .pagination-container,
        #main-content .pagination-container {
            margin-top: 50px !important;
            display: flex !important;
            flex-direction: row !important;
            justify-content: space-between !important;
            align-items: center !important;
            gap: 20px !important;
            padding: 20px 30px !important;
            background: rgba(255, 255, 255, 0.04) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 20px !important;
            backdrop-filter: blur(25px) !important;
            -webkit-backdrop-filter: blur(25px) !important;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.5) !important;
            width: 100% !important;
            box-sizing: border-box !important;
            clear: both !important;
        }

        .pagination-info,
        body .pagination-info {
            color: var(--gray-400) !important;
            font-size: 0.85rem !important;
            font-weight: 700 !important;
            letter-spacing: 0.5px !important;
            text-transform: uppercase !important;
            display: block !important;
        }

        .admin-pagination,
        body .admin-pagination,
        ul.admin-pagination {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            list-style: none !important;
            padding: 0 !important;
            margin: 0 !important;
            gap: 12px !important;
        }

        .admin-pagination li,
        body .admin-pagination li {
            list-style: none !important;
            margin: 0 !important;
            padding: 0 !important;
            display: inline-block !important;
            /* Fallback for flex */
        }

        /* Extreme Bullet Removal */
        .admin-pagination li::before,
        .admin-pagination li::after,
        ul.admin-pagination li::before,
        ul.admin-pagination li::after {
            display: none !important;
            content: none !important;
        }

        .admin-pagination li a,
        .admin-pagination li span,
        body .admin-pagination li a,
        body .admin-pagination li span {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 44px !important;
            height: 44px !important;
            padding: 0 18px !important;
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 12px !important;
            color: var(--gray-300) !important;
            font-size: 0.95rem !important;
            font-weight: 700 !important;
            font-family: 'Outfit', sans-serif !important;
            text-decoration: none !important;
            transition: all 0.3s ease !important;
        }

        .admin-pagination li a:hover {
            background: rgba(255, 215, 0, 0.15) !important;
            border-color: var(--yellow) !important;
            color: var(--yellow) !important;
            transform: translateY(-3px) !important;
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.2) !important;
        }

        .admin-pagination li.active span {
            background: var(--yellow) !important;
            color: var(--black) !important;
            border-color: var(--yellow) !important;
            box-shadow: 0 8px 20px rgba(255, 215, 0, 0.4) !important;
            transform: translateY(-3px) !important;
        }

        @media (max-width: 768px) {
            .pagination-container {
                flex-direction: column !important;
                gap: 20px !important;
                padding: 25px !important;
                text-align: center !important;
            }

            .admin-pagination {
                justify-content: center !important;
            }
        }
    </style>

</body>

</html>