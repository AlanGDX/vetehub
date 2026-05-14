<?php $__env->startSection('title', 'Bienvenido a VeteHub'); ?>

<?php $__env->startSection('content'); ?>
    <h1>🎉 ¡Bienvenido a VeteHub!</h1>

    <p>Hola <strong><?php echo e($client->name); ?></strong>,</p>

    <p>Nos alegra informarte que has sido registrado exitosamente en nuestra plataforma de gestión veterinaria. A partir de ahora podrás recibir notificaciones y recordatorios sobre las citas de tus mascotas.</p>

    <div class="info-card">
        <table class="info-table">
            <tr>
                <td>👤 Nombre</td>
                <td><?php echo e($client->name); ?></td>
            </tr>
            <tr>
                <td>📧 Email</td>
                <td><?php echo e($client->email); ?></td>
            </tr>
            <tr>
                <td>📱 Teléfono</td>
                <td><?php echo e($client->phone); ?></td>
            </tr>
            <?php if($client->address): ?>
            <tr>
                <td>📍 Dirección</td>
                <td><?php echo e($client->address); ?><?php echo e($client->city ? ', ' . $client->city : ''); ?></td>
            </tr>
            <?php endif; ?>
        </table>
    </div>

    <p><strong>¿Qué puedes esperar?</strong></p>
    <p>📅 Recordatorios de citas programadas<br>
       ✅ Confirmaciones de nuevas citas<br>
       📋 Notificaciones sobre cambios en tus citas</p>

    <div class="divider"></div>

    <p>Si tus datos no son correctos, comunícate con la clínica para actualizarlos.</p>

    <p style="color: #94a3b8; font-size: 13px; margin-top: 24px;">
        ¡Gracias por confiar en nosotros para el cuidado de tus mascotas! 🐾
    </p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Repos\vetehub\resources\views/emails/client-welcome.blade.php ENDPATH**/ ?>