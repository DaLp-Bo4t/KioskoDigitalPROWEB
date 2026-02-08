<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forno d'Oro - Pizza Artesanal</title>
    <!-- Google Fonts for Premium Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Main CSS -->
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
    <header class="main-header">
        <div class="container header-container">
            <a href="<?= base_url() ?>" class="logo">
                <i class="fas fa-pizza-slice"></i> Forno <span>d'Oro</span>
            </a>
            <nav class="main-nav">
                <ul class="nav-list">
                    <li><a href="<?= base_url() ?>" class="<?= service('router')->methodName() == 'index' && service('router')->controllerName() == '\App\Controllers\Home' ? 'active' : '' ?>">Home</a></li>
                    <li><a href="<?= base_url('menu') ?>">Menú</a></li>
                    <li><a href="<?= base_url('/#reviews') ?>">Reseñas</a></li>
                    <li><a href="https://wa.me/584242150422" target="_blank">Contacto</a></li>
                </ul>
            </nav>
            <div class="header-actions">
                <a href="<?= base_url('order') ?>" class="cart-btn" title="Ver Carrito">
                    <i class="fas fa-shopping-bag"></i> <span class="cart-count"><?= session('cart') ? count(session('cart')) : 0 ?></span>
                </a>
                
                <?php if(session()->has('user')): ?>
                    <a href="<?= base_url('profile') ?>" class="login-btn" title="Mi Perfil">
                        <img src="<?= session('user')['avatar'] ?>" alt="Avatar" style="width: 20px; height: 20px; border-radius: 50%; vertical-align: middle; margin-right: 5px;">
                        <span><?= explode(' ', session('user')['name'])[0] ?></span>
                    </a>
                    <a href="<?= base_url('logout') ?>" class="logout-btn" title="Cerrar Sesión" style="margin-left: 10px; color: var(--text-light);">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>" class="login-btn">Acceder</a>
                    <a href="<?= base_url('register') ?>" class="btn-cta" style="padding: 0.5rem 1rem; font-size: 0.9rem; margin-left: 10px;">Registrarse</a>
                <?php endif; ?>
            </div>
            <button class="mobile-menu-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    <main class="main-content">
        <?= $this->renderSection('content') ?>
    </main>

    <footer class="main-footer">
        <div class="container footer-grid">
            <div class="footer-col">
                <h3>Forno <span>d'Oro</span></h3>
                <p>La mejor pizza de la ciudad, entregada caliente y rápido en tu puerta.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h4>Enlaces Rápidos</h4>
                <ul>
                    <li><a href="<?= base_url('menu') ?>">Menú</a></li>
                    <li><a href="<?= base_url('tracking') ?>">Rastrea tu Orden</a></li>
                    <li><a href="<?= base_url('profile') ?>">Mi Cuenta</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contacto</h4>
                <p><i class="fas fa-phone-alt"></i> +58 424-2150422</p>
                <p><i class="fas fa-envelope"></i> hola@fornodoro.com</p>
                <a href="https://wa.me/584242150422" target="_blank" class="whatsapp-btn-footer">
                    <i class="fab fa-whatsapp"></i> Chat Soporte
                </a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> PARDA. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="<?= base_url('js/main.js') ?>"></script>
</body>
</html>
