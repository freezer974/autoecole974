<?php require_once('../header.php');?>
<?php require_once('../connexion_bdd.php');?>

<!-- formulaire -->
<form method="POST" action="inscription_post.php">
    <fieldset> <legend>Créer son compte</legend> 
        <p>           
            <!-- ajouter-->
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Nom et Prénom :</span>
                </div>
                <input type="mail" class="form-control" name="nom" placeholder="" required>
                <input type="mail" class="form-control" name="prenom" placeholder="" required>
            </div>

            <div class="input-group mb-3">

                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">E-mail :</span>
                </div>
                    <input type="mail" class="form-control" name="mail" placeholder="" required>
            </div>

            <div class="input-group mb-3">

                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Mot de passe :</span>
                </div>
                    <input type="password" class="form-control" name="password" placeholder="" required>
            </div>
            
            <div class="input-group mb-3">

                    <div class="input-group-prepend">
                        <label class="input-group-text" for="inputGroupSelect01">Role</label>
                    </div>
                <select class="custom-select" name="id" id="id" required>

                    <!-- Récupération de l'ID dans la table roles -->
                    <?php $role = $bdd->query('SELECT * FROM roles');

                    while ($read = $role->fetch()) : ?>
                        <option value="<?php echo $read['id']?>"><?php echo $read['label']?></option>
                    <?php endwhile; ?>

                </select>
            </div>
        </p>
        <?php 

        ?>
        <input type="submit" name="envoi" value="Créer le compte" class="btn btn-outline-success">
    </fieldset>
</form>

<?php require_once('../footer.php');?>
