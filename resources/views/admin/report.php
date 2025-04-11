<?php $v->layout('_theme'); ?>
<?php if (Session()->has('USER_ID')) : ?>
    <form method="POST" action="<?= url('admin.create.report'); ?>" id="form-report">
        <div class="row">
            <div class="col-12 col-sm-8 mb-2">
                <div class="box mb-2">
                    <div class="box-header">
                        <h4>Relátorio de Chamados</h4>
                    </div>
                    <div class="box-content p-2">
                        <div class="row">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
                            <div class="col-12 col-sm-12 col-md-4 col-xl-4 col-xxl-4">
                                <div class="form-group">
                                    <label class="form-label required" for="start_date">Data Inicial:</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control" min="2020-01-01" max="<?= date('Y-m-d'); ?>" value="<?= old('start_date'); ?>">
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-xl-4 col-xxl-4">
                                <div class="form-group">
                                    <label class="form-label required" for="end_date">Data Final:</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" min="2020-01-01" max="<?= date('Y-m-d'); ?>" value="<?= old('end_date'); ?>">
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-xl-4 col-xxl-4">
                                <div class="form-group">
                                    <label class="form-label required" for="departament">Departamento:</label>
                                    <select name="departament" id="departament" class="form-select" required>
                                        <option value disabled selected>Selecione o Departamento</option>
                                        <?php foreach ($departaments as $departament) : ?>
                                            <option value="<?= $departament->TICKET_DEPARTAMENTO; ?>" <?= old('departament') == $departament->TICKET_DEPARTAMENTO ? 'selected' : null ?>><?= $departament->NOME; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-content p-2 border-top">
                        <button class="btn btn-danger" id="btn-report">Gerar Relatório</button>
                    </div>
                </div>
                <?php if ($message) : ?>
                    <?= $message; ?>
                <?php endif; ?>
                <?php if (count($data)) :  ?>
                    <a href="<?= url('admin.output.report', ['start_date' => old('start_date'), 'end_date' => old('end_date'), 'departament' => old('departament')]); ?>" target="_blank" class="text-reset text-decoration-none" rel="noopener noreferrer" id="link-report">
                        <i class="fas fa-solid fa-download"></i> Download File.
                    </a>
                <?php endif; ?>
            </div>
            <?= $v->insert('account/sidebar'); ?>
        </div>
    </form>
    <?php $v->start('javascript'); ?>
    <script type="text/javascript">
        const form = document.getElementById('form-report');
        const btn = document.getElementById('btn-report');
        const link = document.getElementById('link-report');

        form.addEventListener('submit', function() {
            btn.setAttribute('disabled', true);
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span> Gerando o Arquivo por favor aguarde...</span>';
            link.style.display = 'none';
        });
    </script>
    <?php $v->end(); ?>
<?php endif; ?>