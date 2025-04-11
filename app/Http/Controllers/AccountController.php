<?php

namespace App\Http\Controllers;

use App\Common\View;
use App\Common\Message;
use App\Models\Entity\User;
use App\Models\Rules\Rules;
use App\Models\Sector\Sector;

class AccountController extends User
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
        $this->message = new Message();
    }

    public function viewAccount(int $id): void
    {
        $user = $this->getUserById((int) $id);
        $sector = (new Sector())->getSectorByUser($user);
        $rules = (new Rules())->getRulesBySector($sector);

        echo $this->view->render('account', [
            'user' => $user,
            'sector' => $sector,
            'rules' => $rules
        ]);
    }

    public function viewPassword(int $id): void
    {
        $user = $this->getUserById((int) $id);
        $sector = (new Sector())->getSectorByUser($user);

        echo $this->view->render('account/changePassword', [
            'user' => $user,
            'sector' => $sector,
            'message' => $this->message
        ]);
    }

    public function storePassword(int $id): void
    {
        $required = [
            'oldPassword' => input()->post('oldPassword')->getValue(),
            'newPassword' => input()->post('newPassword')->getValue(),
            'confirmPassword' => input()->post('confirmPassword')->getValue()
        ];

        if (in_array('', $required)) {
            $this->message->error('Existem campos em branco, por favor preencha todos os campos');
            $this->viewPassword($id);
            return;
        }

        $required = array_map('clearHtml', $required);

        $user = $this->getUserById((int) $id);

        if (!$user) {
            redirect(url('app.home'));
            return;
        }

        if (mb_strlen($required['newPassword']) <= 2) {
            $this->message->error('Sua nova senha deve conter mais de 3 caracteres');
            $this->viewPassword($id);
            return;
        }

        if ($required['newPassword'] != $required['confirmPassword']) {
            $this->message->error('Confirmação de senha não confere com sua nova Senha');
            $this->viewPassword($id);
            return;
        }

        if (!password_verify($required['oldPassword'], $user->Password)) {
            $this->message->error('Sua senha atual é inválida, por favor tente novamente');
            $this->viewPassword($id);
            return;
        }

        $update = $this->updatePasswordByUserId($id, $required['newPassword']);

        if (!$update) {
            $this->message->error('Não foi possível alterar sua senha, por favor tente novamente');
            $this->viewPassword($id);
            return;
        }

        $this->message->success('Sua senha foi alterada com sucesso.');
        $this->viewPassword($id);

        //redirect(url('account.password', ['user' => $id]));
    }
}
