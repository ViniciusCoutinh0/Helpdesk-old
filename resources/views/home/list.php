<div class="row mb-2">
    <div class="col-12 mb-2">
        <div class="box">
            <div class="box-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="py-0 my-0">Chamados Abertos</h5>
                    <a href="<?= url('app.list.state', ['user' => $user->Framework_User, 'state' => 1]); ?>" class="btn btn-danger">Ver Todos Chamados em Aberto</a>
                </div>
            </div>
            <div class="box-content">
                <?php if ($open) : ?>
                    <?php foreach ($open as $item) : ?>
                        <div class="box-inline">
                            <div class="content">
                                <div class="detail">
                                    <img src="<?= asset('storage/avatar/'.mb_strtoupper($user->Username[0]).'.png'); ?>" alt="" class="avatar">
                                    <div class="group">
                                        <span>Nº.:</span>
                                        <?= $item->ID_ARTIA == 0 ? $item->TICKET_ID : $item->ID_ARTIA; ?>
                                    </div>
                                    <div class="group">
                                        <span>Aberto em:</span>
                                        <?= date('d/m/y à\s H:i', strtotime($item->INICIALIZACAO)); ?>
                                    </div>
                                    <div class="group">
                                        <span>Departamento responsável:</span>
                                        <?= mb_convert_case($item->DEPARTAMENTO, MB_CASE_TITLE, 'UTF-8'); ?>
                                    </div>
                                    <div class="group">
                                        <span>Assunto do Chamado:</span>
                                        <?= mb_convert_case(html_entity_decode($item->TITULO), MB_CASE_TITLE, 'UTF-8'); ?>
                                    </div>
                                </div>
                                <a href="<?= url('ticket.show', ['id' => $item->TICKET_CHAMADO]); ?>" class="btn btn-sm btn-primary">Visualizar</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="alert alert-info" role="alert">
                        Nenhum chamado em aberto no momento...
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="box">
            <div class="box-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="py-0 my-0">Chamados Finalizados</h5>
                    <a href="<?= url('app.list.state', ['user' => $user->Framework_User, 'state' => 2]); ?>" class="btn btn-danger">Ver Todos Chamados Finalizados</a>
                </div>
            </div>
            <div class="box-content">
                <?php if ($closed) : ?>
                    <?php foreach ($closed as $item) :  ?>
                        <div class="box-inline">
                            <div class="content">
                                <div class="detail">
                                    <img src="<?= asset('storage/avatar/'.mb_strtoupper($user->Username[0]).'.png'); ?>" alt="" class="avatar">
                                    <div class="group">
                                        <span>Nº.:</span>
                                        <?= $item->ID_ARTIA == 0 ? $item->TICKET_ID : $item->ID_ARTIA; ?>
                                    </div>
                                    <div class="group">
                                        <span>Finalizado em:</span>
                                        <?= date('d/m/y à\s H:i', strtotime($item->ID_ARTIA ? $item->FINALIZACAO_ARTIA : $item->FINALIZACAO)); ?>
                                    </div>
                                    <div class="group">
                                        <span>Departamento responsável:</span>
                                        <?= mb_convert_case($item->DEPARTAMENTO, MB_CASE_TITLE, 'UTF-8'); ?>
                                    </div>
                                    <div class="group">
                                        <span>Assunto do Chamado:</span>
                                        <?= mb_convert_case(html_entity_decode($item->TITULO), MB_CASE_TITLE, 'UTF-8'); ?>
                                    </div>
                                </div>
                                <a href="<?= url('ticket.show', ['id' => $item->TICKET_CHAMADO]); ?>" class="btn btn-sm btn-primary">Visualizar</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="alert alert-info" role="alert">
                        Nenhum chamado em aberto no momento...
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>