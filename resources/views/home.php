<?php $v->layout('_theme'); ?>
<?php if (Session()->has('USER_ID')) : ?>
    <?php if ($user->State === 'N') : ?>
        <div class="alert alert-danger" role="alert">
            Você não tem permissão para Visualizar está pagina.
        </div>
    <?php else : ?>
        <!-- TicketList -->
        <?= $v->insert('home/list'); ?>
        <!-- TicketList -->
    <?php endif; ?>
<?php else : ?>
    <!-- Login -->
    <?= $v->insert('home/login'); ?>
    <!-- Login -->
<?php endif; ?>