<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container section" style="min-height: 60vh; display: flex; align-items: center; justify-content: center;">
    <div class="login-card" style="background: white; padding: 3rem; border-radius: var(--radius); box-shadow: var(--shadow-md); width: 100%; max-width: 450px;">
        <div class="text-center" style="text-align: center; margin-bottom: 2rem;">
            <h1 style="font-family: var(--font-heading); font-size: 2.5rem; margin-bottom: 0.5rem;">Bienvenido</h1>
        </div>

        <form action="<?= base_url('auth/login') ?>" method="post">
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nombre de Usuario</label>
                <input type="text" name="username" required placeholder="Tu usuario" style="width: 100%; padding: 0.8rem 1rem; border: 1px solid #ddd; border-radius: 8px; outline: none;">
            </div>
            
            <div class="form-group" style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Contraseña</label>
                <input type="password" name="password" required placeholder="********" style="width: 100%; padding: 0.8rem 1rem; border: 1px solid #ddd; border-radius: 8px; outline: none;">
            </div>

            <button type="submit" class="btn-cta" style="width: 100%; border: none; cursor: pointer;">Iniciar Sesión</button>
        </form>

        <div class="text-center" style="text-align: center; margin-top: 1.5rem;">
            <p class="text-muted">¿No tienes cuenta? <a href="<?= base_url('register') ?>" style="color: var(--primary); font-weight: 600;">Regístrate</a></p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
