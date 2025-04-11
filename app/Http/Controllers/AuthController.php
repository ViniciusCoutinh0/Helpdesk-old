<?php

namespace App\Http\Controllers;

use App\Common\Message;
use App\Common\View;
use App\Models\Entity\User;

class AuthController extends User
{
    /** @var \App\Common\View */
    private $view;
    private $message;

    public function __construct()
    {
        $this->view = new View();
        $this->message = new Message();
    }

    public function viewLogin(): void
    {
        echo $this->view->render('home', [
            'message' => $this->message
        ]);
    }

    public function signIn(): void
    {
        $required = input()->all();
        $required = array_map('clearHtml', $required);

        if (in_array('', $required)) {
            $this->message->error('Existem campos em branco por favor preencha todos os campos');
            $this->viewLogin();
            return;
        }

        $user = $this->getUserByUsername(trim($required['username']));

        if (!$user) {
            $this->message->error('Usuário/Senha inválido ou não cadastrado');
            $this->viewLogin();
            return;
        }

        if (!password_verify(trim($required['password']), $user->Password)) {
            $this->message->error('Usuário/Senha inválido ou não cadastrado');
            $this->viewLogin();
            return;
        }

        if ($user->State === 'N') {
            $this->message->error('Usuário inativo por favor entre contato com Suporte T.i');
            $this->viewLogin();
            return;
        }

        Session()
        ->set('USERNAME', trim($required['username']))
        ->set('USER_ID', (int) $user->Framework_User);

        $user->Status = 1;
        $user->save();

        // if ($user->Pending_Password === 'S') {
        //     redirect("/account/user/{$user->Framework_User}/change/password", 200);
        //     return;
        // }

        redirect(url('app.home'), 200);
    }

    public function signOut(): void
    {
        $user = $this->getUserByUsername(Session()->USERNAME);

        if (!$user || !Session()->has('USERNAME')) {
            redirect(env('CONFIG_APP_PATH'));
            return;
        }

        Session()->unset('USERNAME')->unset('USER_ID')->destroy();

        $user->Status = 2;
        $user->save();

        redirect(url('app.home'), 200);
    }
}
