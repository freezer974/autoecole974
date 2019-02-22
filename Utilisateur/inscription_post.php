<?php 
    require_once('../connexion_bdd.php');// connexion avec la base de données

    var_dump($_POST);
    die();

    $pass_hache=password_hash($_POST['password'], PASSWORD_DEFAULT);

        $mod = $bdd->prepare('INSERT INTO utilisateurs(nom, prenom, mail, password, id_role ) VALUES (?,?,?,?,?)');
        $mod->execute(array(
            htmlspecialchars( $_POST['nom']), 
            htmlspecialchars( $_POST['prenom']), 
            htmlspecialchars( $_POST['mail']), 
            htmlspecialchars( $pass_hache), 
            htmlspecialchars( $_POST['id_role'])));

        //redirection du visiteur vers la page de voir_utilisateurs
        echo "<p>Bienvenue dans l'équipage de  l'Auto-Ecole 974 votre compte a été crée avec succés. <p>";
        echo "<a href=login.php>Se connecter<a/>";
?>

