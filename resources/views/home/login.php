<div class="row align-items-center justify-content-center vh-100">
    <div class="col-12 col-sm-12 col-md-4 col-xl-4 col-xxl-4">
        <div class="text-center">
            <h4 class="py-0 my-0">Olá, Bem-vindo(a) 🖐️</h4>
            <p>Digite o seu login e senha para continuar!</p>
        </div>
        <div class="box mb-2">
            <div class="box-content">
                <form method="post" action="<?= url('auth.sigin'); ?>" id="form-login">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
                    <div class="mb-2">
                        <label for="username" class="form-label required">Login:</label>
                        <input type="text" name="username" id="username" class="form-control" value="<?= old('username'); ?>" autocomplete="off" required>
                    </div>
                    <div class="mb-2">
                        <label for="password" class="form-label required">Senha:</label>
                        <input type="password" name="password" id="password" class="form-control" autocomplete="off" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-danger my-2" role="button" id="btn-login">Entrar</button>
                    </div>
                </form>
            </div>
        </div>
        <?php if ($message) : ?>
            <?= $message; ?>
        <?php endif; ?>
    </div>
</div>
</div>
<?php $v->start('javascript'); ?>
<script type="text/javascript">
    onSubmit('form-login', 'btn-login');
</script>
<?php $v->end(); ?>