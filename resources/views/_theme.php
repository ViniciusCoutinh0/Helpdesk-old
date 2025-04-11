<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_ENV['CONFIG_APP_NAME']; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;700&family=Sora:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('assets/app.css'); ?>">
    <link rel="shortcut icon" href="<?= asset('favicon.ico'); ?>" />

</head>

<body>
    <?php if (Session()->has('USER_ID')) : ?>
        <header>
            <div class="user">
                <img src="<?= asset('storage/avatar/' . mb_strtoupper($user->Username[0]) . '.png'); ?>" alt="avatar.png" class="avatar" />
                <a href="<?= url('app.home'); ?>" class="text-reset text-decoration-none">
                    Olá, <strong><?= mb_strtoupper($user->Username); ?></strong>
                </a>
            </div>
            <button id="mobile-btn">Menu</button>
            <nav class="menu" id="nav">
                <ul>
                    <li><a href="<?= url('app.home'); ?>">Página Inicial</a></li>
                    <li><a href="<?= url('ticket.store.view', ['user' => $user->Framework_User]); ?>">Novo Chamado</a></li>
                    <li><a href="<?= url('account.view', ['user' => $user->Framework_User]); ?>">Configurações da Conta</a></li>
                    <li><a href="<?= url('auth.signout'); ?>">Sair</a></li>
                </ul>
            </nav>
        </header>

    <?php endif; ?>
    <main class="container">
        <?= $v->section('content'); ?>
    </main>
</body>

<script src="<?= asset('assets/leopard2.js'); ?>"></script>

<?php if (Session()->has('USER_ID')) : ?>
    <script type="text/javascript">
        execToggle();
    </script>
<?php endif; ?>

</html>
<?= $v->section('javascript'); ?>

</html>