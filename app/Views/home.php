<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="hero">
    <div class="container hero-content">
        <h1>Sabor Auténtico <br> en Cada Rebanada</h1>
        <p>Ingredientes frescos, masa artesanal y entrega rápida.</p>
        <a href="<?= base_url('menu') ?>" class="btn-cta">Pedir Ahora</a>
    </div>
</section>

<!-- Our Quality Promise -->
<section class="section" style="padding-bottom: 0;">
    <div class="container text-center">
        <div style="display: flex; justify-content: space-around; flex-wrap: wrap; gap: 2rem;">
            <div style="max-width: 300px;">
                <i class="fas fa-fire-alt" style="font-size: 3rem; color: var(--primary); margin-bottom: 1rem;"></i>
                <h3 style="margin-bottom: 0.5rem;">Horno de Leña</h3>
                <p class="text-muted">Cocción tradicional a 400°C para esa textura crujiente perfecta.</p>
            </div>
            <div style="max-width: 300px;">
                <i class="fas fa-leaf" style="font-size: 3rem; color: var(--success); margin-bottom: 1rem;"></i>
                <h3 style="margin-bottom: 0.5rem;">Ingredientes Frescos</h3>
                <p class="text-muted">Vegetales cortados al día y masa fermentada por 24 horas.</p>
            </div>
            <div style="max-width: 300px;">
                <i class="fas fa-shipping-fast" style="font-size: 3rem; color: var(--secondary); margin-bottom: 1rem;"></i>
                <h3 style="margin-bottom: 0.5rem;">Entrega en 30'</h3>
                <p class="text-muted">Si no llega caliente en 30 minutos, ¡es gratis!</p>
            </div>
        </div>
    </div>
</section>

<!-- Kiosk Promo Section -->
<section class="section" style="background: linear-gradient(135deg, #fff5f2 0%, #ffffff 100%); margin-top: 3rem;">
    <div class="container">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 4rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 300px;">
                <span style="color: var(--primary); font-weight: 700; text-transform: uppercase; letter-spacing: 2px;">Nuevo Menú Digital Forno d'Oro</span>
                <h2 style="font-family: var(--font-heading); font-size: 3rem; margin: 1rem 0; line-height: 1.2;">Diseña tu Pizza <br> Ingrediente a Ingrediente</h2>
                <p style="font-size: 1.2rem; margin-bottom: 2rem; color: var(--text-light);">
                    ¿Tienes una combinación única en mente? Usa nuestro nuevo constructor visual interactivo. 
                    Elige tu salsa, queso y toppings favoritos y mira cómo cobra vida en tiempo real.
                </p>
                <a href="<?= base_url('kiosko') ?>" class="btn-cta" style="background: var(--secondary);"><i class="fas fa-magic"></i> Crear mi Pizza Ahora</a>
            </div>
            <div style="flex: 1; min-width: 300px; text-align: center;">
                 <img src="<?= base_url('images/kiosko/base_crust.png') ?>" alt="Kiosko Preview" style="max-width: 100%; border-radius: 50%; box-shadow: var(--shadow-md); animation: spin-slow 30s linear infinite;">
            </div>
        </div>
    </div>
</section>

<!-- Signature Pizzas Carousel -->
<section class="section">
    <div class="container">
        <h2 class="section-title">Pizzas de Autor</h2>
        
        <div class="carousel-wrapper" style="position: relative; overflow: hidden; padding: 1rem 0;">
            <button id="prevBtn" style="position: absolute; left: 0; top: 50%; transform: translateY(-50%); z-index: 10; background: var(--surface); border: none; width: 40px; height: 40px; border-radius: 50%; box-shadow: var(--shadow-md); cursor: pointer; color: var(--primary); font-size: 1.2rem;"><i class="fas fa-chevron-left"></i></button>
            <button id="nextBtn" style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); z-index: 10; background: var(--surface); border: none; width: 40px; height: 40px; border-radius: 50%; box-shadow: var(--shadow-md); cursor: pointer; color: var(--primary); font-size: 1.2rem;"><i class="fas fa-chevron-right"></i></button>

            <div class="carousel-track" id="track">
                <style>.carousel-track::-webkit-scrollbar { display: none; }</style>

                <?php foreach ($featured_products as $product): ?>
                <div class="carousel-card">
                    <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>" style="width: 100%; height: 200px; object-fit: cover;">
                    
                    <!-- Info Button -->
                    <button class="btn-info" onclick="showInfo(this)" 
                        data-name="<?= $product['name'] ?>" 
                        data-desc="<?= $product['description'] ?>"
                        data-weight="<?= isset($product['weight']) ? $product['weight'] : 'N/A' ?>"
                        data-calories="<?= isset($product['calories']) ? $product['calories'] : 'N/A' ?>"
                        style="position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.9); border: none; width: 30px; height: 30px; border-radius: 50%; cursor: pointer; color: var(--text-main); transition: 0.3s;">
                        <i class="fas fa-info"></i>
                    </button>

                    <div class="product-info" style="padding: 1.5rem;">
                        <span class="product-category" style="color: var(--primary); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;"><?= $product['category'] ?></span>
                        <h3 style="margin: 0.5rem 0;"><?= $product['name'] ?></h3>
                        <p class="text-muted" style="margin-bottom: 1rem; font-size: 0.9rem;"><?= $product['description'] ?></p>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-weight: 700; font-size: 1.2rem;">$<?= number_format($product['price'], 2) ?></span>
                            <a href="<?= base_url('menu/detail/' . $product['id']) ?>" style="background: var(--secondary); color: white; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center;"><i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Master Chefs Section -->
<section class="section" style="background-color: var(--surface);">
    <div class="container">
        <h2 class="section-title">Nuestros Maestros</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
            <?php foreach ($chefs as $chef): ?>
            <div class="chef-card" style="text-align: center;">
                <img src="<?= $chef['image'] ?>" alt="<?= $chef['name'] ?>" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 1rem; border: 3px solid var(--primary); padding: 5px;">
                <h3 style="margin-bottom: 0.2rem;"><?= $chef['name'] ?></h3>
                <p style="color: var(--primary); font-weight: 600; font-size: 0.9rem; margin-bottom: 1rem; text-transform: uppercase;"><?= $chef['role'] ?></p>
                <p class="text-muted"><?= $chef['bio'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Reviews Preview Section -->
<section id="reviews" class="section">
    <div class="container">
        <h2 class="section-title">Lo que dicen nuestros clientes</h2>
        <div class="reviews-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            <?php foreach ($reviews as $review): ?>
            <div class="review-card" style="padding: 2rem; background: var(--background); border-radius: var(--radius); box-shadow: var(--shadow-sm);">
                <div class="stars" style="color: var(--accent); margin-bottom: 1rem;">
                    <?php for($i=0; $i<$review['rating']; $i++): ?><i class="fas fa-star"></i><?php endfor; ?>
                </div>
                <p style="font-style: italic; margin-bottom: 1rem;">"<?= $review['comment'] ?>"</p>
                <h4 style="font-family: var(--font-heading);"><?= $review['user'] ?></h4>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Nutritional Info Modal -->
<div id="nutriModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: var(--radius); max-width: 400px; width: 90%; position: relative; animation: slideUp 0.3s ease;">
        <button onclick="closeModal()" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
        
        <h2 id="modalTitle" style="font-family: var(--font-heading); margin-bottom: 1rem; color: var(--primary);"></h2>
        <p id="modalDesc" style="color: var(--text-light); margin-bottom: 1.5rem;"></p>
        
        <div style="background: var(--background); padding: 1rem; border-radius: 8px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <strong><i class="fas fa-weight-hanging"></i> Peso:</strong>
                <span id="modalWeight"></span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <strong><i class="fas fa-fire"></i> Calorías:</strong>
                <span id="modalCals"></span>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const track = document.getElementById('track');
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        let autoPlayInterval;

        if (!track || !nextBtn || !prevBtn) {
            console.error('Carousel elements not found');
            return;
        }

        function getScrollAmount() {
            try {
                const card = track.querySelector('.carousel-card');
                if (card && card.offsetWidth > 0) {
                    const style = window.getComputedStyle(track);
                    const gap = parseFloat(style.columnGap) || 32; // Default 2rem = 32px
                    return card.offsetWidth + gap;
                }
            } catch (e) {
                console.error('Error calculating scroll width', e);
            }
            return 320; // Default fallback width
        }

        function moveNext() {
            const amount = getScrollAmount();
            // Check if we are near the end
            if (track.scrollLeft + track.clientWidth >= track.scrollWidth - 10) {
                track.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
                track.scrollBy({ left: amount, behavior: 'smooth' });
            }
        }

        function movePrev() {
            const amount = getScrollAmount();
            if (track.scrollLeft <= 10) {
                track.scrollTo({ left: track.scrollWidth, behavior: 'smooth' });
            } else {
                track.scrollBy({ left: -amount, behavior: 'smooth' });
            }
        }

        nextBtn.addEventListener('click', () => {
            stopAutoPlay();
            moveNext();
            startAutoPlay();
        });

        prevBtn.addEventListener('click', () => {
            stopAutoPlay();
            movePrev();
            startAutoPlay();
        });

        function startAutoPlay() {
            stopAutoPlay(); // Prevent multiple intervals
            autoPlayInterval = setInterval(moveNext, 3000); // 3 seconds
        }

        function stopAutoPlay() {
            if (autoPlayInterval) clearInterval(autoPlayInterval);
        }

        // Pause on hover
        track.parentElement.addEventListener('mouseenter', stopAutoPlay);
        track.parentElement.addEventListener('mouseleave', startAutoPlay);

        // Start initially
        startAutoPlay();
    });

    // Modal Logic
    const modal = document.getElementById('nutriModal');
    
    function showInfo(btn) {
        document.getElementById('modalTitle').innerText = btn.dataset.name;
        document.getElementById('modalDesc').innerText = btn.dataset.desc;
        document.getElementById('modalWeight').innerText = btn.dataset.weight;
        document.getElementById('modalCals').innerText = btn.dataset.calories;
        
        modal.style.display = 'flex';
    }

    function closeModal() {
        modal.style.display = 'none';
    }

    // Close on click outside
    window.onclick = function(event) {
        if (event.target == modal) {
            closeModal();
        }
    }
</script>

<style>
    @keyframes slideUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .btn-info:hover {
        background: var(--primary) !important;
        color: white !important;
        transform: rotate(15deg);
    }
    @media (min-width: 768px) {
        .carousel-card {
            flex: 0 0 calc((100% - 4rem) / 3); /* 3 items minus 2 gaps of 2rem */
        }
    }
    @media (max-width: 767px) {
        .carousel-card {
            flex: 0 0 100%;
        }
    }
    .carousel-track {
        display: flex;
        gap: 2rem;
        padding: 1rem 0; /* Remove horizontal padding to fit logic */
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scrollbar-width: none;
        scroll-behavior: smooth;
    }
    .carousel-card {
        scroll-snap-align: start;
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        position: relative;
    }
    .carousel-track::-webkit-scrollbar { display: none; }
</style>

<?= $this->endSection() ?>
