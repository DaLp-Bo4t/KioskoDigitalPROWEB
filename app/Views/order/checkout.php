<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container section">
    <h1>Finalizar Compra</h1>
    
    <div class="col-12" style="margin-bottom: 2rem; width: 100%;">
        <div class="card" style="background: white; padding: 1.5rem; border-radius: var(--radius); box-shadow: var(--shadow-sm); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h3 style="margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <span style="font-size: 1.5rem;">🛵</span> Estimación de Entrega
                </h3>
                <div style="margin-top: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-light); font-weight: 600;">
                        Dirección de Entrega:
                        <span style="font-weight: 400; font-size: 0.85rem; color: #666;">(Puedes pegar un link de Google Maps 📍)</span>
                    </label>
                    <div style="position: relative;">
                        <textarea id="delivery_address" name="delivery_address" form="payment-form" class="form-control" style="width: 100%; padding: 0.8rem; padding-right: 50px; border: 1px solid #ddd; border-radius: 6px; resize: none; font-family: inherit;" rows="2" placeholder="Introduce tu dirección exacta o pega un link de Google Maps" oninput="detectGoogleMapsLink()"><?= session('user.address') ?></textarea>
                        <button type="button" id="maps-link-btn" onclick="openMapsLink()" style="display: none; position: absolute; right: 8px; top: 50%; transform: translateY(-50%); background: #4285f4; color: white; border: none; border-radius: 4px; padding: 6px 10px; cursor: pointer; font-size: 0.85rem;" title="Abrir ubicación en Google Maps">
                            📍
                        </button>
                    </div>
                    <!-- Hidden fields to store coordinates if available -->
                    <input type="hidden" id="delivery_lat" name="delivery_lat" form="payment-form">
                    <input type="hidden" id="delivery_lng" name="delivery_lng" form="payment-form">
                    <input type="hidden" id="delivery_maps_link" name="delivery_maps_link" form="payment-form">
                    
                    <!-- Location status indicator -->
                    <div id="location-status" style="margin-top: 0.5rem; font-size: 0.85rem; display: none;">
                        <span id="location-status-text"></span>
                    </div>
                </div>
            </div>
            <div id="delivery-result" style="text-align: right;">
                <button type="button" onclick="calculateDelivery()" class="btn-secondary" style="padding: 0.8rem 1.5rem; background: #e0e0e0; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">
                    Calcular Tiempo
                </button>
            </div>
        </div>
    </div>

    <div class="row" style="display: flex; gap: 2rem; flex-wrap: wrap;">
        <!-- Order Summary -->
        <div class="col-md-6" style="flex: 1; min-width: 300px;">
            <div class="card" style="background: white; padding: 1.5rem; border-radius: var(--radius); box-shadow: var(--shadow-sm);">
                <h3>Resumen del Pedido</h3>
                <ul style="list-style: none; padding: 0; margin-top: 1rem;">
                    <?php foreach ($cart as $item): ?>
                        <li style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eee; padding: 0.8rem 0; gap: 1rem;">
                            <div style="display: flex; align-items: center; gap: 0.8rem;">
                                <?php if(isset($item['image'])): ?>
                                    <img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                <?php endif; ?>
                                <span><?= $item['qty'] ?>x <?= $item['name'] ?></span>
                            </div>
                            <span style="font-weight: 500;">$<?= number_format($item['price'] * $item['qty'], 2) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div style="margin-top: 1rem; text-align: right; font-size: 1.2rem; font-weight: bold;">
                    Total USD: $<?= number_format($total, 2) ?>
                </div>
            </div>
        </div>

        <!-- Payment Method -->
        <div class="col-md-6" style="flex: 1; min-width: 300px;">
            <div class="card" style="background: white; padding: 1.5rem; border-radius: var(--radius); box-shadow: var(--shadow-sm);">
                <h3>Método de Pago</h3>
                
                <?php if (session()->getFlashdata('error')): ?>
                    <div style="background: #ffebee; color: #c62828; padding: 0.8rem; border-radius: 4px; margin-bottom: 1rem;">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('order/process_payment') ?>" method="post" id="payment-form">
                    
                    <div style="margin-bottom: 1rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; padding: 1rem; border: 1px solid #ddd; border-radius: 8px; cursor: pointer;">
                            <input type="radio" name="payment_method" value="zelle" required onchange="togglePaymentDetails()">
                            <span style="font-weight: 600;">Zelle</span>
                        </label>
                    </div>

                    <!-- Zelle Details -->
                    <div id="zelle-details" style="display: none; background: #f0f7ff; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #cce5ff;">
                        <h4 style="margin-top: 0; color: var(--primary);">Detalles de Zelle</h4>
                        <div style="background: white; padding: 0.8rem; border-radius: 4px; margin-bottom: 1rem; border: 1px dashed #aaa;">
                            <p style="margin: 0;"><strong>Correo Store:</strong> pagos@kioskodigital.com</p>
                            <p style="margin: 0;"><strong>Titular:</strong> Kiosko Digital CA</p>
                        </div>

                        <div class="form-group" style="margin-bottom: 0.5rem;">
                            <label for="zelle_holder">Nombre del Titular de la Cuenta Zelle:</label>
                            <input type="text" name="zelle_holder" id="zelle_holder" class="form-control" placeholder="Nombre completo" style="width: 100%; padding: 0.8rem; margin-top: 0.5rem; border: 1px solid #ccc; border-radius: 4px;">
                        </div>

                        <div class="form-group">
                            <label for="zelle_reference">Número de Referencia:</label>
                            <input type="text" name="zelle_reference" id="zelle_reference" class="form-control" placeholder="Ej: 12345678" style="width: 100%; padding: 0.8rem; margin-top: 0.5rem; border: 1px solid #ccc; border-radius: 4px;">
                        </div>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; padding: 1rem; border: 1px solid #ddd; border-radius: 8px; cursor: pointer;">
                            <input type="radio" name="payment_method" value="pago_movil" onchange="togglePaymentDetails()">
                            <span style="font-weight: 600;">Pago Móvil (Bolívares)</span>
                        </label>
                    </div>

                    <!-- Pago Movil Details -->
                    <div id="pago-movil-details" style="display: none; background: #f9f9f9; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #eee;">
                        <h4 style="margin-top: 0; color: var(--primary);">Detalles de Pago Móvil</h4>
                        <p style="margin: 0.5rem 0;"><strong>Tasa de Cambio:</strong> <?= number_format($exchangeRate, 2) ?> VES/USD</p>
                        <p style="margin: 0.5rem 0; font-size: 1.1rem;"><strong>Total a Pagar: Bs. <?= number_format($totalVES, 2) ?></strong></p>
                        
                        <hr style="margin: 1rem 0; border: 0; border-top: 1px solid #ddd;">
                        
                        <div style="background: white; padding: 0.8rem; border-radius: 4px; margin-bottom: 1rem; border: 1px dashed #aaa;">
                            <p style="margin: 0;"><strong>Banco:</strong> Banco de Venezuela (0102)</p>
                            <p style="margin: 0;"><strong>Teléfono:</strong> 0412-1234567</p>
                            <p style="margin: 0;"><strong>C.I / RIF:</strong> V-12345678</p>
                        </div>

                        <div class="form-group">
                            <label for="pm_reference">Número de Referencia:</label>
                            <input type="text" name="pm_reference" id="pm_reference" class="form-control" placeholder="Últimos 4-6 dígitos" maxlength="6" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)" style="width: 100%; padding: 0.8rem; margin-top: 0.5rem; border: 1px solid #ccc; border-radius: 4px;">
                        </div>
                    </div>

                    <button type="submit" class="btn-cta" style="width: 100%; text-align: center; margin-top: 1rem;">Confirmar Pedido</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Store detected location data
    let detectedLocation = {
        lat: null,
        lng: null,
        link: null
    };

    /**
     * Detect if the address input contains a Google Maps link and extract coordinates
     */
    function detectGoogleMapsLink() {
        const addressInput = document.getElementById('delivery_address');
        const address = addressInput.value.trim();
        const mapsBtn = document.getElementById('maps-link-btn');
        const statusDiv = document.getElementById('location-status');
        const statusText = document.getElementById('location-status-text');
        
        // Reset hidden fields
        document.getElementById('delivery_lat').value = '';
        document.getElementById('delivery_lng').value = '';
        document.getElementById('delivery_maps_link').value = '';
        
        // Patterns to detect Google Maps links and extract coordinates
        const patterns = [
            // Google Maps short links: https://maps.app.goo.gl/xyz or https://goo.gl/maps/xyz
            /(?:https?:\/\/)?(?:maps\.app\.goo\.gl|goo\.gl\/maps)\/[\w-]+/i,
            // Standard Google Maps URLs with @lat,lng
            /(?:https?:\/\/)?(?:www\.)?google\.[a-z.]+\/maps\/@(-?\d+\.?\d*),(-?\d+\.?\d*)/i,
            // Google Maps URLs with place or search
            /(?:https?:\/\/)?(?:www\.)?google\.[a-z.]+\/maps\/place\/[^@]*@(-?\d+\.?\d*),(-?\d+\.?\d*)/i,
            // Google Maps with query parameters
            /(?:https?:\/\/)?(?:www\.)?google\.[a-z.]+\/maps\?[^\s]*?(?:q|ll)=(-?\d+\.?\d*),(-?\d+\.?\d*)/i,
            // maps.google.com format
            /(?:https?:\/\/)?maps\.google\.[a-z.]+\/[^\s]*/i,
            // Direct coordinates format: lat,lng or lat, lng
            /^(-?\d{1,3}\.\d+)[,\s]+(-?\d{1,3}\.\d+)$/
        ];

        let isLink = false;
        let extractedCoords = null;

        // Check for Google Maps URL patterns
        for (const pattern of patterns) {
            const match = address.match(pattern);
            if (match) {
                isLink = true;
                
                // Try to extract coordinates from the match
                if (match[1] && match[2]) {
                    extractedCoords = {
                        lat: parseFloat(match[1]),
                        lng: parseFloat(match[2])
                    };
                }
                break;
            }
        }

        // Also check for direct coordinates
        const directCoordsMatch = address.match(/^(-?\d{1,3}\.\d{4,})\s*,\s*(-?\d{1,3}\.\d{4,})$/);
        if (directCoordsMatch) {
            extractedCoords = {
                lat: parseFloat(directCoordsMatch[1]),
                lng: parseFloat(directCoordsMatch[2])
            };
            isLink = true;
        }

        if (isLink) {
            // Show map button
            mapsBtn.style.display = 'block';
            statusDiv.style.display = 'block';
            
            if (extractedCoords && !isNaN(extractedCoords.lat) && !isNaN(extractedCoords.lng)) {
                // We have coordinates!
                detectedLocation.lat = extractedCoords.lat;
                detectedLocation.lng = extractedCoords.lng;
                detectedLocation.link = address;
                
                document.getElementById('delivery_lat').value = extractedCoords.lat;
                document.getElementById('delivery_lng').value = extractedCoords.lng;
                document.getElementById('delivery_maps_link').value = address;
                
                statusText.innerHTML = '<span style="color: #2e7d32;">✅ Ubicación detectada: ' + extractedCoords.lat.toFixed(6) + ', ' + extractedCoords.lng.toFixed(6) + '</span>';
            } else {
                // Link detected but couldn't extract coords (short link)
                detectedLocation.link = address;
                document.getElementById('delivery_maps_link').value = address;
                
                statusText.innerHTML = '<span style="color: #1976d2;">🔗 Link de Google Maps detectado - Se usará para el delivery</span>';
            }
        } else {
            // Not a maps link, regular address
            mapsBtn.style.display = 'none';
            statusDiv.style.display = 'none';
            detectedLocation = { lat: null, lng: null, link: null };
        }
    }

    /**
     * Open the detected location in Google Maps
     */
    function openMapsLink() {
        const address = document.getElementById('delivery_address').value.trim();
        
        // If we have coordinates, open them directly
        if (detectedLocation.lat && detectedLocation.lng) {
            const url = `https://www.google.com/maps?q=${detectedLocation.lat},${detectedLocation.lng}`;
            window.open(url, '_blank');
        } else if (detectedLocation.link) {
            // Try to open the link directly
            let link = detectedLocation.link;
            if (!link.startsWith('http')) {
                link = 'https://' + link;
            }
            window.open(link, '_blank');
        } else {
            // Search for the address
            const encoded = encodeURIComponent(address);
            window.open(`https://www.google.com/maps/search/${encoded}`, '_blank');
        }
    }

    // Run detection on page load in case there's a pre-filled address
    document.addEventListener('DOMContentLoaded', function() {
        detectGoogleMapsLink();
    });

    async function calculateDelivery() {
        const resultDiv = document.getElementById('delivery-result');
        resultDiv.innerHTML = '<span style="color: grey;">Calculando...</span>';

        try {
            const formData = new FormData();
            const address = document.getElementById('delivery_address').value;
            formData.append('address', address);
            
            // Send coordinates if available
            if (detectedLocation.lat && detectedLocation.lng) {
                formData.append('lat', detectedLocation.lat);
                formData.append('lng', detectedLocation.lng);
            }
            if (detectedLocation.link) {
                formData.append('maps_link', detectedLocation.link);
            }
            
            const response = await fetch('<?= base_url('order/calculate_delivery') ?>', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.status === 'success') {
                resultDiv.innerHTML = `
                    <div style="text-align: right;">
                        <div style="font-size: 1.4rem; font-weight: bold; color: var(--primary);">${data.data.duration_text}</div>
                        <div style="font-size: 0.9rem; color: #666;">Distancia: ${data.data.distance_text}</div>
                    </div>
                `;
            } else {
                resultDiv.innerHTML = `<span style="color: red;">Error: ${data.message}</span>`;
            }
        } catch (e) {
            console.error(e);
            resultDiv.innerHTML = '<span style="color: red;">Error de conexión</span>';
        }
    }

    function togglePaymentDetails() {
        // Get selected value
        const methods = document.querySelectorAll('input[name="payment_method"]');
        let selectedValue;
        for (const rb of methods) {
            if (rb.checked) {
                selectedValue = rb.value;
                break;
            }
        }

        const zelleDetails = document.getElementById('zelle-details');
        const pmDetails = document.getElementById('pago-movil-details');
        
        const zelleHolder = document.getElementById('zelle_holder');
        const zelleRef = document.getElementById('zelle_reference');
        const pmRef = document.getElementById('pm_reference');

        // Reset display
        zelleDetails.style.display = 'none';
        pmDetails.style.display = 'none';
        
        // Reset required
        zelleHolder.required = false;
        zelleRef.required = false;
        pmRef.required = false;

        if (selectedValue === 'zelle') {
            zelleDetails.style.display = 'block';
            zelleHolder.required = true;
            zelleRef.required = true;
        } else if (selectedValue === 'pago_movil') {
            pmDetails.style.display = 'block';
            pmRef.required = true;
        }
    }
</script>

<?= $this->endSection() ?>
