<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container section" style="text-align: center; max-width: 600px;">
    
    <div style="background: white; padding: 3rem; border-radius: var(--radius); box-shadow: var(--shadow-md);">
        <div style="width: 80px; height: 80px; background: #E8F5E9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
            <i class="fas fa-check" style="font-size: 2.5rem; color: var(--success);"></i>
        </div>
        
        <h1 style="font-family: var(--font-heading); margin-bottom: 1rem;">¡Pedido Confirmado!</h1>
        <?php $orderId = $order['order_id'] ?? 'PENDIENTE'; ?>
        <p class="text-muted" style="margin-bottom: 1rem;">Tu orden #<?= $orderId ?> está siendo preparada.</p>
        
        <div class="alert-box" style="background: #FFF3CD; border: 1px solid #FFEEBA; color: #856404; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; text-align: left;">
            <p style="margin: 0; font-weight: 600;"><i class="fas fa-exclamation-triangle"></i> Importante:</p>
            <p style="margin: 0.5rem 0 0;">Para asegurarnos de procesar tu pedido lo antes posible, <strong>es necesario que reportes tu pago</strong> enviándonos el comprobante vía WhatsApp. Sin este paso, no podremos iniciar la preparación.</p>
        </div>

        <?php 
            $orderId = $order['order_id'] ?? 'PENDIENTE';
            $ref = $order['payment']['reference'] ?? 'S/R';
            $method = $order['payment']['type'] ?? 'Desconocido';
            $total = number_format($order['total'], 2);
            $invoiceLink = base_url('order/invoice');
            
            $msg = "Hola, envío soporte de pago.\n";
            $msg .= "*Orden:* #{$orderId}\n";
            $msg .= "*Monto:* \${$total}\n";
            $msg .= "*Método:* " . ucfirst(str_replace('_', ' ', $method)) . "\n";
            $msg .= "*Referencia:* {$ref}\n";
            $msg .= "*Factura:* {$invoiceLink}";
            
            $waUrl = "https://wa.me/584129203359?text=" . urlencode($msg);
        ?>

        <!-- Primary Action -->
        <div style="margin-bottom: 2rem;">
            <a href="<?= $waUrl ?>" target="_blank" class="btn-cta pulse-button" style="background: #25D366; color: white; display: block; width: 100%; padding: 1.2rem; font-size: 1.3rem; border-radius: 50px; text-decoration: none; box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4); border:none;">
                <i class="fab fa-whatsapp" style="font-size: 1.5rem; margin-right: 10px;"></i> Reportar Pago Ahora
            </a>
        </div>

        <!-- Secondary Actions -->
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="<?= base_url('order/tracking') ?>" class="btn-secondary" style="background: var(--surface); color: var(--primary); border: 1px solid #ddd; padding: 0.8rem 1.5rem; border-radius: 8px; text-decoration: none; display: flex; align-items: center; gap: 0.5rem; flex: 1; justify-content: center; min-width: 200px;">
                <i class="fas fa-map-marked-alt"></i> Rastrear Pedido
            </a>
            
            <a href="<?= base_url('order/invoice') ?>" target="_blank" class="btn-secondary" style="background: var(--surface); color: var(--text-light); border: 1px solid #ddd; padding: 0.8rem 1.5rem; border-radius: 8px; text-decoration: none; display: flex; align-items: center; gap: 0.5rem; flex: 1; justify-content: center; min-width: 200px;">
                <i class="fas fa-file-invoice"></i> Ver Factura
            </a>
        </div>

        <style>
            @keyframes pulse-green {
                0% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7); }
                70% { box-shadow: 0 0 0 15px rgba(37, 211, 102, 0); }
                100% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0); }
            }
            .pulse-button {
                animation: pulse-green 2s infinite;
            }
            .btn-secondary:hover {
                background: #f1f1f1 !important;
                border-color: #ccc !important;
            }
        </style>
        
        <hr style="border: 0; border-top: 1px solid #eee; margin: 2rem 0;">

        <h3>¿Qué te pareció la experiencia?</h3>
        <p class="text-muted" style="margin-bottom: 1.5rem;">Tu opinión nos ayuda a mejorar.</p>

        <form action="<?= base_url('order/submit_review') ?>" method="post">
            <div class="rating-group" style="display: flex; justify-content: center; gap: 0.5rem; margin-bottom: 1.5rem; flex-direction: row-reverse;">
                <!-- Simple CSS Star Rating (Reverse order for hover effect logic) -->
                <?php for($i=5; $i>=1; $i--): ?>
                <input type="radio" name="rating" id="star<?= $i ?>" value="<?= $i ?>" style="display: none;">
                <label for="star<?= $i ?>" style="font-size: 2rem; color: #ddd; cursor: pointer; transition: color 0.2s;">
                    <i class="fas fa-star"></i>
                </label>
                <?php endfor; ?>
                <style>
                    .rating-group input:checked ~ label,
                    .rating-group label:hover,
                    .rating-group label:hover ~ label {
                        color: var(--accent) !important;
                    }
                </style>
            </div>

            <textarea name="comment" placeholder="Escribe tu comentario aquí..." style="width: 100%; height: 100px; padding: 1rem; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; margin-bottom: 1.5rem; resize: none;"></textarea>

            <button type="submit" class="btn-cta" style="width: 100%;">Enviar Reseña</button>
        </form>

        <div style="margin-top: 2rem;">
            <a href="<?= base_url() ?>" style="color: var(--primary); font-weight: 600;">Volver al Inicio</a>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
