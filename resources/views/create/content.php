<div class="box my-2">
    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
    <div class="box-header">Informações do Chamado</div>
    <div class="box-content">
        <label for="title" class="form-label required">Assunto selecionado:</label>
        <input type="text" name="title" id="title" placeholder="Preenchimento automático" class="form-control" readonly required value="<?= old('title'); ?>">
    </div>
    <div id="custom-fields-block" class="box-content d-none">
        <div id="custom-fields"></div>
    </div>
    <div class="box-content">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <label for="message" class="form-label required">Mensagem:</label>
            <small>Máximo de Caracteres: <span id="counter">3000</span>/3000</small>
        </div>
        <div class="form-floating">
            <textarea name="message" id="message" maxlength="3000" style="height: 250px;" class="form-control" required><?= old('message'); ?></textarea>
            <label for="floatingTextarea">Seja o máximo possível descritivo na mensagem:</label>
        </div>
    </div>
    <div class="box-content mb-2">
        <label for="attachment" class="form-label">Desejar anexar arquivos? <small>(opcional)</small></label>
        <input type="file" class="form-control" name="attachment[]" id="attachment" multiple>
        <small>Apenas arquivos no formato: <strong>JPEG/JPG/PNG, EXCEL ou PDF.</strong></small>
    </div>
</div>
<button class="btn btn-danger btn-sm-lg mb-2" id="create-btn" role="button">Criar Chamado</button>
<?php if ($message) : ?>
    <?= $message; ?>
<?php endif; ?>