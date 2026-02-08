<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container section">
    <div class="product-detail-wrapper" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 4rem;">
        
        <!-- Image Section -->
        <div class="product-visuals">
            <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>" style="width: 100%; border-radius: var(--radius); box-shadow: var(--shadow-md);">
        </div>

        <!-- Info & Config Section -->
        <div class="product-config">
            <form action="<?= base_url('order/add') ?>" method="post" id="addToCartForm">
                <h1 style="font-family: var(--font-heading); font-size: 3rem; margin-bottom: 0.5rem;"><?= $product['name'] ?></h1>
                
                <!-- Display Price -->
                <p class="price" id="displayPrice" style="font-size: 2rem; color: var(--primary); font-weight: 700; margin-bottom: 1rem;">$<?= number_format($product['price'], 2) ?></p>
                
                <div class="product-specs" style="display: flex; gap: 2rem; margin-bottom: 1.5rem; color: var(--text-light); font-size: 0.9rem;">
                    <?php if(isset($product['weight'])): ?>
                    <span><i class="fas fa-weight-hanging"></i> <?= $product['weight'] ?></span>
                    <?php endif; ?>
                    <?php if(isset($product['calories'])): ?>
                    <span><i class="fas fa-fire"></i> <?= $product['calories'] ?></span>
                    <?php endif; ?>
                </div>

                <p class="description" style="margin-bottom: 2rem; font-size: 1.1rem; color: var(--text-light);"><?= $product['description'] ?></p>

                <h3 style="margin-bottom: 1rem;"></h3>
                
                <!-- Hidden Base Data -->
                <input type="hidden" name="name" value="<?= $product['name'] ?>">
                <input type="hidden" name="base_price" id="basePrice" value="<?= $product['price'] ?>">
                <input type="hidden" name="price" id="finalPrice" value="<?= $product['price'] ?>">
                <input type="hidden" name="image" value="<?= $product['image'] ?>">

                <div class="ingredients-list" style="margin-bottom: 2rem;">
                    <?php foreach ($product['ingredients'] as $ing): ?>
                    <div class="ingredient-opt" style="display: flex; justify-content: space-between; align-items: center; padding: 0.8rem 0; border-bottom: 1px solid #eee;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <!-- Checkbox with data-price attribute -->
                            <input type="checkbox" 
                                   name="extras[]" 
                                   value="<?= $ing['name'] ?>" 
                                   data-cost="<?= $ing['price'] ?>"
                                   <?= $ing['default'] ? 'checked' : '' ?> 
                                   onchange="calculateTotal()"
                                   style="width: 18px; height: 18px; accent-color: var(--primary);">
                            <?= $ing['name'] ?>
                        </label>
                        <span class="ing-price" style="color: var(--text-light); font-size: 0.9rem;">
                            <?= $ing['price'] > 0 ? '+ $' . number_format($ing['price'], 2) : '' ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="actions" style="display: flex; gap: 1rem; align-items: center;">
                    <div class="qty-selector" style="display: flex; align-items: center; border: 1px solid #ddd; border-radius: 5px; overflow: hidden;">
                        <button type="button" onclick="changeQty(-1)" style="padding: 0.8rem 1.2rem; background: none; border: none; cursor: pointer; font-size: 1.2rem;">-</button>
                        <input type="number" name="qty" id="qtyInput" value="1" style="width: 50px; text-align: center; border: none; outline: none; -moz-appearance: textfield;">
                        <button type="button" onclick="changeQty(1)" style="padding: 0.8rem 1.2rem; background: none; border: none; cursor: pointer; font-size: 1.2rem;">+</button>
                    </div>
                    <button type="submit" class="btn-cta" style="flex: 1; text-align: center; border:none; cursor: pointer;">Agregar al Pedido</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="reviews-section" style="margin-top: 5rem;">
        <h2 class="section-title">Opiniones de Clientes</h2>
        
        <!-- Review Form -->
        <div class="review-form" style="background: #f9f9f9; padding: 2rem; border-radius: var(--radius); margin-bottom: 3rem;">
            <h3 style="margin-bottom: 1rem;">Deja tu opinión</h3>
            
            <?php if(isset($user)): ?>
                <form action="<?= base_url('menu/submit_review') ?>" method="post">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem;">Nombre</label>
                            <input type="text" name="user" value="<?= $user['name'] ?>" readonly style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px; background: #e9ecef; cursor: not-allowed;">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem;">Calificación</label>
                            <select name="rating" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;">
                                <option value="5">5 Estrellas - Excelente</option>
                                <option value="4">4 Estrellas - Muy Bueno</option>
                                <option value="3">3 Estrellas - Bueno</option>
                                <option value="2">2 Estrellas - Regular</option>
                                <option value="1">1 Estrella - Malo</option>
                            </select>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem;">Comentario</label>
                        <textarea name="comment" rows="3" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;"></textarea>
                    </div>
                    
                    <button type="submit" class="btn-cta" style="border:none; cursor: pointer;">Publicar Opinión</button>
                </form>
            <?php else: ?>
                <div style="text-align: center; padding: 2rem;">
                    <p style="margin-bottom: 1rem; color: var(--text-light);">Debes iniciar sesión para dejar una opinión.</p>
                    <a href="<?= base_url('user/login') ?>" class="btn-cta">Inicia Sesión</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="reviews-list">
            <?php foreach ($product['reviews'] as $review): ?>
            <div class="review-item" style="border-bottom: 1px solid #eee; padding: 1.5rem 0;">
                <div class="review-header" style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <strong><?= $review['user'] ?></strong>
                    <span class="text-muted" style="font-size: 0.9rem;"><?= $review['date'] ?></span>
                </div>
                <div class="stars" style="color: var(--accent); margin-bottom: 0.5rem;">
                     <?php for($i=0; $i<$review['rating']; $i++): ?><i class="fas fa-star"></i><?php endfor; ?>
                </div>
                <p><?= $review['comment'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
    function changeQty(change) {
        let input = document.getElementById('qtyInput');
        let val = parseInt(input.value) + change;
        if(val >= 1) input.value = val;
    }

    function calculateTotal() {
        const basePrice = parseFloat(document.getElementById('basePrice').value);
        let extraCost = 0;
        
        document.querySelectorAll('input[name="extras[]"]:checked').forEach(chk => {
            extraCost += parseFloat(chk.dataset.cost);
        });

        const newTotal = basePrice + extraCost;
        
        // Update visual display
        document.getElementById('displayPrice').innerText = '$' + newTotal.toFixed(2);
        
        // Update hidden input sent to backend
        document.getElementById('finalPrice').value = newTotal.toFixed(2);
    }

    // Run on load to account for default checked items if any have cost (though usually defaults are free)
    // window.onload = calculateTotal; 
    // In this mock data defaults are price 0, but good practice.
</script>

<?= $this->endSection() ?>
