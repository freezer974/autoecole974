$(document).ready(function() {
    $('.datepicker').datepicker({
        language: 'fr',
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
        });    
    $('#genres').select2();
    $('#series').select2();

    $("#message").fadeTo(2000, 500).slideUp(500, function(){
        $("#message").slideUp(500);
    });

    $('#ajoutModal').on('show.bs.modal', function (event) {
        var a = $(event.relatedTarget); // Button that triggered the modal
        var horaire = a.data('horaire') // Extract info from data-* attributes
        var dateCour = a.data('datecour') // Extract info from data-* attributes
        var heureCour = a.data('heurecour') // Extract info from data-* attributes
        var nom = a.data('nom') // Extract info from data-* attributes
        var role = a.data('role') // Extract info from data-* attributes
        var id_utilisateur = a.data('id_utilisateur') // Extract info from data-* attributes
        var label = a.data('label') // Extract info from data-* attributes
        var jour = a.data('jour') // Extract info from data-* attributes
        var voitures = a.data('voitures'); // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this);
        modal.find('.modal-title')
            .text('Ajouter un cours de ' + label)
        modal.find('.modal-body p')
            .text(nom + ' voulez vous ajouter un cours de ' + label + ' à ' + horaire + ' ' + jour)
        modal.find('.modal-body p').wrap("<form class='form' method='POST' action='action_cours.php'></form>");
        modal.find('#action')
            .attr({
                value: 'ajouter',
                type: 'submit'
            });

        modal.find('#dateCour').attr('value', dateCour);
        modal.find('#heureCour').attr('value', heureCour);
        modal.find('#label').attr('value', label);
        modal.find('#id_utilisateur').attr('value', id_utilisateur);

        modal.find('select').remove('option');

        for(i=0; i < voitures.length; i++)
        {
            modal.find('select').append('<option value=' + voitures[i].id + '>' + voitures[i].immatriculation +'</option>');
        }
    });

    $('#annuleModal').on('show.bs.modal', function (event) {
        var a = $(event.relatedTarget); // Button that triggered the modal
        var horaire = a.data('horaire') // Extract info from data-* attributes
        var dateCour = a.data('datecour') // Extract info from data-* attributes
        var heureCour = a.data('heurecour') // Extract info from data-* attributes
        var nom = a.data('nom') // Extract info from data-* attributes
        var role = a.data('role') // Extract info from data-* attributes
        var id = a.data('id') // Extract info from data-* attributes
        var label = a.data('label') // Extract info from data-* attributes
        var jour = a.data('jour') // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this);
        modal.find('.modal-title')
            .text('Annulation d\'un cours de ' + label)
        modal.find('.modal-body p')
            .text(nom + ' voulez vous annuler le cours de ' + label + ' à ' + horaire + ' ' + jour)
        modal.find('.modal-body p').wrap("<form class='form' method='POST' action='action_cours.php'></form>");
        modal.find('#action')
            .attr({
                value: 'annuler',
                type: 'submit'
            });

        modal.find('#dateCour').attr('value', dateCour);
        modal.find('#heureCour').attr('value', heureCour);
        modal.find('#label').attr('value', label);
        modal.find('#id').attr('value', id);
    })

    $('#reserveModal').on('show.bs.modal', function (event) {
        var a = $(event.relatedTarget); // Button that triggered the modal
        var horaire = a.data('horaire') // Extract info from data-* attributes
        var dateCour = a.data('datecour') // Extract info from data-* attributes
        var heureCour = a.data('heurecour') // Extract info from data-* attributes
        var nom = a.data('nom') // Extract info from data-* attributes
        var role = a.data('role') // Extract info from data-* attributes
        var id = a.data('id') // Extract info from data-* attributes
        var label = a.data('label') // Extract info from data-* attributes
        var jour = a.data('jour') // Extract info from data-* attributes
        var nomMoniteur = a.data('nommoniteur'); // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this);
        modal.find('.modal-title')
            .text('Réservation d\'un cours de ' + label)
        modal.find('.modal-body p')
            .text(nom + ' voulez vous reserver le cours de ' + label + ' à ' + horaire + ' ' + jour + ' avec ' + nomMoniteur) 
        modal.find('.modal-body p').wrap("<form class='form' method='POST' action='action_cours.php'></form>");
        modal.find('#action')
            .attr({
                value: 'reserver',
                type: 'submit'
            });

        modal.find('#dateCour').attr('value', dateCour);
        modal.find('#heureCour').attr('value', heureCour);
        modal.find('#label').attr('value', label);
        modal.find('#id').attr('value', id);
    })

    $('#annuleReservationModal').on('show.bs.modal', function (event) {
        var a = $(event.relatedTarget); // Button that triggered the modal
        var horaire = a.data('horaire') // Extract info from data-* attributes
        var dateCour = a.data('datecour') // Extract info from data-* attributes
        var heureCour = a.data('heurecour') // Extract info from data-* attributes
        var nom = a.data('nom') // Extract info from data-* attributes
        var role = a.data('role') // Extract info from data-* attributes
        var id_cours = a.data('id_cours') // Extract info from data-* attributes
        var label = a.data('label') // Extract info from data-* attributes
        var jour = a.data('jour') // Extract info from data-* attributes
        var nomMoniteur = a.data('nommoniteur'); // Extract info from data-* attributes
        var id_eleve = a.data('id_eleve');
        var nom_eleve = a.data('nom_eleve');
        var finDePhrase = '';

        if (nom_eleve == ''){
            finDePhrase = ' avec ' + nomMoniteur;
        } else {
            finDePhrase = ' pour l\'élève ' + nom_eleve +'<br /> <strong>Si vous validez</strong>, un email lui notifira de votre annulation.';
        }
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this);
        modal.find('.modal-title')
            .text('Annulation de réservation d\'un cours de ' + label)
        modal.find('.modal-body p')
            .html(nom + ' voulez vous annuler le cours de ' + label + ' à ' + horaire + ' ' + jour + finDePhrase) 
        modal.find('.modal-body p').wrap("<form class='form' method='POST' action='action_cours.php'></form>");
        modal.find('#action')
            .attr({
                value: 'annuleReservation',
                type: 'submit'
            });

        modal.find('#dateCour').attr('value', dateCour);
        modal.find('#heureCour').attr('value', heureCour);
        modal.find('#label').attr('value', label);
        modal.find('#id_eleve').attr('value', id_eleve);
        modal.find('#id_cours').attr('value', id_cours);
    })

    $( "#id_utilisateur" ).change(function () {

        if ($(this).val() == ''){
            return;
        }
      var params = [];
      $( "select option:selected" ).each(function() {
        params['nom'] = $(this).attr('data-lastname');
        params['prenom'] = $(this).attr('data-firstname');
        params['email'] = $(this).attr('data-email');
        params['role'] = $(this).attr('data-role');
        params['id'] = $(this).val();
        params['action'] = 'session';
      });
      post_en_url("action_cours.php", params);
    })

    function post_en_url(url, parametres) {
        //Création dynamique du formulaire
        var form = $('<form>');
        form.attr('method', 'POST');
        form.attr('action', url);
        //Ajout des paramètres sous forme de champs cachés
        for(var cle in parametres) {
            if(parametres.hasOwnProperty(cle)) {
                var champCache = $('<input/>');
                champCache.attr('type', 'hidden');
                champCache.attr('name', cle);
                champCache.attr('value', parametres[cle]);
                form.append(champCache);
            }
        }
        //Ajout du formulaire à la page et soumission du formulaire
        $(document.body).append(form);
        form.submit();
    }


})



