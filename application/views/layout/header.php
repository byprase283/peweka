<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="<?= get_setting('site_about', 'Peweka - Culture & The Future. Brand clothing streetwear lokal dengan desain unik dan bahan premium.') ?>">
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

    // Simple PHP function to lighten/darken hex color
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

    $theme_light = adjustBrightness($theme_color, 40);
    $theme_dark = adjustBrightness($theme_color, -50);
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

        /* Cart Badge Styles */
        .cart-link {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            color: white;
            transition: color 0.3s ease;
        }
        .cart-link:hover {
            color: var(--yellow);
        }
        .cart-link .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--yellow);
            color: var(--black);
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 50%;
            min-width: 18px;
            text-align: center;
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
            <button class="navbar-toggle" onclick="toggleMenu()" aria-label="Menu">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="navbar-menu" id="navMenu">
                <li><a href="<?= base_url() ?>">Home</a></li>
                <li><a href="<?= base_url('shop') ?>"
                        class="<?= $this->uri->segment(1) == 'shop' ? 'active' : '' ?>">Belanja</a></li>
                <li><a href="<?= base_url() ?>#products">Produk</a></li>
                <li><a href="<?= base_url() ?>#about">Tentang</a></li>
                <?php if ($ig = get_setting('instagram_url')): ?>
                    <li class="nav-ig-item"><a href="<?= $ig ?>" target="_blank"><i class="fab fa-instagram"></i>
                            @<?= basename($ig) ?></a></li>
                <?php endif; ?>
                <li class="nav-cart-item">
                    <a href="<?= base_url('cart') ?>" class="cart-link" title="Keranjang Belanja">
                        <i class="fas fa-shopping-bag"></i>
                        <span class="badge" id="cartBadge" style="display: none;">0</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>