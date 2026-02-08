<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura <?= $order['order_id'] ?></title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; background: #f5f5f5; color: #333; }
        .invoice-container { max-width: 800px; margin: 2rem auto; background: white; padding: 3rem; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header { display: flex; justify-content: space-between; margin-bottom: 3rem; border-bottom: 2px solid #eee; padding-bottom: 2rem; }
        .company-info h1 { margin: 0; color: #FF5722; font-size: 2rem; }
        .company-info p { margin: 0.2rem 0; color: #666; font-size: 0.9rem; }
        .invoice-details { text-align: right; }
        .invoice-details h2 { margin: 0; color: #333; }
        .bill-to { margin-bottom: 2rem; }
        .bill-to h3 { margin-bottom: 0.5rem; color: #555; font-size: 1.1rem; }
        .bill-to p { margin: 0.2rem 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 2rem; }
        th { text-align: left; padding: 1rem; background: #f9f9f9; color: #555; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.05em; }
        td { padding: 1rem; border-bottom: 1px solid #eee; }
        .totals { margin-left: auto; width: 300px; }
        .total-row { display: flex; justify-content: space-between; padding: 0.5rem 0; }
        .total-row.final { font-weight: bold; font-size: 1.2rem; border-top: 2px solid #333; padding-top: 1rem; margin-top: 0.5rem; }
        .footer { margin-top: 4rem; text-align: center; color: #999; font-size: 0.8rem; border-top: 1px solid #eee; padding-top: 2rem; }
        .btn-download { display: inline-block; background: #333; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 20px; cursor: pointer; border: none; font-size: 1rem; transition: background 0.3s; }
        .btn-download:hover { background: #555; }
        
        @media print {
            body { background: white; }
            .invoice-container { margin: 0; box-shadow: none; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="invoice-container" id="invoice">
        <div class="header">
            <div class="company-info">
                <h1>Kiosko Digital</h1>
                <p>RIF: J-12345678-9</p>
                <p>Calle Principal, Local 1</p>
                <p>La Guaira, Venezuela</p>
                <p>hola@kioskopizza.com</p>
            </div>
            <div class="invoice-details">
                <h2>FACTURA</h2>
                <p>#<?= $order['order_id'] ?></p>
                <p>Fecha: <?= date('d/m/Y', strtotime($order['date'])) ?></p>
            </div>
        </div>

        <div class="bill-to">
            <h3>Facturar a:</h3>
            <?php if($order['user']): ?>
                <p><strong><?= esc($order['user']['name']) ?></strong></p>
                <p><?= esc($order['user']['email']) ?></p>
                <?php if(isset($order['user']['phone'])): ?><p>Tel: <?= esc($order['user']['phone']) ?></p><?php endif; ?>
            <?php else: ?>
                <p>Cliente Invitado</p>
            <?php endif; ?>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">Descripción</th>
                    <th style="width: 15%; text-align: center;">Cant.</th>
                    <th style="width: 15%; text-align: right;">Precio</th>
                    <th style="width: 20%; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($order['cart'] as $item): ?>
                <tr>
                    <td>
                        <strong><?= esc($item['name']) ?></strong>
                        <?php if(!empty($item['extras'])): ?>
                        <div style="font-size: 0.85rem; color: #777;">
                            <?= implode(', ', $item['extras']) ?>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;"><?= $item['qty'] ?></td>
                    <td style="text-align: right;">$<?= number_format($item['price'], 2) ?></td>
                    <td style="text-align: right;">$<?= number_format($item['price'] * $item['qty'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>$<?= number_format($order['total'], 2) ?></span>
            </div>
            <!-- Example Tax
            <div class="total-row">
                <span>IVA (16%):</span>
                <span>$<?= number_format($order['total'] * 0.16, 2) ?></span>
            </div>
            -->
            <div class="total-row final">
                <span>Total:</span>
                <span>$<?= number_format($order['total'], 2) ?></span>
            </div>
        </div>

        <div style="margin-top: 3rem; padding: 1rem; background: #f9f9f9; border-radius: 5px;">
            <p style="margin: 0; font-size: 0.9rem;">
                <strong>Método de Pago:</strong> 
                <?= ucwords(str_replace('_', ' ', $order['payment']['type'])) ?>
                <?php if($order['payment']['type'] == 'zelle'): ?>
                    (Ref: <?= $order['payment']['reference'] ?>)
                <?php elseif($order['payment']['type'] == 'pago_movil'): ?>
                    (Ref: <?= $order['payment']['reference'] ?>)
                <?php endif; ?>
            </p>
        </div>

        <div class="footer">
            <p>¡Gracias por su compra!</p>
        </div>
    </div>

    <div style="text-align: center;" class="no-print">
        <button id="downloadBtn" class="btn-download" onclick="generatePDF()">
            <i class="fas fa-download"></i> Descargar PDF
        </button>
        <br><br>
        <a href="<?= base_url('order/confirmation') ?>" style="color: #666;">Volver</a>
    </div>

    <script>
        function generatePDF() {
            const element = document.getElementById('invoice');
            const opt = {
                margin:       0.5,
                filename:     'Factura_<?= $order['order_id'] ?>.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            
            // Generate valid PDF
            html2pdf().set(opt).from(element).save();
        }
    </script>
</body>
</html>
