<?php $v->layout('_theme'); ?>
<?php if (Session()->has('USER_ID')) : ?>
    <form action="<?= url('ticket.store', ['user' => $user->Framework_User]) ?>" method="post" enctype="multipart/form-data" id="create-form">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-4 col-xl-4 col-xxl-4">
                <?= $v->insert('create/sidebar'); ?>
            </div>
            <div class="col-12 col-sm-12 col-md-8 col-xl-8 col-xxl-8">
                <?= $v->insert('create/content'); ?>
            </div>
        </div>
    </form>
    <?php $v->start('javascript'); ?>
    <script type="text/javascript">
        (function() {
            categories();
            wordCount('message', counter);
            onSubmit('create-form', 'create-btn');
            employee('employee');
        })();
    </script>
    <?php $v->end(); ?>
<?php else : ?>
    <div class="alert alert-danger" role="alert">
        <i class="fas fa-exclamation-circle"></i> Você precisa está logado para visualizar está página.
    </div>
<?php endif; ?>