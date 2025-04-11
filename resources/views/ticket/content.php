<div class="box my-2">
    <div class="box-header">
        <h4 class="py-0 my-0">#<?= $ticket->ID_ARTIA == 0 ? $ticket->TICKET_ID : $ticket->ID_ARTIA; ?> - <?= html_entity_decode($ticket->TITULO); ?></h4>
    </div>
    <div class="box-content">
        <div class="d-flex justify-content-between align-items-center mb-2" style="color: #899199;">
            <small>Histórico interações:</small>
            <?php if ($ticket->ATUALIZACAO) : ?>
                <small>Última atualização: <?= date('d/m á\s H:i', strtotime($ticket->ATUALIZACAO)); ?></small>
            <?php endif; ?>
        </div>
        <?php $decode = json_decode($ticket->MENSAGEM); ?>
        <div class="d-flex align-items-start gap-2">
            <img class="avatar" src="<?= asset('storage/avatar/'.mb_strtoupper($ticket->USUARIO[0]).'.png'); ?>" alt="avatar.png">
            <div class="d-flex flex-column flex-wrap">
                <div class="d-flex justify-content-start align-items-center gap-2">
                    <strong><?= mb_convert_case($ticket->USUARIO, MB_CASE_TITLE, 'UTF-8'); ?></strong>
                    <div class="d-flex justify-content-start flex-fill gap-2">
                        <small class="d-none d-sm-block" style="color: #899199;">
                            <?= mb_convert_case($ticket->SETOR, MB_CASE_TITLE, 'UTF-8'); ?>
                        </small>
                        <small style="color: #899199;"><?= date('d/m à\s H:i:s', strtotime($ticket->INICIALIZACAO)); ?></small>
                    </div>
                </div>
                <p class="text-break"><?= nl2br(html_entity_decode($decode->MESSAGE)); ?></p>
            </div>
        </div>
        <?php if ($commits) : ?>
            <?php foreach ($commits as $commit) : ?>
                <div class="d-flex align-items-start gap-2 mb-3">
                    <img class="avatar" src="<?= asset('storage/avatar/'.mb_strtoupper($commit->USUARIO[0]).'.png'); ?>" alt="avatar.png">
                    <div class="d-flex flex-column flex-wrap w-100">
                        <div class="d-flex justify-content-start align-items-center gap-2">
                            <strong><?= mb_convert_case($commit->USUARIO, MB_CASE_TITLE, 'UTF-8'); ?></strong>
                            <div class="d-flex justify-content-start flex-fill gap-2">
                                <small class="d-none d-sm-block" style="color: #899199;">
                                    <?= mb_convert_case($commit->SETOR, MB_CASE_TITLE, 'UTF-8'); ?>
                                </small>
                                <small style="color: #899199;"><?= date('d/m à\s H:i:s', strtotime($commit->INICIALIZACAO)); ?></small>
                            </div>
                        </div>
                        <p class="text-break py-0 my-0"><?= nl2br(html_entity_decode($commit->COMENTARIO)); ?></p>
                        <?php if ($commit->ENDERECO) : ?>
                            <?php $explode = explode('&', $commit->ENDERECO);
                            $clear = array_filter($explode); ?>
                            <div class="d-flex flex-wrap mb-2">
                                <?php foreach ($clear as $item) : ?>

                                    <a href="<?=  defaultUrl().$item; ?>" class="text-decoration-none" style="color: #899199;" target="_blank" rel="noopener noreferrer" style="margin-right: .5rem;">
                                        <small>Visualizar anexo</small>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php if ($attachments) : ?>
            <?php foreach ($attachments as $attachment) : ?>
                
                <div class="d-flex flex-column mb-2">
                    <a class="text-reset text-decoration-none" href="<?= preg_match('/^https?:\/\/[^\s\/$.?#].[^\s]*$/i', $attachment->ENDERECO) === 1 ? $attachment->ENDERECO : defaultUrl().$attachment->ENDERECO; ?>" target="_blank" rel="noopener noreferrer">
                        <h6 class="py-0 my-0">Visualizar anexo</h6>
                    </a>
                    <small style="color: #899199;">
                        Enviado em: <?= date('d/m á\s H:i', strtotime($attachment->ENVIADO)); ?>
                    </small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php if ($ticket->ESTADO == 1) : ?>
            <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
            <label for="message" class="form-label required">Responder:</label>
            <div class="form-floating mb-2">
                <textarea name="message" id="message" maxlength="3000" style="height: 150px;" class="form-control" required><?= old('message'); ?></textarea>
                <label for="floatingTextarea">Seja o máximo possível descritivo na mensagem:</label>
            </div>
            <div class="mb-2">
                <label for="attachment" class="form-label">Desejar anexar arquivos? <small>(opcional)</small></label>
                <input type="file" class="form-control" name="files[]" id="files" multiple>
                <small style="color: #899199;">Apenas arquivos no formato: <strong>JPEG/JPG/PNG, EXCEL e PDF.</strong></small>
            </div>
            <button class="btn btn-danger" id="commit-btn">Responder</button>
        <?php endif; ?>
    </div>
</div>
<?php if ($message) : ?>
    <?= $message; ?>
<?php endif; ?>