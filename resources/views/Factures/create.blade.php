@extends('layouts.master2')

@section('content')
    <div class="container">

        <!-- Wrapper avec une bordure et ombre légère pour le style -->
        <div class="callout callout-info shadow-sm p-4 rounded" style="background-color: #f8f9fa;">
            
            <h5 class="mb-4 text-primary"><i class="fas fa-info-circle"></i> Détails de la Transaction</h5>

            <div class="row">
                <!-- Date de la transaction -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date"><i class="fas fa-calendar-alt"></i> Date</label>
                        <input type="date" id="date" class="form-control" style="border-radius: 10px;" onkeydown="return false">
                    </div>
                </div>

                <!-- Sélection du Client -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="client"><i class="fas fa-user"></i> Clients</label>
                        <select class="form-control select2" id="client" required>
                            <option></option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }} {{ $client->nom }}" 
                                        @if ($client->nom == 'Client') selected @endif>
                                    {{ $client->nom }} {{ $client->prenom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Sélection du type de produit -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="produitType"><i class="fas fa-box"></i> Produit Type</label>
                        <select class="form-control" id="produitType" style="border-radius: 10px;">
                            <option></option>
                            
                            @foreach ($produitTypes as $produitType)
                                @if (($user->role_id == 2 || $user->role_id == 3) && in_array($produitType->id, [1, 2]))
                                    <option value="{{ $produitType->id }}">{{ $produitType->produitType }}</option>
                                @elseif ($user->role_id == 5 && $produitType->id == 3)
                                    <option value="{{ $produitType->id }}">{{ $produitType->produitType }}</option>
                                @elseif (!in_array($user->role_id, [2, 3, 5]))
                                    <option value="{{ $produitType->id }}">{{ $produitType->produitType }}</option>
                                @endif
                            @endforeach

                        </select>
                    </div>
                </div>
            </div>

            <form id="monFormulaire">
                <div id="msg25"></div>
                
                <div class="row align-items-center">
                    <!-- Sélection du Produit -->
                    <div class="mb-3 col-md-4">
                        <label for="produit"><i class="fas fa-cubes"></i> Produits</label>
                        <select class="form-control select2" id="produit">
                            <option></option>
                            <!-- Les produits sont chargés depuis le JS -->
                        </select>
                    </div>

                    <!-- Quantité -->
                    <div class="mb-3 col-md-4">
                        <label for="quantite"><i class="fas fa-sort-numeric-up"></i> Quantité</label>
                        <input type="number" value="0" min="0" class="form-control" id="quantite" style="border-radius: 10px;">
                        <div id="messagePro" class="text-danger mt-1"></div>
                    </div>

                    <input type="hidden" min=0 class="form-control" id='tva'>
                    <!-- Bouton d'ajout -->
                    <div class="mb-3 col-md-4">
                        <button type="button" class="btn btn-info mt-3" onclick="ajouterAuTableau()" title="Ajouter">
                            <i class="fas fa-plus"></i> Ajouter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- CSS pour un style plus propre et uniforme (pour l'entete) -->
        <style>
           
            .select2-container .select2-selection--single {
                height: 38px !important;
                border-radius: 15px !important;
            }
            
            .btn-info:hover {
                background-color: #138496;
                border-color: #117a8b;
            }
        </style>


        <!-- Main content -->
        <div class="invoice p-3 mb-3">
            <!-- title row -->
            <div class="row">
                <div class="col-12">

                </div>
                <!-- /.col -->
            </div>
            <!-- info row -->

            <!-- Table row -->
            <div class="row">
                {{-- <div class="col-md-2"></div> --}}
                <div class="col-12 table-responsive">
                    <table class="table table-striped" id="monTableau">
                        <thead>
                            <tr>
                                <th>Quantité</th>
                                <th>Produit</th>
                                <th>Prix</th>
                                <th>Total</th>
                            </tr>
                        </thead>

                        <tbody id="monTableauBody">
                            <!-- Les lignes de tableau seront ajoutées ici -->
                        </tbody>
                    </table>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">

                </div>
                <!-- /.col -->
                <div class="col-sm-12 col-md-6">
                    {{-- <p class="lead">Amount Due 2/22/2014</p> --}}

                    <div class="table-responsive">
                        <table class="table table-responsive">

                            <tr>
                                <th style="width:50%">Total HT:</th>
                                <td id="totalHT">0</td>
                            </tr>
                            <tr>
                                <th>Total TVA</th>
                                <td id="totalTVA">0</td>
                            </tr>
                            <tr>
                                <th>Total TTC</th>
                                <td id="totalTTC" class="right badge-md badge-info">0</td>
                            </tr>
                            <tr>
                                <th>Montant perçu</th>
                                <td class="d-flex">
                                    <input type="text" class="form-control mr-2" id="montantPaye" required
                                        oninput="ajouterValider()">
                                    {{-- <a href="#" class="btn btn-sm btn-success"><i class="fas fa-check"></i></a> --}}
                                </td>

                            </tr>
                            <tr>
                                <th>Reliquat</th>
                                <td><input type="text" class="form-control" id="montantRendu" required
                                        style="background-color: rgb(246, 222, 4)" disabled></td>

                            </tr>
                            <tr>
                                <th>Remise(%) </th>
                                <td><input type="text" class="form-control" id="remise" required
                                        oninput="ajouterValider()"></td>

                            </tr>
                            <tr>
                                <th>Montant payé</th>
                                <td class="d-flex">
                                    <input type="text" class="form-control mr-2" id="montantFinal"
                                        style="background-color: rgb(4, 246, 4)" required disabled>
                                    {{-- <button type="button" class="btn btn-sm btn-success"  title="valider"><i class="fas fa-check"></i></button>    --}}


                                </td>

                            </tr>

                        </table>
                        <div id="msg30"></div>

                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- this row will not appear when printing -->
            <div class="row no-print">
                <div class="col-12">
                    

                    <button type="button" class="btn btn-info float-right valider"
                        style="margin-right: 5px;"onclick="enregistrerDonnees()">
                        <i class="fas fa-download"></i> Valider
                    </button>

                </div>
            </div>
        </div>
        <!-- /.invoice -->
        <div id="msg200"></div>

    </div>

    <script src="../../../../AD/toastify-js-master/src/toastify.js"></script>

    {{-- Ajouter produit dans le tableau --}}
    <script>
        function ajouterAuTableau() {
            // Récupérer les valeurs du formulaire
            var quantite = document.getElementById("quantite").value;
            var produit = document.getElementById("produit").value;
            var client = document.getElementById("client").value;
            var selectProduit = $('#produit');
            var prix = $('option:selected', selectProduit).data('prix');
    
            if (quantite.trim() == "" || isNaN(parseFloat(quantite)) || produit.trim() == "" || client.trim() == "") {
                $('#msg25').html(`<p class="text-danger">
                                    <strong>Veuillez remplir tous les champs (quantité, produit, client) avec des valeurs valides.</strong>
                                </p>`);
                setTimeout(function() {
                    $('#msg25').html('');
                }, 5000);
            } else {
                var total = quantite * prix;
                var tableauBody = document.getElementById("monTableauBody");
                var newRow = tableauBody.insertRow(tableauBody.rows.length);
    
                var cell1 = newRow.insertCell(0);
                var cell2 = newRow.insertCell(1);
                var cell3 = newRow.insertCell(2);
                var cell4 = newRow.insertCell(3);
                var cell5 = newRow.insertCell(4);
    
                cell1.innerHTML = quantite;
                cell2.innerHTML = produit;
                cell3.innerHTML = prix;
                cell4.innerHTML = total.toFixed();
    
                // Ajouter un bouton de suppression dans la cinquième cellule
                var deleteButton = document.createElement("button");
                deleteButton.innerHTML = '<i class="fas fa-trash"></i>';
                deleteButton.className = "btn btn-sm btn-danger";
                deleteButton.onclick = function() {
                    var row = this.parentNode.parentNode;
                    tableauBody.deleteRow(row.rowIndex - 1);
                    mettreAJourTotalHT();
                };
                cell5.appendChild(deleteButton);
    
                mettreAJourTotalHT();
    
                document.getElementById("quantite").value = "";
                document.getElementById("prix").value = "";
            }
        }

        function mettreAJourTotalHT() {
            var tva = document.getElementById("tva").value;
            var tableauBody = document.getElementById("monTableauBody");
            var totalHT = 0;
    
            for (var i = 0; i < tableauBody.rows.length; i++) {
                var cell = tableauBody.rows[i].cells[3];
                totalHT += parseFloat(cell.innerHTML) / 1.18;
            }
            var totalTVA = (totalHT * 18) / 100;
            var totalTTC = totalHT + totalTVA;
    
            document.getElementById("totalHT").innerHTML = totalHT.toFixed(2);
            document.getElementById("totalTVA").innerHTML = totalTVA.toFixed(2);
            document.getElementById("totalTTC").innerHTML = totalTTC.toFixed(2);
        }
    </script>

    {{-- Valider pour montant final --}}
    <script>
        function ajouterValider() {
            // Récupérer les valeurs du formulaire
            var montantPercu = parseFloat(document.getElementById("montantPaye").value);
            var remise = document.getElementById("remise").value;
            var totalTTC = document.getElementById('totalTTC').innerText;
            var montantRendu = 0;

            if (montantPercu == "" || totalTTC == 0) {
                // Ajoutez ici le code pour afficher un message d'erreur ou faites une action appropriée
                $('#msg30').html(` <p  class="text-danger">
                        <strong>Veuillez remplir tous les champs.</strong>
                                    </p>`);
                // Masquer le message après 3 secondes
                setTimeout(function() {
                    $('#msg30').html('');
                }, 5000); // 3000 millisecondes équivalent à 3 secondes
            } else {

                // Calculer le total en multipliant la quantité par le prix
                if (isNaN(remise)) {
                    // Si la remise n'est pas un nombre, utilisez le totalTTC directement
                    var montantFinal = totalTTC;
                } else {
                    // Si la remise est un nombre, calculez le montant final en appliquant la remise
                    var montantFinal = totalTTC - (totalTTC * remise) / 100;
                }

                var montantRendu = montantPercu - montantFinal;

                document.getElementById("montantFinal").value = montantFinal
                    .toFixed(); // Afficher le total avec deux décimales

                //alert(montantRendu)
                document.getElementById("montantRendu").value = montantRendu
                    .toFixed(); // Afficher le total avec deux décimales

            }
        }
    </script>


    {{-- Enregistrer une facture --}}
    <script>
        function enregistrerDonnees(donnees,button) {
            // Récupérer toutes les lignes du tableau
            var tableauBody = document.getElementById("monTableauBody");
            var date = document.getElementById("date").value;
            var client = document.getElementById("client").value;
            var totalHT = document.getElementById("totalHT").textContent;
            var totalTVA = document.getElementById("totalTVA").textContent;
            var totalTTC = document.getElementById("totalTTC").textContent;
            var montantPaye = document.getElementById("montantPaye").value;
            var produitType = document.getElementById("produitType").value;
            var remise = document.getElementById("remise").value;
            var montantFinal = document.getElementById("montantFinal").value;
        //  alert(montantPaye)
            //alert(totalTTC)

            if (produitType == "") {
                $('#msg30').html(`
        <p class="text-danger">
            <strong>Veuillez remplir tous les champs obligatoires.</strong>
        </p>`);

                // Masquer le message après 3 secondes
                setTimeout(function() {
                    $('#msg30').html('');
                }, 2000);

        } else if (montantPaye == "") {
            // Afficher un message d'erreur si le montant payé est vide
            $('#msg30').html(`
                <p class="text-danger">
                    <strong>Le montant perçu est vide.</strong>
                </p>`
            );

            // Masquer le message après 3 secondes
            setTimeout(function() {
                $('#msg30').html('');
            }, 3000);
        }else {

                var donnees = [];

                for (var i = 0; i < tableauBody.rows.length; i++) {
                    var ligne = tableauBody.rows[i];
                    var quantite = ligne.cells[0].textContent;
                    var produit = ligne.cells[1].textContent;
                    var prix = ligne.cells[2].textContent;
                    var total = ligne.cells[3].textContent;
                    //alert(totalHT)
                    donnees.push({
                        quantite: quantite,
                        produit: produit,
                        prix: prix,
                        total: total
                    });

                }
                $('.valider').hide();

                // Envoyer les données au serveur via une requête AJAX
                $.ajax({

                    type: "POST",
                    url: "{{ route('facture.store') }}", // L'URL de votre route Laravel
                    data: {
                        _token: '{{ csrf_token() }}',
                        donnees: JSON.stringify(donnees),
                        client,
                        date,
                        totalTTC,
                        totalHT,
                        totalTVA,
                        montantPaye,
                        produitType,
                        remise,
                        montantFinal
                    },
                    success: function(response) {
                        var routeURL =
                            "http://127.0.0.1:8000/facture"; // Remplacez ceci par l'URL réelle de la route

                        Toastify({
                            text: "Félicitations, la facture a été enregistrée avec succès !",
                            duration: 5000,
                            close: true,
                            gravity: "top", // Position du toast
                            backgroundColor: "#4CAF50", // Fond vert
                            className: "your-custom-class", // Classe CSS personnalisée
                            stopOnFocus: true, // Arrêter le temps lorsque le toast est en focus
                            onClose: function() {
                                window.location.href = routeURL;
                            }

                        }).showToast();

                        var url = "{{ route('accueil.index') }}"
                        setTimeout(function() {
                            window.location = url
                        }, 3000)

                    },

                });
            }
        }
    </script>

    {{-- verifier le stock --}}
    <script>
        var quantiteInput = document.getElementById("quantite");
        var produitSelect = document.getElementById("produit");
        var message = document.getElementById("messagePro");
        var previousValue = quantiteInput.value;
        var previousSelectedIndex = produitSelect.selectedIndex;

        quantiteInput.addEventListener("input", function() {
            validateQuantite();
        });

        produitSelect.addEventListener("change", function() {
            validateQuantite();
        });

        function validateQuantite() {
            var selectedOption = produitSelect.options[produitSelect.selectedIndex];
            var stock = parseFloat(selectedOption.getAttribute("data-stock"));
            var quantite = parseFloat(quantiteInput.value);

            if (isNaN(quantite) || isNaN(stock) || quantite <= stock) {
                message.textContent = "";
                quantiteInput.style.borderColor = "";
            } else {
                message.textContent = "Stock insuffisant!";
                quantiteInput.style.borderColor = "red";

                // Efface le champ de quantité après 3 secondes
                setTimeout(function() {
                    quantiteInput.value = "";
                }, 100);
            }

            // Vérifiez si l'utilisateur a changé de produit
            if (produitSelect.selectedIndex !== previousSelectedIndex) {
                quantiteInput.value = "";
                previousSelectedIndex = produitSelect.selectedIndex;
            }

            // Vérifiez si la quantité a été modifiée manuellement
            if (quantiteInput.value !== previousValue) {
                previousSelectedIndex = produitSelect.selectedIndex;
            }
        }
        // Vous pouvez appeler validateQuantite() au chargement de la page pour vérifier la quantité initiale
        validateQuantite();
    </script>

    {{-- Control sur la date --}}
    <script>
        // Récupérer la date d'aujourd'hui
        var dateActuelle = new Date();
        var annee = dateActuelle.getFullYear();
        var mois = ('0' + (dateActuelle.getMonth() + 1)).slice(-2);
        var jour = ('0' + dateActuelle.getDate()).slice(-2);
    
        // Formater la date pour l'attribut value de l'input
        var dateAujourdhui = annee + '-' + mois + '-' + jour;
    
        // Définir la valeur et la propriété max de l'input
        var inputDate = document.getElementById('date');
        inputDate.value = dateAujourdhui;
        inputDate.max = dateAujourdhui;
    </script>


    <!-- JavaScript pour la mise à jour dynamique (Produit type et produit) -->
    <script>
        // Fonction pour mettre à jour la liste des produits en fonction du Produit Type sélectionné
        function updateProduits() {
            var produitTypeSelect = document.getElementById('produitType');
            var produitsSelect = document.getElementById('produit');

            // Obtient la valeur sélectionnée du Produit Type
            var selectedProduitType = produitTypeSelect.value;

            // Efface les options précédentes
            produitsSelect.innerHTML = '<option></option>';

            // Filtrage des produits en fonction du Produit Type sélectionné
            @foreach ($produits as $produit)
                if ("{{ $produit->produitType_id }}" == selectedProduitType) {
                    var option = document.createElement('option');
                    option.value = "{{ $produit->libelle }}";
                    option.setAttribute('data-prix', "{{ $produit->prix }}");
                    option.setAttribute('data-stock', "{{ $produit->stock_actuel }}");

                    option.textContent = "{{ $produit->libelle }}";
                    produitsSelect.appendChild(option);
                }
            @endforeach
        }

        // Ajoute un écouteur d'événements pour détecter les changements dans le Produit Type
        document.getElementById('produitType').addEventListener('change', updateProduits);

        // Appelle la fonction updateProduits initialement pour configurer la liste des produits
        updateProduits();
    </script>

@endsection
