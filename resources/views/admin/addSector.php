<?php $v->layout('_theme'); ?>
<?php if(Session()->USER_ID): ?>
<form action="<?=url('admin.post.create.sector'); ?>" method="post" id="form-addsector">
<div class="row">
    <input type="hidden" name="csrf_token" value="<?=csrf_token(); ?>">
    <div class="col-12 col-sm-8 mb-2">
        <div class="box">
            <div class="box-header">
                <h4>Adicionar Setor</h4>
            </div>
            <div class="box-content p-2">
                <div class="form-group">
                    <label for="name" class="form-label required">Nome do Setor:</label>
                    <input type="text" name="name" id="name" class="form-control" autocomplete="off" value="<?=old('name'); ?>" required>
                </div>
            </div>
            <div class="box-header border-top">Permissoes</div>
            <div class="box-content p-2">   
                <div class="row">
                    <div class="col">
                        <div class="form-check">
                            <input type="checkbox" name="rule_read" id="rule_read" class="form-check-input" <?=(old('rule_read') == 'on' ? 'checked' : null);?>>
                            <label for="rule_read" class="form-check-label">Visualizar</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="rule_create" id="rule_create" class="form-check-input" <?=(old('rule_create') == 'on' ? 'checked' : null);?>>
                            <label for="rule_create" class="form-check-label">Criar</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-check">
                            <input type="checkbox" name="rule_update" id="rule_update" class="form-check-input" <?=(old('rule_update') == 'on' ? 'checked' : null);?>>
                            <label for="rule_update" class="form-check-label">Editar</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="rule_delete" id="rule_delete" class="form-check-input" <?=(old('rule_delete') == 'on' ? 'checked' : null);?>>
                            <label for="rule_delete" class="form-check-label">Deletar</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="btn btn-danger my-2" id="btn-addsector">Criar Setor</button>
        <?php if($message): ?>
            <?=$message; ?>
        <?php endif; ?>
    </div>
    <?=$v->insert('account/sidebar'); ?>
</div>
</form>
<?php $v->start('javascript'); ?>
<script type="text/javascript">
    onSubmit('form-addsector', 'btn-addsector');
</script>
<?php $v->end(); ?>

<?php endif; ?>