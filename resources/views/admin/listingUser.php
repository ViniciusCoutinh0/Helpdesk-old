<?php $v->layout('_theme'); ?>
<div class="row">
    <div class="col-12 col-sm-8 mb-2">
        <div class="box">
            <div class="box-header">
                <h4>Lista de Usuários cadastrados</h4>
            </div>
            <div class="box-content p-2">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nome de Usuário</th>
                                <th>Setor</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($listAll) : ?>
                                <?php foreach ($listAll as $item) : ?>
                            <tr>
                                <td><?= mb_strtolower($item->Username); ?></td>
                                <td><?=$item->Sector; ?></td>
                                <td>
                                    <a href="<?=url('admin.view.update.user', ['user' => $item->Framework_User]); ?>">
                                        Editar
                                    </a>
                                </td>
                            </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php $v->insert('account/sidebar'); ?>
</div>
