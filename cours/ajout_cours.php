<?php
    $menu = 'cours';

    require_once('../header.php');
    // a supprimer lorsque la conneion est possible
    $_SESSION['role'] = 'Moniteur';
    $_SESSION['nom'] = 'moniteur1';
    $_SESSION['id'] = '1';
    // jusqu'ici

    connexion_role('moniteur');

    require_once('../connexion_bdd.php');
    $requete = $bdd->prepare('SELECT * FROM voitures');
    $requete->execute();
    $voitures = $requete->fetchAll();
?>

    <h1>Ajouter un cours</h1>

    <form method="post" action="action_cours.php">
        <div class="form-group">
            <label for="nom">Cours de</label>
            <input type="text" readonly class="form-control" id="label" name="label" placeholder="ex : conduide" value="conduite" required>
        </div>     
        <div class="form-group">
            <label for="dateCour">Date du cours</label>
            <select class= "form-control" id="dateCour" name="dateCour" required>
            <option value="1">Lundi</option>
            <option value="2">Mardi</option>
            <option value="3">Mercredi</option>
            <option value="4">Jeudi</option>
            <option value="5">Vendredi</option>
            <option value="6">Samedi</option>
            </select>        
        </div>
        <div class="form-group">
            <label for="heureCour">Heure du cours</label>
            <select class= "form-control" id="heureCour" name="heureCour" required>
            <option value="1">7h-8h</option>
            <option value="2">8h-9h</option>
            <option value="3">9h-10h</option>
            <option value="4">10h-11h</option>
            <option value="5">11h-12h</option>
            <option value="6">13h-14h</option>
            <option value="7">14h-15h</option>
            <option value="8">15h-16h</option>
            </select>        
        </div>
        <div class="form-group">
            <label for="id_voiture">Selectionner la voiture</label>
            <select id="id_voiture" name="id_voiture" class="form-control" required>
                <?php foreach($voitures as $voiture): ?>
                    <option value="<?= $voiture['id'] ?>"> <?= htmlentities($voiture['immatriculation']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" name="action" value="ajouter">Ajouter</button>
        <a href="<?= $_SERVER['HTTP_REFERER']; ?>" class="btn btn-info">retour</a>
    </form>

<?php
    require_once('../footer.php');
?>