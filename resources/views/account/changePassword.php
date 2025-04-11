<?php $v->layout('_theme'); ?>
<form action="<?=url('account.store.password', ['user' => $user->Framework_User]); ?>" method="post" id="form-changepassword">
    <div class="row">
        <input type="hidden" name="csrf_token" value="<?=csrf_token(); ?>">
        <!-- Content -->
        <div class="col-12 col-sm-8">
            <div class="box">
                <div class="box-header">
                    <h4>Alterar Senha</h4>
                </div>
                <div class="box-content p-2">
                    <div class="alert alert-info" role="alert">
                        Não compartilhe sua senha com ninguém!
                    </div>
                </div>
                <div class="box-content p-2 border-top">
                    <div class="form-group">
                        <label for="oldPassword" class="form-label required">Senha atual:</label>
                        <input type="password" name="oldPassword" id="oldPassword" autocomplete="off"
                            class="form-control" required>
                    </div>
                </div>
                <div class="box-content p-2 border-top">
                    <div class="form-group">
                        <label for="newPassword" class="form-label required">Nova senha:</label>
                        <input type="password" name="newPassword" id="newPassword" autocomplete="off"
                            class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword" class="form-label required">Digite novamente:</label>
                        <input type="password" name="confirmPassword" id="confirmPassword" autocomplete="off"
                            class="form-control" required>
                    </div>
                </div>
            </div>
            <button class="btn btn-danger my-2" id="btn-changepassword">Alterar Senha</button>
            <?php if ($message) : ?>
                <?=$message; ?>
            <?php endif; ?>
        </div>
        <!-- Content -->
        <!-- Sidebar -->
            <?=$v->insert('account/sidebar'); ?>
        <!-- Sidebar -->
    </div>
</form>
<?php $v->start('javascript'); ?>
<script type="text/javascript">
    onSubmit('form-changepassword', 'btn-changepassword');
</script>
<?php $v->end(); ?>
