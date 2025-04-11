<?php $v->layout('_theme'); ?>
<?php if(Session()->USER_ID): ?>
<form action="<?=url('admin.post.update.sector', ['sector' => $currentSector->Framework_Sector]); ?>" method="post" id="form-editsector">
<div class="row">
    <input type="hidden" name="csrf_token" value="<?=csrf_token(); ?>">
    <div class="col-12 col-sm-8 mb-2">
        <div class="box">
            <div class="box-header">
                <h4>Editar Setor: <?=$currentSector->Name; ?></h4>
            </div>
            <div class="box-content p-2">
                <div class="form-group">
                    <label for="name" class="form-label required">Nome do Setor:</label>
                    <input type="text" name="name" id="name" class="form-control" autocomplete="off" value="<?=$currentSector->Name; ?>" required>
                </div>
            </div>
            <div class="box-header border-top">Permissões:</div>
            <div class="box-content p-2">   
                <div class="row">
                    <div class="col">
                        <div class="form-check">
                            <input type="checkbox" name="rule_read" id="rule_read" class="form-check-input" <?=($rule->Rule_Read === 'S' ? 'checked' : null); ?>>
                            <label for="rule_read" class="form-check-label">Visualizar</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="rule_create" id="rule_create" class="form-check-input" <?=($rule->Rule_Create === 'S' ? 'checked' : null); ?>> 
                            <label for="rule_create" class="form-check-label">Criar</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-check">
                            <input type="checkbox" name="rule_update" id="rule_update" class="form-check-input" <?=($rule->Rule_Update === 'S' ? 'checked' : null); ?>>
                            <label for="rule_update" class="form-check-label">Editar</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="rule_delete" id="rule_delete" class="form-check-input" <?=($rule->Rule_Delete === 'S' ? 'checked' : null); ?>>
                            <label for="rule_delete" class="form-check-label">Deletar</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="btn btn-danger my-2" id="btn-editsector">Editar Setor</button>
        <?php if($message): ?>
            <?=$message; ?>
        <?php endif; ?>
    </div>
    <?=$v->insert('account/sidebar'); ?>
</div>
</form>
<?php $v->start('javascript'); ?>
<script type="text/javascript">
    onSubmit('form-editsector', 'btn-editsector');
</script>
<?php $v->end(); ?>

<?php endif; ?>