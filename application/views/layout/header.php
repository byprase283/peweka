<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="<?= get_setting('site_description', get_setting('site_about', 'Peweka - Culture & The Future. Brand clothing streetwear lokal dengan desain unik dan bahan premium.')) ?>">
    <link rel="icon" type="image/png"
        href="<?= base_url('assets/img/' . get_setting('site_favicon', 'favicon.png')) ?>">
    <link rel="shortcut icon" type="image/png"
        href="<?= base_url('assets/img/' . get_setting('site_favicon', 'favicon.png')) ?>">
    <title>
        <?= isset($title) ? $title : get_setting('site_name', 'Peweka') . ' - Culture & The Future' ?>
    </title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
            --white:
                <?= $text_color ?>
            ;
            --font-primary:
                <?= $font_heading ?>
                , sans-serif;
            --font-body:
                <?= $font_body ?>
                , sans-serif;
            --shadow-glow: 0 0 30px
                <?= $theme_color ?>
                4D;
            /* 30% opacity */
        }

        /* Apply fonts */
        body {
            font-family: var(--font-body);
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .navbar-brand,
        .hero-text h1 {
            font-family: var(--font-primary);
        }

        /* Modern Desktop Cart Styling */
        .cart-link {
            position: relative;
            display: none;
            /* Managed by JS */
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            color: var(--yellow);
            background: rgba(255, 215, 0, 0.1);
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .cart-link:hover {
            background: rgba(255, 215, 0, 0.2);
            transform: translateY(-2px);
            color: var(--yellow-light);
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.15);
        }

        .cart-link:active {
            transform: scale(0.9);
        }

        .cart-link .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--yellow);
            color: var(--black);
            font-size: 0.7rem;
            font-weight: 800;
            min-width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 2px solid var(--black);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            animation: badgeIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        /* Modern Mobile Cart Styling */
        .nav-actions {
            display: none;
            align-items: center;
            gap: 15px;
        }

        .mobile-cart-link {
            position: relative;
            color: var(--yellow);
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 215, 0, 0.1);
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .mobile-cart-link:active {
            transform: scale(0.9);
            background: rgba(255, 215, 0, 0.2);
        }

        .mobile-cart-link .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--yellow);
            color: var(--black);
            font-size: 0.7rem;
            font-weight: 800;
            min-width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 2px solid var(--black);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            animation: badgeIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes badgeIn {
            from {
                transform: scale(0);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .nav-actions {
                display: flex;
            }

            .navbar-toggle {
                margin-left: 5px;
                width: 40px;
                height: 40px;
                display: flex !important;
                align-items: center;
                justify-content: center;
                background: rgba(255, 255, 255, 0.05);
                border-radius: 12px;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <div class="container">
            <a href="<?= base_url() ?>" class="navbar-brand">
                <img src="<?= base_url('assets/img/' . get_setting('site_logo', 'logo.png')) ?>"
                    alt="<?= get_setting('site_name', 'Peweka') ?> Logo" class="brand-logo">
                <?= strtolower(get_setting('site_name', 'peweka')) ?>
            </a>

            <div class="nav-actions">
                <a href="<?= base_url('cart') ?>" class="mobile-cart-link" id="mobileCartLink" style="display: none;">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="badge" id="mobileCartBadge">0</span>
                </a>
                <button class="navbar-toggle" onclick="toggleMenu()" aria-label="Menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <ul class="navbar-menu" id="navMenu">
                <li><a href="<?= base_url() ?>">Home</a></li>
                <li><a href="<?= base_url('produk') ?>"
                        class="<?= ($this->uri->segment(1) == 'shop' || $this->uri->segment(1) == 'produk') ? 'active' : '' ?>">Belanja</a>
                </li>
                <li><a href="<?= base_url() ?>#products">Produk</a></li>
                <li><a href="<?= base_url() ?>#about">Tentang</a></li>
                <?php if ($ig = get_setting('instagram_url')): ?>
                    <li class="nav-ig-item"><a href="<?= $ig ?>" target="_blank"><i class="fab fa-instagram"></i>
                            @<?= basename($ig) ?></a></li>
                <?php endif; ?>
                <li class="nav-cart-item">
                    <a href="<?= base_url('cart') ?>" class="cart-link" id="desktopCartLink" title="Keranjang Belanja">
                        <i class="fas fa-shopping-bag"></i>
                        <span class="badge" id="cartBadge" style="display: none;">0</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>