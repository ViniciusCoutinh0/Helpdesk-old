<?php

namespace App\Http\Controllers;

use App\Common\Message;
use App\Common\View;
use App\Models\Entity\User;
use App\Models\Ticket\Ticket;

class AppController extends User
{
    private $view;
    private $message;

    public function __construct()
    {
        $this->view = new View();
        $this->message = new Message();
    }

    public function home(): void
    {
        if (Session()->has('USER_ID') === false) {
            echo $this->view->render('home', ['message' => $this->message]);
            return;
        }

        $user = (new User())->getUserById((int) Session()->USER_ID);
        $open = (new Ticket())->getTicketsByUsernameAndState($user, 8, 1);
        $closed = (new Ticket())->getTicketsByUsernameAndState($user, 8, 2);

        echo $this->view->render('home', compact('user', 'open', 'closed'));
    }

    public function list(string $user, int $state): void
    {
        $user = (new User())->getUserById((int) Session()->USER_ID);
        $tickets = (new Ticket())->getTicketsByUsernameAndState($user, null, $state);

        echo $this->view->render('home/tickets', compact('user', 'tickets'));
    }
}
