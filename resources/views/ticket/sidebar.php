<div class="box my-2">
    <div class="box-content">
        <div class="d-flex align-items-center gap-2 mb-2">
            <img class="avatar" src="<?= asset(sprintf('storage/avatar/%s.png', $ticket->PROC_NOME[0])); ?>" alt="<?= sprintf('%s.jpg', $ticket->PROC_NOME[0]) ?>">
            <div>
                <small style="color:#899199;">Responsável:</small>
                <h6 class="py-0 my-0"><?= $ticket->PROC_NOME ?></h6>
            </div>
        </div>
        <div class="d-flex flex-column">
            <div class="border-bottom">
                <small style="color:#899199;" class="required">Departamento responsável:</small>
                <h6 class="py-0 my-0 pb-1"><?= mb_convert_case($ticket->DEPARTAMENTO, MB_CASE_TITLE, 'UTF-8'); ?></h6>
            </div>
            <div class="border-bottom">
                <small style="color:#899199;">Categoria:</small>
                <h6 class="py-0 my-0"><?= mb_convert_case($ticket->CATEGORIA, MB_CASE_TITLE, 'UTF-8'); ?></h6>
            </div>
            <div class="border-bottom">
                <small style="color:#899199;">Criado em:</small>
                <h6 class="py-0 my-0"><?= date('d/m à\s H:i', strtotime($ticket->INICIALIZACAO)); ?></h6>
            </div>
            <div class="border-bottom">
                <small class="required" style="color:#899199;">Prazo estimado:</small>
                <h6 class="py-0 my-0"><?= date('d/m', strtotime($ticket->PRAZO_ARTIA)); ?></h6>
            </div>
            <div class="border-bottom">
                <small class="required" style="color:#899199;">Integrado:</small>
                <h6 class="py-0 my-0"><?= ($ticket->ID_ARTIA ? 'Sim' : 'Não'); ?></h6>
            </div>
            <?php if ($ticket->ESTADO == 2) : ?>
                <div class="border-bottom">
                    <small style="color:#899199;">Finalizado em:</small>
                    <?php if (is_null($ticket->FINALIZACAO_ARTIA)) : ?>
                        <h6 class="py-0 my-0"><?= date('d/m à\s H:i', strtotime($ticket->FINALIZACAO)); ?></h6>
                    <?php else : ?>
                        <h6 class="py-0 my-0"><?= date('d/m à\s H:i', strtotime($ticket->FINALIZACAO_ARTIA)); ?></h6>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="d-flex align-items-center gap-2 my-2">
            <img class="avatar" src="<?= asset(sprintf('storage/avatar/%s.png', mb_strtoupper($ticket->USUARIO[0]))); ?>" alt="<?= sprintf('%s.jpg', mb_strtoupper($ticket->USUARIO[0])) ?>">
            <div>
                <small style="color:#899199;">Cliente:</small>
                <h6 class="py-0 my-0"><?= mb_strtoupper($ticket->USUARIO) ?></h6>
            </div>
        </div>
        <div class="d-flex flex-column">
            <div class="border-bottom">
                <small style="color:#899199;">Acesso Remoto:</small>
                <h6 class="py-0 my-0"><?= mb_convert_case($ticket->COMPUTADOR, MB_CASE_TITLE, 'UTF-8'); ?></h6>
            </div>
            <?php $decode = json_decode($ticket->MENSAGEM); ?>
            <?php if (isset($decode->FIELDS)) : ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($decode->FIELDS as $field) : ?>
                        <div class="border-bottom">
                            <small style="color:#899199;"><?= mb_convert_case($field->FIELD_NAME, MB_CASE_TITLE, 'UTF-8'); ?>:</small>
                            <h6 class="py-0 my-0">
                                <?= mb_convert_case(html_entity_decode($field->FIELD_VALUE ? $field->FIELD_VALUE : 'Não informado'), MB_CASE_TITLE, 'UTF-8'); ?>
                            </h6>
                        </div>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
    <?php if ($ticket->ESTADO == 1) : ?>
        <div class="box-content bg-success text-light">
            Aberto
        </div>
    <?php else : ?>
        <div class="box-content bg-danger text-light">
            Fechado
        </div>
    <?php endif; ?>
</div>