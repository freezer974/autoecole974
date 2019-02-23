<?php
    require_once('../connexion_bdd.php');// connexion avec la base de données
    require_once('../session.php');
   /* var_dump($_POST);
    die();*/

    $pass1 = htmlentities($_POST['password1']);
    $pass2 = htmlentities($_POST['password2']);


    if ($pass1 == $pass2) // vérifie que les deux mot de passe soit identique
    {
        
        $pass_hache=password_hash($_POST['password1'], PASSWORD_DEFAULT);

            $mod = $bdd->prepare('INSERT INTO utilisateurs(nom, prenom, mail, id_role, password ) VALUES (?,?,?,?,?)');
            $mod->execute(array(
                htmlspecialchars( $_POST['nom']), 
                htmlspecialchars( $_POST['prenom']), 
                htmlspecialchars( $_POST['mail']),
                htmlspecialchars( $_POST['id_role']), 
                ( $pass_hache), 
                ));

            //redirection du visiteur vers la page de inscription
            flash("message", "Bienvenue dans l'équipage de  l'Auto-Ecole 974 votre compte a été crée avec succés.");
            redirection_page();

     }
        else
        {
            //redirection du visiteur vers la page de inscription
            flash("message", "Les mots de passe ne coresspond pas veillez les resaisir", "danger");
            redirection_page();

        }

