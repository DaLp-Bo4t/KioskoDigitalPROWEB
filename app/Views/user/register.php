<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container section" style="min-height: 60vh; display: flex; align-items: center; justify-content: center;">
    <div class="login-card" style="background: white; padding: 3rem; border-radius: var(--radius); box-shadow: var(--shadow-md); width: 100%; max-width: 450px;">
        <div class="text-center" style="text-align: center; margin-bottom: 2rem;">
            <h1 style="font-family: var(--font-heading); font-size: 2.5rem; margin-bottom: 0.5rem;">Crear Cuenta</h1>
        </div>

        <form action="<?= base_url('auth/create') ?>" method="post">
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nombre Completo</label>
                <input type="text" name="name" required placeholder="Tu nombre" style="width: 100%; padding: 0.8rem 1rem; border: 1px solid #ddd; border-radius: 8px; outline: none;">
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nombre de Usuario</label>
                <input type="text" name="username" required placeholder="Nombre de usuario" style="width: 100%; padding: 0.8rem 1rem; border: 1px solid #ddd; border-radius: 8px; outline: none;">
            </div>
            
            <div class="form-group" style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Contraseña</label>
                <input type="password" name="password" required placeholder="********" style="width: 100%; padding: 0.8rem 1rem; border: 1px solid #ddd; border-radius: 8px; outline: none;">
            </div>

            <hr style="margin-bottom: 1.5rem; border: 0; border-top: 1px solid #eee;">
            <p style="margin-bottom: 1rem; font-weight: 600; color: var(--primary);">Datos de Envío</p>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Dirección de Entrega</label>
                <textarea name="address" required placeholder="EJ: Carayaca" style="width: 100%; padding: 0.8rem 1rem; border: 1px solid #ddd; border-radius: 8px; outline: none; min-height: 80px; font-family: inherit;"></textarea>
                <p style="font-size: 0.85rem; color: var(--text-light); margin-top: 0.5rem;">Debe colcoar una dirección de entrega centrica.</p>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Teléfono</label>
                <input type="tel" name="phone" required placeholder="04121234567" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)" style="width: 100%; padding: 0.8rem 1rem; border: 1px solid #ddd; border-radius: 8px; outline: none;">
            </div>

            <button type="submit" class="btn-cta" style="width: 100%; border: none; cursor: pointer;">Registrarse</button>
        </form>

        <div class="text-center" style="text-align: center; margin-top: 1.5rem;">
            <p class="text-muted">¿Ya tienes cuenta? <a href="<?= base_url('login') ?>" style="color: var(--primary); font-weight: 600;">Inicia Sesión</a></p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
