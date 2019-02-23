<?php
    require_once('../connexion_bdd.php');// connexion avec la base de données
    require_once('../session.php');
    /*var_dump($_POST);
    die();*/
    
    $pass_hache=password_hash($_POST['password'], PASSWORD_DEFAULT);

        $mod = $bdd->prepare('INSERT INTO utilisateurs(nom, prenom, mail, id_role, password ) VALUES (?,?,?,?,?)');
        $mod->execute(array(
            htmlspecialchars( $_POST['nom']), 
            htmlspecialchars( $_POST['prenom']), 
            htmlspecialchars( $_POST['mail']),
            htmlspecialchars( $_POST['id_role']), 
            htmlspecialchars( $pass_hache), 
            ));

        //redirection du visiteur vers la page de voir_utilisateurs
        flash("message", "Bienvenue dans l'équipage de  l'Auto-Ecole 974 votre compte a été crée avec succés.");
        redirection_page();

