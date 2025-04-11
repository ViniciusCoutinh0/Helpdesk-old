<div class="col-12 col-sm-8 mb-2">
    <div class="box">
        <div class="box-header">
            <h4 class="px-0 my-0">Informações Básicas</h4>
        </div>
        <div class="box-content">
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Nome de Usuário</td>
                            <td><strong><?= $user->Username; ?></strong></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><strong><?= $user->Email; ?></strong></td>
                        </tr>
                        <tr>
                            <td>Setor do Usuário</td>
                            <td><strong><?= $sector->Name; ?></strong></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <h4 class="px-0 my-0">Permissões de <?= $sector->Name; ?>:</h4>
                            </td>
                        </tr>
                        <tr>
                            <td>Criar</td>
                            <td><?= ($rules->Rule_Create == 'S' ? 'Sim' : 'Não'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Visualizar</td>
                            <td><?= ($rules->Rule_Read == 'S' ? 'Sim' : 'Não'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Editar</td>
                            <td><?= ($rules->Rule_Update == 'S' ? 'Sim' : 'Não'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Excluir</td>
                            <td><?= ($rules->Rule_Delete == 'S' ? 'Sim' : 'Não'); ?>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <td>Conta criada em</td>
                            <td><strong><?= date('d/m/Y à\s H:i:s', strtotime($user->Created_at)); ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>