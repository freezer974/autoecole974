<?php
    // appel à la session
    require_once('../session.php');
    //appel à la base de donnée
    require_once('../connexion_bdd.php');

    //echo '<pre>' . var_export($_POST, true) . '</pre>';
    //echo '<pre>' . var_export($_SESSION, true) . '</pre>';
    //die();

    if (!empty($_POST['action'])):

        $action = htmlentities($_POST['action']);
        
        // si on a l'action ajouter et que l'utilisateur est un moniteur
        if (($action == 'ajouter') && ($_SESSION['role'] == 'Moniteur')):
            
            // on vérifie que tous les champs sont rempli pour faire la gestion
            if (!empty($action) && !empty($_POST['label']) && !empty($_POST['dateCour']) && !empty($_POST['heureCour']) && !empty($_POST['id_voiture'])):

                $req = $bdd->prepare('SELECT dateCour, heureCour FROM cours WHERE dateCour = :dateCour AND heureCour = :heureCour');
                $req->bindValue(':dateCour', $_POST['dateCour'], PDO::PARAM_INT);
                $req->bindValue(':heureCour', $_POST['heureCour'], PDO::PARAM_INT);
                $req->execute();
                $cours = $req->fetch();
                $req->closeCursor();

                // on vérifie si le créneau choisi n'est pas déja pris 
                if (!$cours):

                    // on insère le cours dans la basse de donnée
                    $req = $bdd->prepare('INSERT INTO cours (label,dateCour,heureCour,id_voiture,dispo) VALUES (:label,:dateCour,:heureCour,:id_voiture,:dispo)');
                    $req->bindValue(':label', ChaineAvecMajuscule($_POST['label']), PDO::PARAM_STR);
                    $req->bindValue(':dateCour', $_POST['dateCour'], PDO::PARAM_INT);
                    $req->bindValue(':heureCour', $_POST['heureCour'], PDO::PARAM_INT);
                    $req->bindValue(':id_voiture', $_POST['id_voiture'], PDO::PARAM_INT);
                    $req->bindValue(':dispo', 0, PDO::PARAM_INT);
                    $req->execute();
                    $id_cour = $bdd->lastInsertId();
                    $req->closeCursor();

                    // on associe le cours et l'utilisateur dans leur table relationnelle la basse de donnée
                    $req = $bdd->prepare('INSERT INTO cours_utilisateurs(id_cour,id_utilisateur) VALUES (:id_cour,:id_utilisateur)');
                        $req->bindValue(':id_utilisateur', $_SESSION['id'], PDO::PARAM_INT);
                        $req->bindValue(':id_cour', $id_cour, PDO::PARAM_INT);
                        $req->execute();
                        $req->closeCursor();

                    // on envoie un message pour dire que c'est ok
                    flash('message', 'Le cours de <strong>'. ChaineAvecMajuscule($_POST['label']).'</strong> a été ajoutée');
                    redirection_page();
                else:

                    // s'il y a déjà un cours à cette plage horaire on envoie un message pour le dire
                    flash('message', 'Le cours de <strong>'. ChaineAvecMajuscule($_POST['label']) .'</strong> est déjà réservé');
                    redirection_page();
                endif;
            else:
                // s'il manque des élements on envoie un message pour le dire
                flash('message', 'Les données ne sont pas parvenues', 'danger');
                redirection_page();
            endif;
        endif;

        // si on a l'action annuler et que l'utilisateur est un moniteur et on a les champs id valide
        if (($action == 'annuler') && (connexion_role('Moniteur') && !empty($_POST['id']) && intval($_POST['id']) > 0)):
            
            $req = $bdd->prepare('SELECT dateCour, heureCour FROM cours WHERE dateCour = :dateCour AND heureCour = :heureCour');
            $req->bindValue(':dateCour', $_POST['dateCour'], PDO::PARAM_INT);
            $req->bindValue(':heureCour', $_POST['heureCour'], PDO::PARAM_INT);
            $req->execute();
            $cours = $req->fetch();
            $req->closeCursor();

            // on vérifie si le créneau choisi est bien éxistant, puis on supprime le cours et en cascade ses relations dans la table relationnelle 
            if ($cours):
                delete($bdd, 'cours', 'id', $_POST['id']);

                flash('message', 'Le cours de <strong>'. ChaineAvecMajuscule($_POST['label']).'</strong> a été annulé');
                redirection_page();
            else:

                flash('message', 'Le cours de <strong>'. ChaineAvecMajuscule($_POST['label']) .'</strong> est déjà vide');
                redirection_page();
            endif;
        endif;

        // si on a l'action réserver et que l'utilisateur est un élève avec le champs id valide
       if (($action == 'reserver') && (connexion_role('Eleve') && !empty($_POST['id']) && intval($_POST['id']) > 0)):
            $req = $bdd->prepare('SELECT dateCour, heureCour FROM cours WHERE dateCour = :dateCour AND heureCour = :heureCour');
            $req->bindValue(':dateCour', $_POST['dateCour'], PDO::PARAM_INT);
            $req->bindValue(':heureCour', $_POST['heureCour'], PDO::PARAM_INT);
            $req->execute();
            $cours = $req->fetch();
            $req->closeCursor();

            // on vérifie si le créneau choisi est bien éxistant, puis on supprime le cours et en cascade ses relations dans la table relationnelle 
            if ($cours):
                // on update les informations de la table cours
                $req = $bdd->prepare('UPDATE cours SET dispo = :dispo WHERE  id = :id'); 
                $req->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
                $req->bindValue(':dispo', 1 , PDO::PARAM_INT);
                $req->execute();
                $req->closeCursor();

                // on insère l'élève au cours modifié dans la table relationnelle
                $req = $bdd->prepare('INSERT INTO cours_utilisateurs(id_cour,id_utilisateur) VALUES (:id_cour,:id_utilisateur)');
                $req->bindValue(':id_utilisateur', $_SESSION['id'], PDO::PARAM_INT);
                $req->bindValue(':id_cour',$_POST['id'], PDO::PARAM_INT);
                $req->execute();
                $req->closeCursor();

                flash('message', 'Le cours a été réservé');
                redirection_page();
            else:
                flash('message', 'Impossible de réservé ce cours de <strong>'. ChaineAvecMajuscule($_POST['label']) .'</strong>');
                redirection_page();
            endif;
        endif;

        // si on a l'action annule réservation et que l'utilisateur et un eleve ou moniteur et l'id du cours est valide
        if (($action == 'annuleReservation') && (connexion_role(['Eleve', 'Moniteur']) && !empty($_POST['id_cours']) && intval($_POST['id_cours']) > 0)):
            
            $req = $bdd->prepare('SELECT dateCour, heureCour FROM cours WHERE dateCour = :dateCour AND heureCour = :heureCour');
            $req->bindValue(':dateCour', $_POST['dateCour'], PDO::PARAM_INT);
            $req->bindValue(':heureCour', $_POST['heureCour'], PDO::PARAM_INT);
            $req->execute();
            $cours = $req->fetch();
            $req->closeCursor();

            // on vérifie la présence de l'id_eleve qui déterminera le type d'utilisateur
            if (empty($_POST['id_eleve'])):
                $id_utilisateur = $_SESSION['id'];
            else:
                $id_utilisateur = $_POST['id_eleve'];
            endif;

            // on vérifie si le créneau choisi est bien éxistant, puis on update le cours  pour le rendre disponible et on supprime le lien dans la base relationnelle
            if ($cours):
                $req = $bdd->prepare("UPDATE cours SET dispo = :dispo WHERE  id = :id"); 
                $req->bindValue(':dispo', 0, PDO::PARAM_INT);
                $req->bindValue(':id', $_POST['id_cours'], PDO::PARAM_INT);
                $req->execute();
                $req->closeCursor();

                $req = $bdd->prepare("DELETE FROM cours_utilisateurs WHERE id_cour = :id_cour AND id_utilisateur = :id_utilisateur");
                $req->bindValue(':id_cour',$_POST['id_cours'], PDO::PARAM_INT);
                $req->bindValue(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
                $req->execute();
                $req->closeCursor();

                $message = 'La réservation est annulée. ';
                if (!empty($_POST['id_eleve'])):
                    $message .= 'Un mail a été envoyé avec succès pour prévenir l\'élève';
                endif;
                flash('message', $message);
                redirection_page();
            else:
                flash('message', 'Impossible d\'annuler la réservation de ce cours de <strong>'. ChaineAvecMajuscule($_POST['label']) .'</strong>');
                redirection_page();
            endif;
        endif;
    endif;

    // si on a l'action deconnexion
    if ($action == 'deconnexion') : 

        flash('message', $_SESSION['nom'] . 'vous êtes déconnecté, quelle dommage ! A bientôt.');   
        
        // Suppression des variables de session et de la session
        $_SESSION = array();
        session_destroy();

        // Suppression des cookies de connexion automatique
        if (!empty($_COOKIE['email']) && !empty($_COOKIE['password'])):
            setcookie('email', '');
            setcookie('password', '');
        endif;

        redirection_page();
    endif;
    
    flash('message', 'Les données ne sont pas parvenues', 'danger');
    redirection_page();
    
?>