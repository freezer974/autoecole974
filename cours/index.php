<?php
    $menu = 'cours';
    
    require_once('../header.php');
    require_once('../connexion_bdd.php');

    $roles = array('Admin', 'Eleve', 'Moniteur');
        $semaine = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        $horaire = ['7h-8h', '8h-9h', '9h-10h', '10h-11h', '11h-12h', '13h-14h','14h-15h', '15h-16h'];

        $requete = $bdd->prepare('SELECT * FROM voitures');
        $requete->execute();
        $voitures = $requete->fetchAll();

        if (empty($_SESSION['id'])): 
            $_SESSION['id'] = '';
        endif;

        $requete = $bdd->prepare('SELECT * FROM utilisateurs INNER JOIN roles ON roles.id = utilisateurs.id_role');
        $requete->execute();
        $utilisateurs = $requete->fetchAll();
       
        
?>
    <div class="form-group">
        <label for="id_utilisateur">Selectionner l'utilisateur pour manipuler le planning</label>
        <select id="id_utilisateur" name="id_utilisateur" class="form-control" required>
            <option value="">Selectionner un role</option>

            <?php foreach($utilisateurs as $utilisateur): ?>
                <option value="<?= $utilisateur['id'] ?>" data-lastname="<?= $utilisateur['nom'] ?>" data-firstname="<?= $utilisateur['prenom'] ?>" data-email="<?= $utilisateur['mail'] ?>" data-role="<?= $utilisateur['label'] ?>"> <?= htmlentities($utilisateur['nom']) ?> - role: <?= htmlentities($utilisateur['label']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    

    <?php /*if (!empty($_SESSION['id']) && !empty($_SESSION['email']) && !empty($_SESSION['role'])):*/ ?>

            <div class="col">
                <h1>Planning de gestion des cours</h1>
            </div>
        </div>

        <?php //recuperation de tout les cours des moniteurs ?>
        <?php $requete = $bdd->prepare('SELECT c.id AS idCours, 
                                               c.label AS labelCours, 
                                               c.dateCour AS dateCours, 
                                               c.heureCour AS heureCours, 
                                               c.dispo AS dispoCours, 
                                               c.id_voiture AS idVoiture, 
                                               ut.id AS idUtilisateur, 
                                               ut.nom AS nomUtilisateur, 
                                               ut.prenom AS prenomUtilisateur, 
                                               ut.mail AS mailUtilisateur, 
                                               ut.id_role AS idRole, 
                                               r.label AS labelRole 
                                        FROM cours c 
                                        INNER JOIN voitures v 
                                            ON c.id_voiture = v.id 
                                        INNER JOIN (
                                            SELECT u.id, u.nom, 
                                                   u.prenom, 
                                                   u.mail, 
                                                   u.id_role, 
                                                   cu.id_cour
                                            FROM utilisateurs u 
                                            INNER JOIN cours_utilisateurs cu 
                                            ON cu.id_utilisateur = u.id
                                        ) ut
                                            ON ut.id_cour = c.id
                                        INNER JOIN roles r
                                            ON r.id = ut.id_role
                                        WHERE r.label = \'Moniteur\'
                                        ORDER BY dateCour, HeureCour'); ?>
        <?php $requete->execute(); ?>
        <?php $coursMoniteurs = $requete->fetchAll(); ?>
        <?php $requete->closeCursor(); ?>
        <?php //recuperation de tout les cours des Eleves ?>
        <?php $requete = $bdd->prepare('SELECT c.id AS idCours, 
                                               c.label AS labelCours, 
                                               c.dateCour AS dateCours, 
                                               c.heureCour AS heureCours, 
                                               c.dispo AS dispoCours, 
                                               c.id_voiture AS idVoiture, 
                                               ut.id AS idUtilisateur, 
                                               ut.nom AS nomUtilisateur, 
                                               ut.prenom AS prenomUtilisateur, 
                                               ut.mail AS mailUtilisateur, 
                                               ut.id_role AS idRole, 
                                               r.label AS labelRole 
                                        FROM cours c 
                                        INNER JOIN voitures v 
                                            ON c.id_voiture = v.id 
                                        INNER JOIN (
                                            SELECT u.id, u.nom, 
                                                   u.prenom, 
                                                   u.mail, 
                                                   u.id_role, 
                                                   cu.id_cour
                                            FROM utilisateurs u 
                                            INNER JOIN cours_utilisateurs cu 
                                            ON cu.id_utilisateur = u.id
                                        ) ut
                                            ON ut.id_cour = c.id
                                        INNER JOIN roles r
                                            ON r.id = ut.id_role
                                        WHERE r.label = \'Eleve\'
                                        ORDER BY dateCour, HeureCour' ); ?>
        <?php $requete->execute(); ?>
        <?php $coursEleves = $requete->fetchAll(); ?>
        <?php $requete->closeCursor(); ?>

            <?php // on extraire le résultat dans des variables differente suivant les roles ?>
            <div class="d-flex flex-row text-center">
            <?php $coursMoniteur = array_shift($coursMoniteurs); ?>
            <?php $coursEleve = array_shift($coursEleves); ?>
                <?php for($jour = 1; $jour <= count($semaine); $jour++): ?>
                    <div class="col">
                        <h4><?= $semaine[$jour-1];?></h4>
                        <div class="list-group">
                            <?php for($heure = 1; $heure <= count($horaire); $heure++): ?>
                                <?php $reserveEleve = ''; $dispoMoniteur = ''; ?>
                                <?php $couleur = 'llist-group-item-light'; ?>
                                <?php $donnees  = $horaire[$heure-1]; ?>
                                <?php $donnees .= ($_SESSION['role'] == 'Moniteur')? ' <span class="badge text-black-50"><i class="fa fa-plus"  aria-hidden="true"></i></span>':''; ?>
                                <?php $attribut = ($_SESSION['role'] == 'Moniteur')? "data-toggle='modal' data-target='#ajoutModal' data-datecour='$jour' data-heurecour='$heure' data-label='Cuisine' data-nom='".$_SESSION['nom']."' data-horaire='".$horaire[$heure-1]."' data-jour='".$semaine[$jour-1]."' data-voitures='".json_encode($voitures)."' data-id_utilisateur='".$_SESSION['id']."'":''; ?>

                                <?php if (($coursMoniteur['dateCours'] == $jour && $coursMoniteur['heureCours'] == $heure)): ?> 
                                    <?php $dispoMoniteur = 'M : '; ?>
                                    <?php if ($_SESSION['id'] == $coursMoniteur['idUtilisateur']): ?>
                                        <?php $dispoMoniteur .=  'vous'; ?>
                                        <?php $dispoMoniteur .= ' <span class="badge text-danger"><i class="fa fa-times"  aria-hidden="true"></i></span>'; ?>
                                        <?php if ($coursMoniteur['dispoCours'] == 0): ?>
                                            <?php // mets la span en début de phrase ?>
                                            <?php $couleur = 'list-group-item-success'; ?>
                                            <?php $attribut = "data-toggle='modal' data-target='#annuleModal' data-datecour='$jour' data-heurecour='$heure' data-label='".$coursMoniteur['labelCours']."' data-nom='".$_SESSION['nom']."' data-horaire='".$horaire[$heure-1]."' data-jour='".$semaine[$jour-1]."' data-id='".$coursMoniteur['idCours']."'"; ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php $dispoMoniteur .= $coursMoniteur['nomUtilisateur']; ?>
                                        <?php $couleur = 'list-group-item-info'; ?>
                                        <?php $attribut = ''; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if ($coursEleve['dateCours'] == $jour && $coursEleve['heureCours'] == $heure): ?>
                                    <?php $reserveEleve = 'E : '; ?>
                                    <?php if ($_SESSION['id'] == $coursEleve['idUtilisateur']): ?>
                                            <?php $couleur = 'list-group-item-danger'; ?>
                                            <?php $reserveEleve .=  'vous'; ?>
                                            <?php $reserveEleve .=  ' <span class="badge text-danger"><i class="fa fa-times"  aria-hidden="true"></i></span>' ?>
                                            <?php $attribut = "data-toggle='modal' data-target='#annuleReservationModal' data-datecour='$jour' data-heurecour='$heure' data-label='".$coursEleve['labelCours']."' data-nom='".$_SESSION['nom']."' data-nommoniteur='".$coursMoniteur['nomUtilisateur']."' data-id_eleve='' data-nom_eleve='' data-horaire='".$horaire[$heure-1]."' data-jour='".$semaine[$jour-1]."' data-id_cours='".$coursEleve['idCours']."'"; ?>
                                    <?php else: ?>
                                        <?php $reserveEleve .= $coursEleve['nomUtilisateur']; ?>
                                        <?php $couleur = 'list-group-item-info'; ?>
                                        <?php $attribut = ''; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if (($coursMoniteur['dispoCours'] == 0) && ($_SESSION['role'] == 'Eleve') && !empty($dispoMoniteur)): ?>
                                    <?php $reserveEleve = 'Dispo'; ?>
                                    <?php $couleur = 'list-group-item-success'; ?>
                                    <?php $attribut = "data-toggle='modal' data-target='#reserveModal' data-datecour='$jour' data-heurecour='$heure' data-label='".$coursMoniteur['labelCours']."' data-nom='".$_SESSION['nom']."' data-nomMoniteur='".$coursMoniteur['nomUtilisateur']."' data-horaire='".$horaire[$heure-1]."' data-jour='".$semaine[$jour-1]."' data-id='".$coursMoniteur['idCours']."'"; ?>
                                <?php endif; ?>
                                <?php if (($coursMoniteur['dispoCours'] == 1) && ($_SESSION['role'] == 'Moniteur') && !empty($dispoMoniteur) && ($_SESSION['id'] == $coursMoniteur['idUtilisateur'])): ?>
                                    <?php $couleur = 'list-group-item-warning'; ?>
                                    <?php $attribut = "data-toggle='modal' data-target='#annuleReservationModal' data-datecour='$jour' data-heurecour='$heure' data-label='".$coursMoniteur['labelCours']."' data-nom='".$_SESSION['nom']."' data-nommoniteur='".$coursMoniteur['nomUtilisateur']."' data-id_eleve='".$coursEleve['idUtilisateur']."' data-nom_eleve='".$coursEleve['nomUtilisateur']."' data-horaire='".$horaire[$heure-1]."' data-jour='".$semaine[$jour-1]."' data-id_cours='".$coursMoniteur['idCours']."'"; ?>
                                <?php endif; ?>

                                <?php if (!empty($dispoMoniteur) || !empty($reserveEleve)): ?>
                                    <?php $donnees = ''; ?>
                                    <?php $donnees .= (!empty($dispoMoniteur))? '<div class="small text-success" id=\'moniteur\'>'.$dispoMoniteur.'</div>':''; ?>
                                    <?php $donnees .= (!empty($dispoMoniteur))? '<div  class="small" id=\'eleve\'>'. $reserveEleve .'</div>':''; ?> 
                                <?php endif; ?>

                                <a class="list-group-item list-group-item-action <?= $couleur; ?> p-2" <?= $attribut; ?>><?= $donnees; ?></a>
                                <?php if (($coursMoniteur['dateCours'] == $jour && $coursMoniteur['heureCours'] == $heure)): ?> 
                                    <?php $coursMoniteur = array_shift($coursMoniteurs); ?>
                                <?php endif; ?>
                                <?php if ($coursEleve['dateCours'] == $jour && $coursEleve['heureCours'] == $heure): ?>
                                    <?php $coursEleve = array_shift($coursEleves); ?>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                    </div> 
                <?php endfor; ?>
            </div>
                    
        <?php /* else: ?>
            <span class="text-muted">Vous devez être connecté pour voir le planning</span>
        <?php endif; */?>

        <div class="modal fade" id="ajoutModal" tabindex="-1" role="dialog" aria-labelledby="ajoutModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ajoutModalLabel">Titre</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p> voulez vous ajouter un cours de </p>
                    <form class='form' method='POST' action='action_cours.php' id="formCoursAjout">
                        <input type='hidden' id='dateCour' name='dateCour' value="">
                        <input type='hidden' id='heureCour' name='heureCour' value="">
                        <input type='hidden' id='label' name='label' value="">
                        <input type='hidden' id='id_utilisateur' name='id_utilisateur' value="">
                        <div class="form-group">
                        <label for="id_voiture">Avec quelle la voiture ?</label>
                        <select id='id_voiture' name='id_voiture' class="form-control" required></select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Non</button>
                    <button type="submit" form="formCoursAjout" class="btn btn-primary" name="action" id="action" value="">Oui</button>
                </div>
                </div>
            </div>
        </div>

        <!-- modal pour l'annulation des cours -->
        <div class="modal fade" id="annuleModal" tabindex="-1" role="dialog" aria-labelledby="annuleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="annuleModalLabel">Titre</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p> voulez vous annule le cours de </p>
                    <form class='form' method='POST' action='action_cours.php' id="formCoursAnnule">
                        <input type='hidden' id='dateCour' name='dateCour' value="">
                        <input type='hidden' id='heureCour' name='heureCour' value="">
                        <input type='hidden' id='label' name='label' value="">
                        <input type='hidden' id='id' name='id' value="">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Non</button>
                    <button type="submit" form="formCoursAnnule" class="btn btn-primary" name="action" id="action" value="">Oui</button>
                </div>
                </div>
            </div>
        </div>

        <!-- modal pour réserver des cours -->
        <div class="modal fade" id="reserveModal" tabindex="-1" role="dialog" aria-labelledby="reserveModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reserveModalLabel">Titre</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p> voulez vous réserver le cours de </p>
                    <form class='form' method='POST' action='action_cours.php' id="formCoursReserve">
                        <input type='hidden' id='dateCour' name='dateCour' value="">
                        <input type='hidden' id='heureCour' name='heureCour' value="">
                        <input type='hidden' id='label' name='label' value="">
                        <input type='hidden' id='id' name='id' value="">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Non</button>
                    <button type="submit" form="formCoursReserve" class="btn btn-primary" name="action" id="action" value="">Oui</button>
                </div>
                </div>
            </div>
        </div>

        <!-- modal pour l'annulation des reservations de cours-->
        <div class="modal fade" id="annuleReservationModal" tabindex="-1" role="dialog" aria-labelledby="annuleReservationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="annuleReservationModalLabel">Titre</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p> voulez vous annuleReservation le cours de </p>
                    <form class='form' method='POST' action='action_cours.php' id="formCoursannuleReservation">
                        <input type='hidden' id='dateCour' name='dateCour' value="">
                        <input type='hidden' id='heureCour' name='heureCour' value="">
                        <input type='hidden' id='label' name='label' value="">
                        <input type='hidden' id='id_eleve' name='id_eleve' value="">
                        <input type='hidden' id='id_cours' name='id_cours' value="">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Non</button>
                    <button type="submit" form="formCoursannuleReservation" class="btn btn-primary" name="action" id="action" value="">Oui</button>
                </div>
                </div>
            </div>
        </div>
 <?php
    require_once('../footer.php');
?>
