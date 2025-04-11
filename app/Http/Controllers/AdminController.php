<?php

namespace App\Http\Controllers;

use App\Common\View;
use App\Common\Message;
use App\Models\Entity\User;
use App\Models\Sector\Sector;
use App\Models\Ticket\Ticket;
use App\Core\Mail;
use App\Models\Rules\Rules;
use App\Models\Ticket\Departament;
use League\Csv\Writer;

class AdminController extends User
{
    /**
     * @var App\Common\View
     */
    private $view;

    /**
     * @var App\Common\Message
     */
    private $message;

    public function __construct()
    {
        $this->view = new View();

        $user = (new User())->getUserById((int) Session()->USER_ID);
        $sector = (new Sector())->getSectorByUser($user);

        $this->message = new Message();
        $this->view->addData(compact('user', 'sector'));
    }

    public function listUsers(): void
    {
        $listAll = (new User())->getAllUser();

        echo $this->view->render('admin/listingUser', compact('listAll'));
    }

    public function listSections(): void
    {
        $sectors = (new Sector())->getAllSectors();

        echo $this->view->render('admin/listingSector', compact('sectors'));
    }

    public function viewCreateUser(): void
    {
        $sectors = (new Sector())->getAllSectors();
        $message = $this->message;

        echo $this->view->render('admin/addUser', compact('sectors', 'message'));
    }

    public function createUser(): void
    {
        $required = input()->all();
        array_shift($required);

        $required = array_map('clearHtml', $required);

        if (in_array('', $required)) {
            $this->message->error('Existem campos em branco, por favor preencha todos os campos');
            $this->viewCreateUser();
            return;
        }

        $username = $this->find()->where(['Username' => $required['username']])->count();
        $email = $this->find()->where(['Email' => $required['email']])->count();

        if ($username) {
            $this->message->error('Username já cadastrado no sistema');
            $this->viewCreateUser();
            return;
        }

        if ($email) {
            $this->message->error('Endereço de E-mail já cadastrado no sistema');
            $this->viewCreateUser();
            return;
        }

        if (!filter_var($required['email'], FILTER_VALIDATE_EMAIL)) {
            $this->message->error('Endereço de E-mail formato inválido');
            $this->viewCreateUser();
            return;
        }

        $create = $this->createUserByData($required);

        if (!$create) {
            $this->message->error('Não foi possivel criar o novo usuário');
            $this->viewCreateUser();
            return;
        }

        $this->message->success('Novo usuário ' . $required['username'] . ' criado com sucesso');
        clearCache($required);
        $this->viewCreateUser();
    }

    public function viewCreateReport(array $data = []): void
    {
        $departaments = (new Departament())->find()->all();

        echo $this->view->render('admin/report', [
            'data' => $data,
            'message' => $this->message,
            'departaments' => $departaments
        ]);
    }


    public function createReport()
    {
        $validated = [
            'start_date'  => strip_tags(input('start_date')),
            'end_date'    => strip_tags(input('end_date')),
            'departament' => (int) input('departament')
        ];

        if (in_array('', $validated)) {
            $this->message->error('Preencha todos os campos!');
            $this->viewCreateReport();
            return;
        }

        $departament = (new Departament())->findBy($validated['departament'])->first();

        if (!$departament) {
            $this->message->error('Departamento não encontrado!');
            $this->viewCreateReport();
            return;
        }

        $tickets = (new Ticket())->getAllTicketsByBetween([
            'start_date'  => $validated['start_date'],
            'end_date'    => $validated['end_date'],
            'departament' => $departament->NOME
        ]);

        if (!count($tickets)) {
            $this->message->error('Nenhum registro encontrado!');
            $this->viewCreateReport();
            return;
        }

        $this->message->success(sprintf('Relatório de Chamados %s gerado com sucesso, total de %s registros.', $departament->NOME, count($tickets)));
        $this->viewCreateReport($tickets);
    }

    public function outputReport(string $start_date, string $end_date, string $departament)
    {
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Description: File Transfer');

        $departament = (new Departament())->findBy($departament)->first();

        $data = (new Ticket())->getAllTicketsByBetween([
            'start_date'  => $start_date,
            'end_date'    => $end_date,
            'departament' => $departament->NOME
        ]);

        if (!count($data)) {
            return;
        }


        $csv = Writer::createFromString('');
        $csv->setDelimiter(';');
        $header = [
            'Protocolo',
            'ID Artia',
            'Departamento',
            'Categoria',
            'Sub Categoria',
            utf8_decode('Atendente/Responsável'),
            'Cliente/Solicitante',
            utf8_decode('Inicialização'),
            utf8_decode('Data de Criação'),
            utf8_decode('Data de Finalização'),
            utf8_decode('Horas de Abertura até a Finalização'),
            utf8_decode('Situação Artia')
        ];

        $csv->insertOne($header);

        $lines = [];
        foreach ($data as $ticket) {
            $startup = new \DateTime($ticket->INICIALIZACAO);
            $end = new \DateTime($ticket->FINALIZACAO);
            $interval = $startup->diff($end);

            $lines[] = [
                $ticket->TICKET_CHAMADO,
                $ticket->ID_ARTIA ?? $ticket->TICKET_ID,
                utf8_decode($ticket->DEPARTAMENTO),
                utf8_decode($ticket->CATEGORIA),
                utf8_decode($ticket->SUB_CATEGORIA),
                mb_convert_case($ticket->USUARIO_PROC, MB_CASE_TITLE, 'utf-8'),
                $ticket->USUARIO, date('d/m/Y H:i:s', strtotime($ticket->INICIALIZACAO)),
                date('d/m/Y H:i:s', strtotime($ticket->INICIALIZACAO)),
                ($ticket->FINALIZACAO ? date('d/m/Y H:i:s', strtotime($ticket->FINALIZACAO)) : ''),
                (!is_null($ticket->FINALIZACAO) ? ($interval->h + ($interval->days * 24)) . ':' . $interval->i . ':' . $interval->s : ''),
                $ticket->ESTADO_ARTIA ? $ticket->ESTADO_ARTIA : 'Pendente'
            ];
        }

        $csv->insertAll($lines);
        $csv->output('Relatório_Chamados_' . date('Y-m-d') . '.csv');
        die();
    }

    public function viewUpdateUser(int $id): void
    {
        $currentId = (new User())->getUserById($id);
        $sectors = (new Sector())->getAllSectors();
        $message = $this->message;

        echo $this->view->render('admin/updateUser', compact('currentId', 'sectors', 'message'));
    }

    public function updateUser(int $id): void
    {
        $required = [
            'email' => input()->post('email')->getValue(),
            'sector' => input()->post('sector')->getValue()
        ];

        if (in_array('', $required)) {
            $this->message->error('Existem campos obrigatórios em branco por favor preencha todos os campos');
            $this->viewUpdateUser($id);
            return;
        }

        if (!filter_var($required['email'], FILTER_VALIDATE_EMAIL)) {
            $this->message->error('Endereço de E-mail formato inválido');
            $this->viewUpdateUser($id);
            return;
        }

        $required['user_id'] = (int) $id;
        $required['name'] = clearHtml(input()->post('name')->getValue());
        $required['username'] = clearHtml(input()->post('username')->getValue());
        $required['password'] = clearHtml(input()->post('password')->getValue());

        if (!empty($required['password'])) {
            ob_start();
            include __DIR__ . '/../../../storage/layout/resetpassword.html';
            $view = ob_get_clean();
            $view = str_replace(['{username}', '{newpassword}'], [mb_strtoupper($required['username']), $required['password']], $view);

            $mail = new Mail();
            $mail->bootstrap('Sua nova senha em HelpDesk Promofarma', $view, $required['email'], $required['name']);
            $mail->send();
            ob_end_flush();
        }

        $update = $this->updateUserByData($required);

        if (!$update) {
            $this->message->error('Não foi possivel editar o usuário, por favor tente novamente');
            $this->viewUpdateUser($id);
            return;
        }

        $this->message->success('Usuário editado com sucesso');
        $this->viewUpdateUser($id);
    }

    public function viewCreateSector(): void
    {
        $message = $this->message;

        echo $this->view->render('admin/addSector', compact('message'));
    }

    public function createSector(): void
    {
        $required['rule_read']   = 'N';
        $required['rule_create'] = 'N';
        $required['rule_update'] = 'N';
        $required['rule_delete'] = 'N';

        foreach ($required as $key => $value) {
            if (input()->exists($key)) {
                $required[$key] = (input()->post($key)->getValue() === 'on' ? 'S' : 'N');
            }
        }

        $required['name'] = mb_convert_case(input()->post('name')->getValue(), MB_CASE_TITLE, 'UTF-8');
        $required = array_map('clearHtml', $required);

        if (in_array('', $required)) {
            $this->message->error('Existem campos em branco, por favor preencha todos os campos');
            $this->viewCreateSector();
            return;
        }

        $sector = (new Sector())->find()->where(['Name' => $required['name']])->count();

        if ($sector) {
            $this->message->error('Nome de setor já cadastrado');
            $this->viewCreateSector();
            return;
        }

        $create = (new Sector())->store($required);

        if (!$create) {
            $this->message->error('Não foi possivel criar um novo setor');
            $this->viewCreateSector();
            return;
        }

        $this->message->success('Novo setor cadastrado com sucesso');
        $this->viewCreateSector();
    }

    public function viewUpdateSector(int $id): void
    {
        $currentSector = (new Sector())->findBy($id)->first();
        $rule = (new Rules())->getRulesBySector($currentSector);

        if (!$currentSector) {
            redirect(url('app.home'));
            return;
        }

        $message = $this->message;
        echo $this->view->render('admin/updateSector', compact('currentSector', 'rule', 'message'));
    }

    public function updateSector(int $id): void
    {
        $required['id'] = $id;
        $required['name'] = mb_convert_case(input()->post('name')->getValue(), MB_CASE_TITLE, 'UTF-8');
        $checkbox = $this->checkbox(['rule_read', 'rule_create', 'rule_update', 'rule_delete']);

        if (empty($required)) {
            $this->message->error('Existem campos em branco, por favor preencha todos os campos');
            $this->viewUpdateSector($id);
            return;
        }

        $update = (new Sector())->updateByParam(array_merge($checkbox, $required));

        if (!$update) {
            $this->message->error('Existem campos em branco, por favor preencha todos os campos');
            $this->viewUpdateSector($id);
            return;
        }

        $this->message->success('Setor alterado com sucesso');
        $this->viewUpdateSector($id);
    }

    private function checkbox(array $data): array
    {
        $modified = [];
        foreach ($data as $key) {
            if (input()->exists($key)) {
                $modified[$key] = (input()->post($key)->getValue() === 'on' ? 'S' : 'N');
            } else {
                $modified[$key] = 'N';
            }
        }
        return $modified;
    }
}
