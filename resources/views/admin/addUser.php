<?php $v->layout('_theme');?>
<form action="<?=url('admin.post.create.user'); ?>" method="post" id="form-adduser">
    <?php if (Session()->has('USER_ID')) :  ?>
    <div class="row">
        <div class="col-12 col-sm-8">
            <div class="box mb-2">
                <div class="box-header">
                    <h4>Adicionar Usuário</h4>
                </div>
                <div class="box-content p-2">
                    <input type="hidden" name="csrf_token" value="<?=csrf_token(); ?>">
                    <div class="form-group mb-1">
                        <label for="name" class="form-label required">Nome do Usuário:</label>
                        <input type="text" name="name" id="name" class="form-control" autocomplete="off"
                            value="<?=old('name');?>" required>
                    </div>
                    <div class="form-group mb-1">
                        <label for="username" class="form-label required">Username:</label>
                        <input type="text" name="username" id="username" class="form-control" autocomplete="off"
                            value="<?=old('username');?>" required>
                    </div>
                    <div class="form-group mb-1">
                        <label for="password" class="form-label required">Senha:</label>
                        <input type="password" name="password" id="password" class="form-control" autocomplete="off"
                            required>
                    </div>
                    <div class="form-group mb-1">
                        <label for="email" class="form-label required">Endereço de E-mail:</label>
                        <input type="email" name="email" id="email" class="form-control" autocomplete="off"
                            value="<?=old('email');?>" required>
                    </div>
                </div>
                <div class="box-content p-2 border-top">
                    <div class="form-group">
                        <label for="sector" class="form-label required">Setor:</label>
                        <select name="sector" id="sector" class="form-select" required>
                            <?php if ($sectors) : ?>
                                <?php foreach ($sectors as $sector) : ?>
                            <option value="<?=$sector->Framework_Sector; ?>"
                                    <?=(old('sections') === $sector->Framework_Sector ? 'selected' : null);?>>
                                    <?=mb_convert_case($sector->Name, MB_CASE_TITLE, 'UTF-8'); ?>
                            </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>
            <button class="btn btn-danger mb-2" id="btn-adduser">Criar novo Usuário</button>
            <?php if ($message) : ?>
                <?=$message; ?>
            <?php endif; ?>
        </div>
        <?=$v->insert('account/sidebar'); ?>
</form>
    <?php endif; ?>
<?php $v->start('javascript'); ?>
<script type="text/javascript">
    onSubmit('form-adduser', 'btn-adduser');
</script>
<?php $v->end(); ?>
