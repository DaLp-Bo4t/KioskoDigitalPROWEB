<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container section">
    <div class="menu-header" style="text-align: center; margin-bottom: 3rem;">
        <h1 style="font-family: var(--font-heading); font-size: 3rem; margin-bottom: 1rem;">Nuestro Menú</h1>
        <p class="text-muted">Explora nuestras deliciosas opciones artesanales</p>
    </div>

    <!-- Filters -->
    <div class="menu-controls" style="display: flex; justify-content: center; margin-bottom: 3rem;">
        <div class="categories-filter" style="display: flex; gap: 1rem; overflow-x: auto; padding-bottom: 0.5rem;">
            <?php foreach ($categories as $cat): ?>
            <button class="filter-btn <?= $cat['slug'] === 'all' ? 'active' : '' ?>" data-filter="<?= $cat['slug'] ?>" 
                style="background: <?= $cat['slug'] === 'all' ? 'var(--primary)' : 'white' ?>; 
                       color: <?= $cat['slug'] === 'all' ? 'white' : 'var(--text-main)' ?>;
                       border: 1px solid #ddd; padding: 0.5rem 1.5rem; border-radius: 50px; cursor: pointer; transition: var(--transition);">
                <?= $cat['name'] ?>
            </button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Product Logic with pure JS handling for mock filtering -->
    <div class="grid-products" id="product-grid">
        <?php foreach ($products as $product): ?>
        <div class="product-card" data-category="<?= $product['category_slug'] ?>">
            <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="product-image">
            <div class="product-info">
                <span class="product-category"><?= $product['category_slug'] ?></span>
                <h3 class="product-title"><?= $product['name'] ?></h3>
                <p class="text-muted"><?= $product['description'] ?></p>
                <div class="product-meta">
                    <span class="product-price">$<?= number_format($product['price'], 2) ?></span>
                    <a href="<?= base_url('menu/detail/' . $product['id']) ?>" class="btn-add">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    // Simple Client-Side Filtering Script
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active classes
            document.querySelectorAll('.filter-btn').forEach(b => {
                b.style.backgroundColor = 'white';
                b.style.color = 'var(--text-main)';
                b.classList.remove('active');
            });
            // Add active class
            btn.classList.add('active');
            btn.style.backgroundColor = 'var(--primary)';
            btn.style.color = 'white';
            
            const filter = btn.dataset.filter;
            const cards = document.querySelectorAll('.product-card');
            
            cards.forEach(card => {
                if (filter === 'all' || card.dataset.category === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>
