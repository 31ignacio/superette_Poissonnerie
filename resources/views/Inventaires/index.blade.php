@extends('layouts.master2')

@section('content')

    <section class="content" >
        <div class="container">

            <div class="row">
                <div class="col-md-10"></div>
                <div class="col-md-2 mt-3">
                <button class="btn btn-danger" onclick="generatePDF()"><i class="fas fa-download"></i> Générer PDF</button>
                </div>
            </div>


            <div class="row">
                <div class="col-12 mt-4">

                    

                    <div class="card" id="my-table"><br>
                        <div class="row">
                            <div class="col-12">
                                <h5>
                                    <i class="fas fa-globe mx-3"></i> <b>Léoni's Superette</b> <br>
                                    <small class="float-right my-3"><b>Date:</b> {{ date('d/m/Y', strtotime($today)) }}</small><br><br>
                                    <small class="float-left mx-3"><b>IFU :</b> 01234567891011</small><br>
                                    <small class="float-left mx-3"><b>Téléphone :</b> (229) 0197472907 / 0161233719 </small>
                                </h5>
                            </div>
                            <!-- /.col -->
                        </div>

                        <div class="card-header">
                            <h5 class="text-center"><b> Écart d'inventaire détails du {{ date('d/m/Y', strtotime($today)) }}</b></h5>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            @php
                            $totalMontant = 0;
                            @endphp
                            <table class="table table-bordered table-responsive">
                                <thead>
                                    <tr>
                                        <th>Produits</th>
                                        <th>Stock actuel</th>
                                        <th>PU</th>
                                        <th>Type</th>
                                        <th hidden>Montant</th>
                                        <th>Stock Physique</th>
                                        <th>Écart d'Inventaire</th>
                                        <th hidden>Montant P</th>
                                        <th>Montant d'écart</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    @foreach ($produits as $produit)
                                        <?php
                                            // Calculer le montant pour ce produit
                                            $montant = $produit->prix * $produit->stock_actuel;
                                            $totalMontant += $montant; // Ajouter le montant au total
                                            $montant_format = number_format($montant, 0, ',', '.');
                                        ?>
                                        <tr>
                                            <td>{{ $produit->libelle }}</td>
                                            <td>{{ $produit->stock_actuel }}</td>
                                            <td>{{ $produit->prix }}</td>
                                            <td>
                                                @if( $produit->produitType_id == 1)
                              
                                                    <span class="badge badge-success">Détails</span>

                                                @elseif( $produit->produitType_id ==3 )
                                                            
                                                    <span class="badge badge-warning">Poissonerie</span>

                                                @else
                                                <span class="badge badge-primary">Gros</span>
                                                @endif

                                            </td>
                                            <td hidden>
                                                <b class="info-box-number montant-produit">{{ $montant_format }} FCFA</b>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control stock-physique" data-stock-actuel="{{ $produit->stock_actuel }}" data-prix="{{ $produit->prix }}" placeholder="Saisir le stock physique">
                                            </td>
                                            <td class="ecart-inventaire">0</td>
                                            <td class="montant-physique" hidden>
                                                <b class="info-box-number">0 FCFA</b>
                                            </td>
                                            <td>
                                                <b class="info-box-number difference-montant">{{ $montant_format }} </b>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>

                            <div class="row">
                                <div class="col-md-8"></div>
                                <div class="col-md-4 total-container">
                                    <span class="total-label">Ecart :</span>
                                    <div class="total-amount" id="total-montant"></div>
                                </div>
                            </div>

                        </div>
                        <!-- /.card-body -->
                    </div>

                <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
                <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- JavaScript -->

    <!-- css pour ecart en bas -->
    <style>
        .montant-rouge{
            color:red;
        }
        .total-container {
            font-size: 25px;
            display: flex;
            align-items: center;
        }
        .total-label {
            margin-right: 10px;
        }
        .total-amount {
            font-size: 20px;
            color: white;
            background-color: red;
            border-radius: 10px;
            padding: 5px 10px;
        }
    </style>

    <!-- total ecart -->

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const stockPhysiqueInputs = document.querySelectorAll('.stock-physique');

            stockPhysiqueInputs.forEach(input => {
                input.addEventListener('input', function () {
                    const stockActuel = parseFloat(this.getAttribute('data-stock-actuel'));
                    const prix = parseFloat(this.getAttribute('data-prix'));
                    const stockPhysique = parseFloat(this.value) || 0;
                    const ecart = stockPhysique - stockActuel;

                    // Mettre à jour l'écart d'inventaire
                    const ecartCell = this.parentElement.nextElementSibling;
                    ecartCell.textContent = ecart;

                    // Calculer et mettre à jour le montant physique
                    const montantPhysiqueCell = ecartCell.nextElementSibling.querySelector('.info-box-number');
                    const montantPhysique = prix * stockPhysique;
                    montantPhysiqueCell.textContent = new Intl.NumberFormat('fr-FR').format(montantPhysique) + ' FCFA';

                    // Calculer la différence de montant
                    const montantProduitCell = this.parentElement.previousElementSibling.querySelector('.montant-produit').textContent;
                    const montantProduit = parseFloat(montantProduitCell.replace(/\D/g, ''));
                    const differenceMontant = montantProduit - montantPhysique;

                    // Mettre à jour la différence de montant avec signe si négatif
                    const differenceMontantCell = montantPhysiqueCell.parentElement.nextElementSibling.querySelector('.difference-montant');
                    const differenceMontantFormatted = differenceMontant < 0 
                        ? '-' + new Intl.NumberFormat('fr-FR').format(Math.abs(differenceMontant))
                        : new Intl.NumberFormat('fr-FR').format(differenceMontant);

                    differenceMontantCell.textContent = differenceMontantFormatted + ' FCFA';
                    differenceMontantCell.classList.add('montant-rouge');

                    // Recalculer la somme totale des montants
                    updateTotalMontant();
                });
            });

            function updateTotalMontant() {
                // Sélectionnez tous les éléments avec la classe difference-montant
                const montantElements = document.querySelectorAll('.difference-montant');

                // Initialisez la somme totale à zéro
                let totalMontant = 0;

                // Parcourez chaque élément et ajoutez son montant à la somme totale
                montantElements.forEach(element => {
                    // Récupérez le texte de l'élément et supprimez ' FCFA' s'il est présent
                    const montantText = element.textContent.replace(' FCFA', '');

                    // Convertissez le texte en nombre en supprimant les séparateurs de milliers et en remplaçant la virgule par un point
                    const montant = parseFloat(montantText.replace(/\s|FCFA/g, '').replace(',', '.'));

                    // Ajoutez le montant à la somme totale
                    totalMontant += montant;
                });

                // Affichez la somme totale en bas de la colonne
                const totalMontantElement = document.getElementById('total-montant');
                totalMontantElement.textContent = new Intl.NumberFormat('fr-FR').format(totalMontant) + ' FCFA';
            }

            // Appeler la fonction pour afficher la somme initiale
            updateTotalMontant();
        });
    </script>


    <!-- tableau ecart -->

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const stockPhysiqueInputs = document.querySelectorAll('.stock-physique');

            stockPhysiqueInputs.forEach(input => {
                input.addEventListener('input', function () {
                    const stockActuel = parseFloat(this.getAttribute('data-stock-actuel'));
                    const prix = parseFloat(this.getAttribute('data-prix'));
                    const stockPhysique = parseFloat(this.value) || 0;
                    const ecart = stockPhysique - stockActuel;

                    // Mettre à jour l'écart d'inventaire
                    const ecartCell = this.parentElement.nextElementSibling;
                    ecartCell.textContent = ecart;

                    // Calculer et mettre à jour le montant physique
                    const montantPhysiqueCell = ecartCell.nextElementSibling.querySelector('.info-box-number');
                    const montantPhysique = prix * stockPhysique;
                    montantPhysiqueCell.textContent = new Intl.NumberFormat('fr-FR').format(montantPhysique) + ' FCFA';

                    // Calculer la différence de montant
                    const montantProduitCell = this.parentElement.previousElementSibling.querySelector('.montant-produit').textContent;
                    const montantProduit = montantProduitCell.replace(/\D/g,'');
                    const differenceMontant = montantProduit - montantPhysique;
                    console.log(montantProduit,montantPhysique,differenceMontant);

                    // Mettre à jour la différence de montant
                    const differenceMontantCell = montantPhysiqueCell.parentElement.nextElementSibling.querySelector('.difference-montant');
                    differenceMontantCell.textContent = new Intl.NumberFormat('fr-FR').format(differenceMontant);
                    differenceMontantCell.classList.add('montant-rouge');
                });
            });
        });
    </script>


    <script>
        // Définir la fonction generatePDF à l'extérieur de la fonction click
        function generatePDF() {
            // Récupérer le contenu du tableau HTML
            var element = document.getElementById('my-table');

            // Obtenez la date actuelle
            var today = new Date();

            // Formatez la date en yyyy-mm-dd sans padStart
            var day = ('0' + today.getDate()).slice(-2);
            var month = ('0' + (today.getMonth() + 1)).slice(-2); // Les mois commencent à 0
            var year = today.getFullYear();

            // Construisez la chaîne de date
            var formattedDate = year + '-' + month + '-' + day;

            // Créez le nom de fichier avec la date du jour
            var filename = 'Ecart_inventaire_détails_du_' + formattedDate + '.pdf';
    
            // Options pour la méthode html2pdf
            var opt = {
                margin: 0.5,
                filename: filename,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'a4', orientation: 'landscape' }
            };
    
            // Utiliser la méthode html2pdf pour générer le PDF à partir du contenu du tableau HTML
            html2pdf().from(element).set(opt).save();
        }
    </script>
@endsection
  
