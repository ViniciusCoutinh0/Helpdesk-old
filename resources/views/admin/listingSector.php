<?php $v->layout('_theme'); ?>
<?php if (Session()->USER_ID) :  ?>
<div class="row">
    <div class="col-12 col-sm-8 mb-2">
        <div class="box">
            <div class="box-header">
                <h4>Lista de Setores Cadastrados</h4>
            </div>
            <div class="box-content p-2">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if($sectors): ?>
                        <?php foreach($sectors as $sector): ?>
                        <tr>
                            <td><?=$sector->Name; ?></td>
                            <td><a href="<?=url('admin.view.update.sector', ['sector' => $sector->Framework_Sector]); ?>">Editar</a></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <?=$v->insert('account/sidebar'); ?>
</div>
<?php endif; ?>