<?php $v->layout('_theme'); ?>
<?php if (Session()->USER_ID) : ?>
    <?php if ($ticket) : ?>
        <form action="<?= url('commit.store', ['id' => $ticket->TICKET_CHAMADO]); ?>" method="POST" enctype="multipart/form-data" id="commit-form">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-4 col-xl-4 col-xxl-4">
                    <?= $v->insert('ticket/sidebar'); ?>
                </div>
                <div class="col-12 col-sm-12 col-md-8 col-xl-8 col-xxl-8">
                    <?= $v->insert('ticket/content'); ?>
                </div>
            </div>
        </form>
        <?php $v->start('javascript'); ?>
        <script type="text/javascript">
            onSubmit('commit-form', 'commit-btn');
        </script>
        <?php $v->end(); ?>
    <?php else : ?>
        <div class="alert alert-danger" role="alert">
            <i class="fas fa-exclamation-circle"></i> Chamado não encontrado ou é invalido.
        </div>
    <?php endif; ?>
<?php else : ?>
    <div class="alert alert-danger" role="alert">
        <i class="fas fa-exclamation-circle"></i> Você precisa está logado para visualizar está página.
    </div>
<?php endif; ?>