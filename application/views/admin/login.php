<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?= get_setting('site_name', 'Peweka') ?></title>
    <link rel="icon" type="image/png"
        href="<?= base_url('assets/img/' . get_setting('site_favicon', 'favicon.png')) ?>">
    <link rel="shortcut icon" type="image/png"
        href="<?= base_url('assets/img/' . get_setting('site_favicon', 'favicon.png')) ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #0a0a0a;
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255, 215, 0, 0.08) 0%, transparent 70%);
            top: -150px;
            right: -150px;
            border-radius: 50%;
        }

        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 215, 0, 0.05) 0%, transparent 70%);
            bottom: -100px;
            left: -100px;
            border-radius: 50%;
        }

        .login-card {
            background: #141414;
            border: 1px solid rgba(255, 215, 0, 0.15);
            border-radius: 20px;
            padding: 50px 40px;
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
            box-shadow: 0 0 60px rgba(255, 215, 0, 0.05);
        }

        .login-brand {
            text-align: center;
            margin-bottom: 35px;
        }

        .login-brand .icon {
            width: 70px;
            height: 70px;
            background: #FFD700;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            font-weight: 900;
            color: #0a0a0a;
            box-shadow: 0 0 30px rgba(255, 215, 0, 0.3);
        }

        .login-brand h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            color: #FFD700;
            font-weight: 800;
        }

        .login-brand p {
            color: #737373;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            color: #a3a3a3;
            font-size: 0.85rem;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #525252;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 14px 15px 14px 45px;
            background: #1e1e1e;
            border: 2px solid #404040;
            border-radius: 10px;
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: #FFD700;
            outline: none;
        }

        .form-control::placeholder {
            color: #525252;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: #FFD700;
            color: #0a0a0a;
            border: none;
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
        }

        .btn-login:hover {
            background: #FFE44D;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 215, 0, 0.3);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }

        .back-link {
            text-align: center;
            margin-top: 25px;
        }

        .back-link a {
            color: #737373;
            font-size: 0.85rem;
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-link a:hover {
            color: #FFD700;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="login-brand">
            <div class="icon"><?= strtoupper(substr(get_setting('site_name', 'P'), 0, 1)) ?></div>
            <h1><?= strtolower(get_setting('site_name', 'peweka')) ?></h1>
            <p>Admin Panel</p>
        </div>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert"><i class="fas fa-exclamation-circle"></i>
                <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/authenticate') ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required
                        autofocus>
                </div>
            </div>
            <div class="form-group">
                <label>Password</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password"
                        required>
                </div>
            </div>
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>
        <div class="back-link">
            <a href="<?= base_url() ?>"><i class="fas fa-arrow-left"></i> Kembali ke Toko</a>
        </div>
    </div>
</body>

</html>