<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container section">
    <div class="profile-header" style="display: flex; align-items: center; gap: 2rem; margin-bottom: 3rem; background: white; padding: 2rem; border-radius: var(--radius); box-shadow: var(--shadow-sm);">
        <img src="<?= $user['avatar'] ?>" alt="Profile" style="width: 100px; height: 100px; border-radius: 50%;">
        <div>
            <h1 style="margin-bottom: 0.5rem;"><?= $user['name'] ?></h1>
            <p class="text-muted">@<?= $user['email'] ?></p>
        </div>
    </div>

    <div class="profile-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
        
        <!-- Order History -->
        <div class="card" style="background: white; padding: 2rem; border-radius: var(--radius); box-shadow: var(--shadow-sm);">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3><i class="fas fa-history" style="color: var(--primary); margin-right: 0.5rem;"></i> Historial de Pedidos</h3>
                <a href="#" style="color: var(--primary); font-size: 0.9rem;">Ver Todos</a>
            </div>
            
            <div class="orders-list">
                <?php if(empty($orders)): ?>
                    <div style="text-align: center; padding: 2rem;">
                        <i class="fas fa-shopping-basket" style="font-size: 3rem; color: #eee; margin-bottom: 1rem;"></i>
                        <p class="text-muted">Aún no has realizado pedidos.</p>
                        <a href="<?= base_url('menu') ?>" style="color: var(--primary); font-weight: 600;">¡Pide tu primera pizza!</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                    <div class="order-item" style="border-bottom: 1px solid #eee; padding-bottom: 1rem; margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; font-weight: 600; margin-bottom: 0.5rem; align-items: center;">
                            <span><?= $order['id'] ?></span>
                            <div>
                                <?php if (strtolower($order['status']) !== 'pending'): ?>
                                    <span class="status-delivered" style="color: var(--success); margin-right: 1rem;"><?= $order['status'] ?></span>
                                <?php endif; ?>
                                <a href="<?= $order['link_invoice'] ?>" style="font-size: 0.8rem; background: var(--primary); padding: 0.3rem 0.8rem; border-radius: 20px; color: white; text-decoration: none;">
                                    Factura
                                </a>
                            </div>
                        </div>
                        <p class="text-muted" style="font-size: 0.9rem; margin-bottom: 0.5rem;"><?= $order['date'] ?> • $<?= number_format($order['total'], 2) ?></p>
                        <p style="font-size: 0.9rem;"><?= $order['items'] ?></p>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Addresses -->
        <div class="card" style="background: white; padding: 2rem; border-radius: var(--radius); box-shadow: var(--shadow-sm);">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3><i class="fas fa-map-marker-alt" style="color: var(--primary); margin-right: 0.5rem;"></i> Direcciones Guardadas</h3>
                <button onclick="toggleAddressForm()" style="border: none; background: var(--primary); color: white; width: 30px; height: 30px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">+</button>
            </div>

            <!-- New Address Form (Hidden by default) -->
            <div id="add-address-form" style="display: none; background: #f9f9f9; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #eee;">
                <h4 style="margin-bottom: 1rem;">Agregar Nueva Dirección</h4>
                <form action="<?= base_url('user/add_address') ?>" method="post">
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <input type="text" name="alias" placeholder="Alias (Ej: Casa, Oficina)" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <textarea name="address" placeholder="Dirección detallada" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 4px; resize: none;"></textarea>
                    </div>
                    <div style="display: flex; gap: 1rem;">
                        <button type="submit" class="btn-cta" style="padding: 0.5rem 1.5rem; font-size: 0.9rem;">Guardar</button>
                        <button type="button" onclick="toggleAddressForm()" style="background: none; border: none; color: #666; cursor: pointer;">Cancelar</button>
                    </div>
                </form>
            </div>
            
            <div class="address-list">
                <?php foreach ($addresses as $index => $addr): ?>
                <div class="address-item" style="display: flex; gap: 1rem; align-items: flex-start; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #f5f5f5; position: relative;">
                    <div class="icon-box" style="width: 40px; height: 40px; background: #FFF3E0; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                        <i class="fas fa-home" style="color: var(--primary);"></i>
                    </div>
                    <div style="flex: 1;">
                        <h4 style="margin-bottom: 0.2rem;"><?= esc($addr['alias']) ?></h4>
                        <p class="text-muted" style="font-size: 0.9rem; white-space: pre-line;"><?= esc($addr['address']) ?></p>
                    </div>
                    
                    <!-- Delete Button -->
                    <form action="<?= base_url('user/remove_address') ?>" method="post" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta dirección?')">
                        <input type="hidden" name="index" value="<?= $index ?>">
                        <button type="submit" style="background: none; border: none; color: #ff5252; cursor: pointer; padding: 0.5rem; transition: color 0.2s;" title="Eliminar dirección">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleAddressForm() {
        const form = document.getElementById('add-address-form');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
</script>

<?= $this->endSection() ?>
