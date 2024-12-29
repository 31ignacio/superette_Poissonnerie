<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            font-size: 10px;
        }

        /* Ajoutez ces styles à votre CSS pour personnaliser la ligne "Montant dû" */
        .amount-due {
            background-color: green;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 10px;
        }


        .invoice {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #ccc;
            padding: 20px;
            font-size: 10px;

        }

        .invoice-header {
            text-align: center;
            background-color: #3498db;
            color: #fff;
            padding: 10px;
            font-size: 10px;

        }

        .invoice-header h1 {
            font-size: 28px;
            font-style: italic;
            margin: 0;
        }

        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            font-size: 10px;
        }

        .invoice-details .left {
            width: 50%;
        }

        .invoice-details .right {
            width: 50%;
            text-align: right;
        }

        .invoice-items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .invoice-items th,
        .invoice-items td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: left;
        }

        .invoice-items th {
            background-color: #f2f2f2;
        }

        .invoice-total {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .invoice-total .left {
            width: 50%;
            font-size: 10px;
        }

        .invoice-total .right {
            width: 50%;
            text-align: right;
            font-size: 15px;
        }

        .message {
            margin-top: 20px;
            font-style: italic;
            font-size: 10px;
            color: #555;
            text-align: center;
        }

        .caissier-name {
    font-family: 'VotrePoliceExceptionnelle', sans-serif; /* Remplacez 'VotrePoliceExceptionnelle' par le nom de votre police */
    font-weight: bold; /* Pour rendre le texte en gras */
    text-decoration: underline; /* Pour souligner le texte */
    /* Ajoutez d'autres styles CSS selon vos préférences */
}

    </style>
</head>

<body>
    <div class="invoice">
        <div class="invoice-header">
            <h1>Facture</h1>
        </div>
        <div style="position: absolute; top: 100px; right: 20px;">
            <p><b>Date:</b> {{ date('d/m/Y', strtotime($date)) }}</p>
            <p><b>Code facture: </b> {{ $code }}</p>
        </div>

        <div class="invoice-details">
            <div class="left">
                <p><b>De :</b> {{$info->nom}}</p>
                <p><b>Adresse :</b> {{$info->adresse}}</p>
                <p><b>Cél : </b>{{$info->telephone}}</p>
                <p><b>IFU : </b> {{$info->ifu}}</p>
            </div>

            @php
                $infosAffichees = false;
            @endphp

            @foreach ($factures as $facture)
                @if ($facture->date == $date && $facture->code == $code)
                    @if (!$infosAffichees)
                    @if($facture->client != "" )
                        <div class="right">
                            <p><b class="caissier-name">CLIENT </b> : {{ $facture->client }}</p>
                            {{-- <p><b>Téléphone :</b> {{ $facture->client->telephone }}</p> --}}
                            {{-- <p><b>N° IFU :</b> {{ $facture->client->ifu }}</p> --}}
                        </div>
                    @else

                        <div class="right">
                            <p><b class="caissier-name">CLIENT </b> : Client </p>
                            {{-- <p><b>Téléphone :</b> .....</p> --}}
                            {{-- <p><b>N° IFU :</b> {{ $facture->client->ifu }}</p> --}}
                        </div>
                    @endif

                        @php
                            $infosAffichees = true; // Marquer que les informations ont été affichées
                        @endphp
                    @endif
                @endif
            @endforeach
        </div>
        <table class="invoice-items">
            <thead>
                <tr>
                    <th>Quantité</th>
                    <th>Produits</th>
                    <th>Prix</th>
                    <th>Totals</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($factures as $facture)
                    @if ($facture->date == $date && $facture->code == $code)
                        <tr>
                            <td>{{ $facture->quantite }}</td>
                            <td>{{ $facture->produit }}</td>
                            <td>{{ $facture->prix }}</td>
                            <td>{{ $facture->total }}</td>
                        </tr>
                    @endif
                @endforeach

            </tbody>
        </table>
        <div class="invoice-total">
            @php
                $infosAffichees = false;
            @endphp

            @foreach ($factures as $facture)
                @if ($facture->date == $date && $facture->code == $code)
                    @if (!$infosAffichees)
                        <div class="left">
                            <p><b>Total HT : </b>  {{ $facture->totalHT }} FCFA</p>
                            <p><b>TVA (%) : </b>  {{ $facture->totalTVA }} FCFA</p>
                            <p><b>Total TTC : </b>  {{ $facture->totalTTC }} FCFA</p>
                            <p><b>Montant perçu : </b>  {{ $facture->montantPaye }} FCFA</p>
                            <p><b>Remise (%) : </b>  {{ $facture->reduction }} %</p>
                            <p><b>Reliquat :</b>  {{ $facture->montantPaye - $facture->montantFinal }} FCFA</p>
                            <p><b>Montant payé :</b> <span class="amount-due">{{ $facture->montantFinal }} FCFA</span></p>
                            {{-- <p><b>Montant final :</b> <span class="amount-due">{{ $facture->user->name }} FCFA</span></p> --}}


                        </div>
                        <div class="right">
                            <p>
                                <span class="caissier-name">Caissier(e) </span> <i> : {{ $facture->user->name }}</i>
                            </p>
                        </div>
                        @php
                            $infosAffichees = true; // Marquer que les informations ont été affichées
                        @endphp
                    @endif
                @endif
            @endforeach
        </div>
        <p class="message">Nous vous remercions pour votre confiance. N'hésitez pas à nous contacter pour toute question
            ou préoccupation.</p>
    </div>
</body>

</html>
