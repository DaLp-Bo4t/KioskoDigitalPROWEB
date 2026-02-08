<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="section" style="padding-top: 2rem;">
    <div class="container">
        


        <h1 class="section-title">Arma tu Pizza Perfecta</h1>
        
        <?php 
            $loadedProxy = session()->getFlashdata('loaded_pizza'); 
            $defaultSize = 'size_personal';
            $defaultCheese = 'cheese_mozarella';
            $loadedToppings = [];

            if($loadedProxy) {
                $defaultSize = $loadedProxy['size'] ?? $defaultSize;
                $defaultCheese = $loadedProxy['cheese'] ?? $defaultCheese;
                $loadedToppings = $loadedProxy['toppings'] ?? [];
            }
        ?>

        <div class="kiosk-grid">
            <!-- Contenedor izquierdo: Pizza + Precio (sticky en desktop) -->
            <div class="pizza-preview-wrapper">
                <div class="visualizer-container">
                    <div class="pizza-stage">
                        <img src="<?= base_url('images/Pizzas/PIZZA CON SALSA DE TOMATE.png') ?>" id="dynamicPizzaImage" class="layer active" 
                             onerror="handleImageError(this)">
                    </div>
                </div>

                <div class="price-tag-floating">
                    $<span id="totalPriceDisplay">10.00</span>
                </div>
            </div>

            <!-- Contenedor derecho: Controles de selección -->
            <div class="controls-panel">
                <form action="<?= base_url('kiosko/add') ?>" method="post" id="pizzaForm">
                    <input type="hidden" name="total_price" id="inputTotalPrice" value="10.00">
                    <input type="hidden" name="description" id="inputDescription" value="">
                    
                    <!-- STEP 1: TAMAÑO -->
                    <div id="step-1" class="step-section active">
                        <div class="control-group">
                            <h3><i class="fas fa-expand-arrows-alt"></i> Paso 1: Elige el Tamaño</h3>
                            <div class="options-grid">
                                <?php foreach($sizes as $sz): ?>
                                <label class="option-card">
                                    <input type="radio" name="size" value="<?= $sz['id'] ?>" data-price="<?= $sz['price'] ?>" data-name="<?= $sz['name'] ?>" <?= $sz['id'] == $defaultSize ? 'checked' : '' ?> onchange="updatePizza()">
                                    <div class="card-content">
                                        <span><?= $sz['name'] ?></span>
                                        <span class="price-badge">$<?= $sz['price'] ?></span>
                                    </div>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <button type="button" class="btn-cta btn-block" onclick="nextStep(2)">
                            Siguiente <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>

                    <!-- STEP 2: QUESO -->
                    <div id="step-2" class="step-section" style="display: none;">
                        <div class="control-group">
                            <h3><i class="fas fa-cheese"></i> Paso 2: Elige el Queso</h3>
                            <div class="options-grid">
                                <?php foreach($cheeses as $c): ?>
                                <label class="option-card">
                                    <input type="radio" name="cheese" value="<?= $c['id'] ?>" data-price="<?= $c['price'] ?>" data-name="<?= $c['name'] ?>" <?= $c['id'] == $defaultCheese ? 'checked' : '' ?> onchange="updatePizza()">
                                    <div class="card-content">
                                        <span><?= $c['name'] ?></span>
                                        <?php if($c['price'] > 0): ?>
                                        <span class="price-badge">+$<?= number_format($c['price'], 2) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="nav-buttons">
                            <button type="button" class="btn-secondary" onclick="prevStep(1)">
                                <i class="fas fa-arrow-left"></i> Atrás
                            </button>
                            <button type="button" class="btn-cta" onclick="nextStep(3)">
                                Siguiente <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 3: INGREDIENTES -->
                    <div id="step-3" class="step-section" style="display: none;">
                        <div class="control-group">
                            <h3><i class="fas fa-pizza-slice"></i> Paso 3: Agrega Ingredientes</h3>
                            <div class="options-grid">
                                
                                <label class="option-card">
                                    <input type="checkbox" name="toppings[]" value="ACEITUNAS" data-price="1.00" data-name="Aceitunas" <?= in_array('ACEITUNAS', $loadedToppings) ? 'checked' : '' ?> onchange="updatePizza()">
                                    <div class="card-content"><span>Aceitunas</span><span class="price-badge">+$1.00</span></div>
                                </label>

                                <label class="option-card">
                                    <input type="checkbox" name="toppings[]" value="CEBOLLA" data-price="0.50" data-name="Cebolla" <?= in_array('CEBOLLA', $loadedToppings) ? 'checked' : '' ?> onchange="updatePizza()">
                                    <div class="card-content"><span>Cebolla</span><span class="price-badge">+$0.50</span></div>
                                </label>

                                <label class="option-card">
                                    <input type="checkbox" name="toppings[]" value="CHAMPI" data-price="1.50" data-name="Champiñones" <?= in_array('CHAMPI', $loadedToppings) ? 'checked' : '' ?> onchange="updatePizza()">
                                    <div class="card-content"><span>Champiñones</span><span class="price-badge">+$1.50</span></div>
                                </label>

                                <label class="option-card">
                                    <input type="checkbox" name="toppings[]" value="JAMON" data-price="1.50" data-name="Jamón" <?= in_array('JAMON', $loadedToppings) ? 'checked' : '' ?> onchange="updatePizza()">
                                    <div class="card-content"><span>Jamón</span><span class="price-badge">+$1.50</span></div>
                                </label>

                                <label class="option-card">
                                    <input type="checkbox" name="toppings[]" value="MAIZ" data-price="1.00" data-name="Maíz" <?= in_array('MAIZ', $loadedToppings) ? 'checked' : '' ?> onchange="updatePizza()">
                                    <div class="card-content"><span>Maíz</span><span class="price-badge">+$1.00</span></div>
                                </label>

                                <label class="option-card">
                                    <input type="checkbox" name="toppings[]" value="PEPERONI" data-price="1.50" data-name="Peperoni" <?= in_array('PEPERONI', $loadedToppings) ? 'checked' : '' ?> onchange="updatePizza()">
                                    <div class="card-content"><span>Pepperoni</span><span class="price-badge">+$1.50</span></div>
                                </label> 

                                <label class="option-card">
                                    <input type="checkbox" name="toppings[]" value="PIMIENTOS" data-price="1.00" data-name="Pimientos" <?= in_array('PIMIENTOS', $loadedToppings) ? 'checked' : '' ?> onchange="updatePizza()">
                                    <div class="card-content"><span>Pimientos</span><span class="price-badge">+$1.00</span></div>
                                </label>

                                <label class="option-card">
                                    <input type="checkbox" name="toppings[]" value="PIÑA" data-price="1.50" data-name="Piña" <?= in_array('PIÑA', $loadedToppings) ? 'checked' : '' ?> onchange="updatePizza()">
                                    <div class="card-content"><span>Piña</span><span class="price-badge">+$1.50</span></div>
                                </label>

                                <label class="option-card">
                                    <input type="checkbox" name="toppings[]" value="POLLO FRITO" data-price="2.00" data-name="Pollo" <?= in_array('POLLO FRITO', $loadedToppings) ? 'checked' : '' ?> onchange="updatePizza()">
                                    <div class="card-content"><span>Pollo Frito</span><span class="price-badge">+$2.00</span></div>
                                </label>

                                <label class="option-card">
                                    <input type="checkbox" name="toppings[]" value="TOCINETA" data-price="2.00" data-name="Tocineta" <?= in_array('TOCINETA', $loadedToppings) ? 'checked' : '' ?> onchange="updatePizza()">
                                    <div class="card-content"><span>Tocineta</span><span class="price-badge">+$2.00</span></div>
                                </label>

                            </div>
                        </div>
                        <div class="nav-buttons">
                            <button type="button" class="btn-secondary" onclick="prevStep(2)">
                                <i class="fas fa-arrow-left"></i> Atrás
                            </button>
                            <button type="button" class="btn-secondary" onclick="saveFavorite()" style="background: #fff3e0; border-color: #ffcc80; color: #e65100;">
                                <i class="fas fa-heart"></i> Guardar
                            </button>
                            <button type="submit" class="btn-cta" style="flex: 2;">
                                <i class="fas fa-shopping-cart"></i> Agregar al Carrito
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top: 3rem; border-top: 2px solid #eee; padding-top: 2rem;">
        <?php if(!empty($saved_pizzas)): ?>
        <div class="saved-pizzas-bar" style="margin-bottom: 2rem; overflow-x: auto; padding-bottom: 1rem;">
            <h3 style="font-size: 1.5rem; margin-bottom: 1.5rem; text-align: center;">
                <i class="fas fa-heart" style="color: var(--primary);"></i> Mis Pizzas Favoritas
            </h3>
            <div style="display: flex; gap: 1.5rem; justify-content: center; flex-wrap: wrap;">
                <?php foreach($saved_pizzas as $sp): ?>
                    <?php $conf = json_decode($sp['configuration'], true); ?>
                    <div class="saved-pizza-card" style="min-width: 220px; background: white; padding: 1.5rem; border-radius: 12px; border: 1px solid #eaeaea; box-shadow: 0 4px 12px rgba(0,0,0,0.05); transition: transform 0.2s;">
                        <strong style="display: block; margin-bottom: 0.8rem; color: var(--text-dark); font-size: 1.1rem;"><?= esc($sp['name']) ?></strong>
                        <div style="font-size: 0.9rem; color: var(--text-light); margin-bottom: 1.2rem;">
                            <i class="fas fa-pizza-slice"></i> <?= count($conf['toppings'] ?? []) ?> Ingredientes
                        </div>
                        <div style="display: flex; gap: 0.8rem; flex-direction: row; justify-content: center;">
                            <a href="<?= base_url('kiosko/orderFavorite/'.$sp['id']) ?>" class="btn-secondary" title="Cargar y Editar" style="flex: 1; text-align: center; padding: 0.8rem; border-radius: 10px;">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= base_url('kiosko/addFavoriteToCart/'.$sp['id']) ?>" class="btn-cta" title="Agregar al Carrito" style="flex: 1; text-align: center; padding: 0.8rem; border-radius: 10px; font-size: 1.1rem;">
                                <i class="fas fa-cart-plus"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Modal para Guardar Favorita -->
    <div id="saveFavoriteModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-heart" style="color: var(--primary);"></i> Nombre de tu Favorita</h3>
                <button type="button" class="close-modal" onclick="closeSaveModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Dale un nombre especial a tu creación:</p>
                <input type="text" id="favoritePizzaName" placeholder="Ej: Mi Pizza Especial" class="modal-input">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeSaveModal()">Cancelar</button>
                <button type="button" class="btn-cta" onclick="confirmSaveFavorite()">Guardar Pizza</button>
            </div>
        </div>
    </div>
</section>

<style>
    .kiosk-grid { 
        display: flex; 
        flex-direction: row; 
        align-items: flex-start; 
        gap: 2rem; 
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .pizza-preview-wrapper {
        position: sticky;
        top: 2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        min-width: 350px;
        max-width: 500px;
    }
    
    .visualizer-container { 
        position: relative; 
        top: 0; 
        background: radial-gradient(circle, #ffffff 0%, #f0f0f0 100%); 
        border-radius: 50%; 
        padding: 1rem; 
        box-shadow: var(--shadow-md); 
        aspect-ratio: 1/1; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        width: 100%; 
        max-width: 450px;
        margin: 0 auto; 
        overflow: hidden; 
    }
    
    .pizza-stage { position: relative; width: 100%; height: 100%; transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1); }
    .layer { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain; transition: opacity 0.3s ease; }
    .base-layer { z-index: 10; }
    #dynamicPizzaImage { z-index: 20; opacity: 1; }
    
    .controls-panel { 
        background: var(--surface); 
        padding: 2rem; 
        border-radius: var(--radius); 
        box-shadow: var(--shadow-sm); 
        flex: 1.2;
        min-width: 400px;
    }
    
    .control-group { margin-bottom: 2rem; }
    .control-group h3 { margin-bottom: 1rem; font-family: var(--font-heading); border-bottom: 2px solid var(--background); padding-bottom: 0.5rem; }
    .options-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 1rem; justify-content: center; } 
    .option-card { cursor: pointer; position: relative; }
    .option-card input { display: none; }
    .card-content { border: 2px solid var(--background); padding: 1rem; border-radius: 8px; text-align: center; transition: var(--transition); display: flex; flex-direction: column; gap: 0.5rem; align-items: center; height: 100%; justify-content: center; }
    .option-card input:checked + .card-content { border-color: var(--primary); background-color: #fff5f2; box-shadow: 0 4px 10px rgba(255, 87, 34, 0.2); transform: translateY(-2px); }
    .price-badge { font-size: 0.9rem; background: var(--secondary); color: white; padding: 4px 10px; border-radius: 12px; font-weight: bold; }

    /* When toppings reach the max, disabled options look subdued */
    .option-card input:disabled + .card-content { opacity: 0.55; pointer-events: none; cursor: not-allowed; }
    
    .price-tag-floating { 
        position: relative; 
        margin-top: -2rem; 
        background: var(--primary); 
        color: white; 
        padding: 0.8rem 2rem; 
        font-size: 2.2rem; 
        font-weight: 800; 
        border-radius: 50px; 
        box-shadow: 0 8px 20px rgba(0,0,0,0.2); 
        z-index: 100; 
        border: 4px solid white;
    }
    
    .step-section { animation: fadeIn 0.4s ease; }
    .nav-buttons { display: flex; gap: 1rem; margin-top: 2rem; justify-content: space-between; }
    .btn-secondary { background: #f0f0f0; color: #333; border: 2px solid #ddd; padding: 1rem 2rem; border-radius: 8px; cursor: pointer; font-weight: bold; transition: 0.3s; font-size: 1.1rem; }
    .btn-secondary:hover { background: #e0e0e0; border-color: #ccc; }
    .btn-cta { font-size: 1.2rem; padding: 1rem 2rem; }
    
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    /* Responsive: en pantallas pequeñas vuelve a layout vertical */
    @media (max-width: 900px) { 
        .kiosk-grid {
            flex-direction: column;
            align-items: center;
        }
        .pizza-preview-wrapper {
            position: relative;
            top: 0;
            max-width: 100%;
            min-width: unset;
        }
        .controls-panel {
            min-width: unset;
            width: 100%;
            max-width: 600px;
        }
        .price-tag-floating { font-size: 1.8rem; padding: 0.5rem 1.5rem; }
    }

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(8px);
        display: none; /* Changed from display:flex to none initially */
        align-items: center;
        justify-content: center;
        z-index: 9999; /* Higher z-index to be above everything */
        animation: fadeIn 0.3s ease;
    }
    .modal-content {
        background: white;
        padding: 2.5rem;
        border-radius: 20px;
        width: 90%;
        max-width: 450px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        animation: slideUp 0.3s ease;
        position: relative;
        z-index: 10000;
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .modal-header h3 {
        margin: 0;
        font-family: var(--font-heading);
        font-size: 1.6rem;
    }
    .close-modal {
        background: none;
        border: none;
        font-size: 1.8rem;
        cursor: pointer;
        color: #999;
        transition: 0.3s;
    }
    .close-modal:hover { color: var(--primary); }
    .modal-body p { margin-bottom: 1rem; color: #666; }
    .modal-input {
        width: 100%;
        padding: 1rem;
        border: 2px solid #eee;
        border-radius: 12px;
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
        outline: none;
        transition: 0.3s;
    }
    .modal-input:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(255,87,34,0.1); }
    .modal-footer {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }
    @keyframes slideUp { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    .shake { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; }
    @keyframes shake {
        10%, 90% { transform: translate3d(-1px, 0, 0); }
        20%, 80% { transform: translate3d(2px, 0, 0); }
        30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
        40%, 60% { transform: translate3d(4px, 0, 0); }
    }
</style>

<script>
    // Lista de jerarquía (orden alfabético)
    const ingredientHierarchy = [
        'ACEITUNAS',
        'CEBOLLA',
        'CHAMPI',
        'JAMON',
        'MAIZ',
        'PEPERONI',
        'PIMIENTOS',
        'PIÑA',
        'POLLO FRITO',
        'TOCINETA' 
    ];

    function nextStep(step) {
        // Simple validation or just move
        showStep(step);
    }

    function prevStep(step) {
        showStep(step);
    }

    function showStep(stepNumber) {
        // Hide all steps
        document.querySelectorAll('.step-section').forEach(el => el.style.display = 'none');
        // Show target step
        const target = document.getElementById('step-' + stepNumber);
        if(target) {
            target.style.display = 'block';
            target.classList.add('active');
        }
    }

    function updatePizza() {
        let currentTotal = 0;
        let description = "Pizza Personalizada";
        
        // 1. Calcular Precio y Descripción
        const size = document.querySelector('input[name="size"]:checked');
        let sizeScale = 1.0;

        if (size) {
            currentTotal += parseFloat(size.dataset.price);
            description = "Pizza " + size.dataset.name;
            
            // Logic for sizing: 
            // Small (id likes size_personal) = 0% increase -> scale(1.0)
            // Medium (id likes size_medium) = 50% increase -> scale(1.5)
            // Large (id likes size_large) = 100% increase -> scale(2.0)
            
            if(size.value === 'size_medium') sizeScale = 1.5;
            if(size.value === 'size_large') sizeScale = 2.0;

        } else { currentTotal += 10.00; }
        
        // Apply transform
        const pizzaStage = document.querySelector('.pizza-stage');
        if(pizzaStage) {
            pizzaStage.style.transform = `scale(${sizeScale})`;
        }
        
        const cheese = document.querySelector('input[name="cheese"]:checked');
        if (cheese) {
            currentTotal += parseFloat(cheese.dataset.price);
            description += ", " + cheese.dataset.name;
        }

        const toppingsChecked = document.querySelectorAll('input[name="toppings[]"]:checked');
        let selectedToppings = [];
        
        toppingsChecked.forEach(t => {
            currentTotal += parseFloat(t.dataset.price);
            selectedToppings.push(t.value);
            description += ", " + t.dataset.name;
        });

        // 2. Construir Nombre de Imagen
        const imgElement = document.getElementById('dynamicPizzaImage');
        const basePath = "<?= base_url('images/Pizzas') ?>";
        let ingredientsSubPath = "";
        
        // Determinar base según el paso activo
        let filename = "PIZZA CON SALSA DE TOMATE.png"; // Default Step 1
        
        const step2 = document.getElementById('step-2');
        const step3 = document.getElementById('step-3');

        // Si estamos en paso 2 o 3, la base lógica es con queso
        if ((step2 && step2.style.display !== 'none') || (step3 && step3.style.display !== 'none')) {
            if (cheese && cheese.dataset && cheese.dataset.name) {
                const cName = cheese.dataset.name.toUpperCase();
                if (cName.includes('MOZ') || cName.includes('MOZZA') || cName.includes('MOZZ')) {
                    filename = "1- QUESO MOZZA.png";
                    ingredientsSubPath = "Mozarella";
                } else if (cName.includes('CHED')) {
                    filename = "1- QUESO CHEDDAR.png";
                    ingredientsSubPath = "Cheddar";
                } else if (cName.includes('PARM')) {
                    filename = "1- QUESO PARMESANO.png";
                    ingredientsSubPath = "Parmesano";
                }
            }
        }

        if (selectedToppings.length > 0) {
            // Ordenar según la jerarquía visual de tus archivos
            selectedToppings.sort((a, b) => {
                let indexA = ingredientHierarchy.indexOf(a);
                let indexB = ingredientHierarchy.indexOf(b);
                // Si no está en la lista (index -1), lo mandamos al final
                if (indexA === -1) indexA = 99;
                if (indexB === -1) indexB = 99;
                return indexA - indexB;
            });

            // Lógica de nombres según cantidad
            if (selectedToppings.length <= 2) {
                // 1 o 2 ingredientes: Empiezan con "PIZZA CON..."
                if (selectedToppings.length === 1) {
                    filename = selectedToppings[0] + ".png";
                } else {
                    filename = selectedToppings[0] + " Y " + selectedToppings[1] + ".png";
                }
            } else if (selectedToppings.length === 3) {
                // 3 ingredientes: Formato directo "A B Y C.png"
                filename = selectedToppings[0] + " " + selectedToppings[1] + " Y " + selectedToppings[2] + ".png";
            } else {
                // Más de 3: Mostrar base 
                console.log("Más de 3 ingredientes seleccionados");
            }
        }

        // Build full URL using base + optional cheese subfolder and encode filename
        let fullPath = basePath + (ingredientsSubPath ? ('/' + ingredientsSubPath) : '');
        imgElement.src = fullPath + '/' + encodeURIComponent(filename);
        console.log("Buscar: " + filename + " - Mostrando: " + imgElement.src);

        // UI
        document.getElementById('totalPriceDisplay').innerText = currentTotal.toFixed(2);
        document.getElementById('inputTotalPrice').value = currentTotal.toFixed(2);
        document.getElementById('inputDescription').value = description;
    }

    // Wrap showStep to trigger updatePizza so image changes immediately on navigation
    const originalShowStep = showStep;
    showStep = function(stepNumber) {
        // Hide all steps
        document.querySelectorAll('.step-section').forEach(el => el.style.display = 'none');
        // Show target step
        const target = document.getElementById('step-' + stepNumber);
        if(target) {
            target.style.display = 'block';
            target.classList.add('active');
        }
        // Force update to check active step and change image
        updatePizza();
    };

    // Enforce a maximum number of toppings selectable at once
    function enforceToppingLimit() {
        const MAX_TOPPINGS = 3;
        const toppings = Array.from(document.querySelectorAll('input[name="toppings[]"]'));
        if (!toppings.length) return;

        const checkedCount = toppings.filter(t => t.checked).length;

        // Disable unchecked toppings when at limit, enable otherwise
        toppings.forEach(t => {
            if (!t.checked) t.disabled = (checkedCount >= MAX_TOPPINGS);
        });

        // Safety: if somehow there are more checked than allowed, uncheck extras (keep first ones)
        if (checkedCount > MAX_TOPPINGS) {
            let keep = 0;
            for (let i = 0; i < toppings.length; i++) {
                const t = toppings[i];
                if (t.checked) {
                    keep++;
                    if (keep > MAX_TOPPINGS) t.checked = false;
                }
            }
        }
    }

    // Manejo de errores: Si la imagen calculada no existe, vuelve a la base adecuada
    function handleImageError(img) {
        console.log("Imagen no encontrada: " + img.src + ". Revertiendo a base safe.");
        // Evita bucle infinito
        if (!decodeURIComponent(img.src).includes("PIZZA CON SALSA DE TOMATE")) {
            img.src = "<?= base_url('images/Pizzas/PIZZA CON SALSA DE TOMATE.png') ?>";
        }
    }

    // Save current configuration to favorites
    function saveFavorite() {
        const modal = document.getElementById('saveFavoriteModal');
        const input = document.getElementById('favoritePizzaName');
        input.value = ""; 
        modal.style.display = 'flex';
        
        // Timeout to ensure display:flex is rendered before focusing
        setTimeout(() => {
            input.focus();
            input.select();
        }, 100);

        // Allow pressing Enter to save
        input.onkeydown = function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                confirmSaveFavorite();
            }
        };
    }

    function closeSaveModal() {
        document.getElementById('saveFavoriteModal').style.display = 'none';
    }

    function confirmSaveFavorite() {
        const nameInput = document.getElementById('favoritePizzaName');
        const name = nameInput.value.trim();
        
        if (!name) {
            nameInput.classList.add('shake');
            setTimeout(() => nameInput.classList.remove('shake'), 500);
            return;
        }

        // 2. Gather Configuration
        const size = document.querySelector('input[name="size"]:checked')?.value;
        const cheese = document.querySelector('input[name="cheese"]:checked')?.value;
        const toppings = Array.from(document.querySelectorAll('input[name="toppings[]"]:checked')).map(el => el.value);

        const config = {
            size: size,
            cheese: cheese,
            toppings: toppings
        };

        // 3. Send to Server
        const formData = new FormData();
        formData.append('name', name);
        formData.append('configuration', JSON.stringify(config));

        fetch('<?= base_url('kiosko/saveConfiguration') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                closeSaveModal();
                alert("¡Pizza guardada en favoritos!");
                location.reload(); 
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Ocurrió un error al guardar.");
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Attach change listeners to toppings to enforce the max selection
        document.querySelectorAll('input[name="toppings[]"]').forEach(i => {
            i.addEventListener('change', () => {
                enforceToppingLimit();
                updatePizza();
            });
        });

        // Initial enforcement + UI update
        enforceToppingLimit();
        updatePizza();
        showStep(1); // Ensure start at step 1
    });
</script>

<?= $this->endSection() ?>