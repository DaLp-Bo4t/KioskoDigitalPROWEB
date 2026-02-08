<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container section">
    <h1>Tu Carrito</h1>
    <div class="card" style="background: white; padding: 2rem; margin-top: 1rem; border-radius: var(--radius); box-shadow: var(--shadow-sm);">
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 2rem;">
            <thead>
                <tr style="border-bottom: 2px solid #eee; text-align: left;">
                    <th style="padding: 1rem;">Producto</th>
                    <th style="padding: 1rem;">Cantidad</th>
                    <th style="padding: 1rem;">Precio Unit.</th>
                    <th style="padding: 1rem;">Total</th>
                    <th style="padding: 1rem;"></th>
                </tr>
            </thead>
            <tbody id="cart-body">
                <?php if (empty($cart_items)): ?>
                    <tr><td colspan="5" style="padding: 2rem; text-align: center;">Tu carrito está vacío. <a href="<?= base_url('menu') ?>">Ir al menú</a></td></tr>
                <?php else: ?>
                    <?php foreach ($cart_items as $index => $item): ?>
                    <tr style="border-bottom: 1px solid #eee;" data-price="<?= $item['price'] ?>" id="row-<?= $index ?>">
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;"><?= $item['name'] ?></div>
                            <?php if (!empty($item['extras'])): ?>
                                <div style="font-size: 0.85rem; color: var(--text-light); margin-top: 0.3rem;">
                                    + <?= implode(', ', $item['extras']) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 1rem;">
                            <div class="qty-control" style="display: flex; align-items: center; gap: 0.5rem;">
                                <button onclick="updateQty(<?= $index ?>, -1)" style="width: 30px; height: 30px; border-radius: 50%; border: 1px solid #ddd; background: white; cursor: pointer;">-</button>
                                <span id="qty-<?= $index ?>" style="width: 30px; text-align: center; font-weight: 600;"><?= $item['qty'] ?></span>
                                <button onclick="updateQty(<?= $index ?>, 1)" style="width: 30px; height: 30px; border-radius: 50%; border: 1px solid #ddd; background: white; cursor: pointer;">+</button>
                            </div>
                        </td>
                        <td style="padding: 1rem;">$<?= number_format($item['price'], 2) ?></td>
                        <td style="padding: 1rem; font-weight: 600;" class="row-total">$<?= number_format($item['price'] * $item['qty'], 2) ?></td>
                        <td style="padding: 1rem; text-align: right;">
                            <button onclick="removeItem(<?= $index ?>)" style="background: none; border: none; color: #ff4757; cursor: pointer; font-size: 1.1rem;">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div style="text-align: right; margin-bottom: 2rem;">

            <h3 id="cart-total">Total: $<?= number_format($total, 2) ?></h3>
        </div>

        <div style="text-align: right;">
            <a href="<?= base_url('menu') ?>" style="margin-right: 1rem; font-weight: 600;">Seguir Comprando</a>
                <?php if(session()->has('user')): ?>
                    <a href="<?= base_url('order/checkout') ?>" class="btn-cta" style="display: block; text-align: center;">Pagar Ahora</a>
                <?php else: ?>
                    <a href="<?= base_url('user/login') ?>" class="btn-cta" style="display: block; text-align: center; background-color: var(--text-light);">Inicia Sesión para Pagar</a>
                <?php endif; ?>
        </div>
    </div>
</div>

<script>
    const baseUrl = '<?= base_url() ?>';

    function updateQty(index, change) {
        const row = document.getElementById('row-' + index);
        const qtySpan = document.getElementById('qty-' + index);
        const rowTotalSpan = row.querySelector('.row-total');
        const price = parseFloat(row.dataset.price);
        
        let currentQty = parseInt(qtySpan.innerText);
        let newQty = currentQty + change;

        if (newQty < 1) return; // Minimum 1

        qtySpan.innerText = newQty;
        rowTotalSpan.innerText = '$' + (price * newQty).toFixed(2);

        const formData = new FormData();
        formData.append('index', index);
        formData.append('qty', newQty);

        fetch(baseUrl + '/order/update_qty', {
            method: 'POST',
            body: formData,
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                document.getElementById('cart-total').innerText = 'Total: $' + data.total;
            }
        });
    }

    function removeItem(index) {
        if(confirm('¿Eliminar este producto?')) {
            const formData = new FormData();
            formData.append('index', index);

            fetch(baseUrl + '/order/remove', {
                method: 'POST',
                body: formData,
                headers: {'X-Requested-With': 'XMLHttpRequest'}
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    location.reload(); 
                }
            });
        }
    }
</script>

<?= $this->endSection() ?>
