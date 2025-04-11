<?php $v->layout('_theme'); ?>
<div class="row">
    <div class="col-12">
        <?php if ($tickets) : ?>
            <div class="box">
                <div class="box-header">Lista de Chamados</div>
                <div class="box-content p-2">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($tickets as $ticket) : ?>
                            <li class="list-group-item d-flex flex-wrap flex-column">
                                <a class="text-reset text-decoration-none" href="<?= url('ticket.show', ['id' => $ticket->TICKET_CHAMADO]) ?>">
                                    <div class="d-flex flex-wrap flex-column">
                                        <div>
                                            #<?= $ticket->ID_ARTIA == 0 ? $ticket->TICKET_ID : $ticket->ID_ARTIA; ?> - <?= mb_convert_case(html_entity_decode($ticket->TITULO), MB_CASE_TITLE, 'UTF-8'); ?>
                                            <span class="fs-7" style="color: #8c8d8f;"><?= ($ticket->ESTADO == 1 ? 'Aberto' : 'Finalizado'); ?></span>
                                        </div>
                                        <span class="fs-7" style="color: #8c8d8f;">Aberto em: <?= date('d/m/Y á\s H:i:s', strtotime($ticket->INICIALIZACAO)); ?></span>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
    </div>
<?php else : ?>
    <div class="alert alert-danger" role="alert">
        Nenhum chamado encontrado.
    </div>
<?php endif; ?>
</div>