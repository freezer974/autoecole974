<?php
   //Ensure that a session exists (just in case)
    require_once('session.php');
    
    if (isset($_GET['page'])) {
        $page = intval($_GET['page']);
    } else {
        $page = 1;
    }

    $nbElementParPage = 5;
    $offset = ($page-1) * $nbElementParPage;
    $totalPage = 0;
    $limit = ' LIMIT '.$offset.','.$nbElementParPage;

    if (empty($menu)){
        $menu = 'accueil';
    }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AutoEcole974, une conduite temporelle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    

    <link rel="stylesheet" type="text/css" media="screen" href="../main.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg  <?= ($_SESSION['role'] == 'Admin')? 'navbar-light bg-warning':'navbar-dark bg-dark'; ?> ">
    <a class="navbar-brand" href="#">AutoEcole974</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav">
        <li class="nav-item <?= (($menu == 'accueil')? 'active' : ''); ?>">
            <a class="nav-link" href="/">Accueil <?= (($menu == 'accueil')? '<span class="sr-only">(current)</span>' : ''); ?></a>
        </li>
        <?php if ($_SESSION['role'] == 'Admin'): ?>
            <li class="nav-item <?= (($menu == 'roles')? 'active' : ''); ?>" >
                <a class="nav-link" href="#">Rôles <?= (($menu == 'roles')? '<span class="sr-only">(current)</span>' : ''); ?></a>
            </li>
            <li class="nav-item <?= (($menu == 'utilisateurs')? 'active' : ''); ?>" >
                <a class="nav-link" href="#">Utilisateurs <?= (($menu == 'utilisateurs')? '<span class="sr-only">(current)</span>' : ''); ?></a>
            </li>
        <?php endif;?>
        <li class="nav-item <?= (($menu == 'cours')? 'active' : ''); ?>" >
                <a class="nav-link" href="/cours">Planning <?= (($menu == 'cours')? '<span class="sr-only">(current)</span>' : ''); ?></a>
            </li>
        </ul>

        <?php if (!empty($_SESSION['id'])): ?>
            <ul class="navbar-nav ml-md-auto">
                <li class="nav-item <?= (($menu == 'profil')? 'active' : ''); ?>">
                    <a class="nav-link" href="#"><?= $_SESSION['nom']; ?> <span class="font-italic small">(<?= $_SESSION['role']; ?>)</span></a>
                </li>
                <li class="nav-item <?= (($menu == 'deconnexion')? 'active' : ''); ?>">
                    <form class="form-inline my-2 my-lg-0" action="/cours/action_cours.php" method="POST">
                        <input type="hidden" value='deconnexion' name='action'>
                        <button type="submit" class="btn btn-secondary">Deconnexion</button>
                    </form>
                </li>
            </ul>
        <?php else: ?>
        <ul class="navbar-nav ml-md-auto">
            <li class="nav-item <?= (($menu == 'login')? 'active' : ''); ?>">
                <a class="nav-link" href="#">Login <?= (($menu == 'login')? '<span class="sr-only">(current)</span>' : ''); ?></a>
            </li>
            <li class="nav-item <?= (($menu == 'inscription')? 'active' : ''); ?>">
                <a class="nav-link" href="/Utilisateur/Inscription.php">Inscription <?= (($menu == 'inscription')? '<span class="sr-only">(current)</span>' : ''); ?></a>
            </li>
        </ul>
        <?php endif; ?>
    </div>
    </nav>
    <div class="container">
        <div class="mt-3">
        <?php flash( 'message' ); ?>
        