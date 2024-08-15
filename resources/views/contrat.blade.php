@php
    use App\Models\BureauPoste;
    $bureau = BureauPoste::find($record->code_bureau);
@endphp

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Oracle Reports">
    <title>local.pdf</title>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            text-indent: 0;
        }

        body {
            margin: 1cm 1cm 0 1cm;
            /* Marge en haut, droite, bas, gauche */
        }



        p {
            color: black;
            font-family: "Courier New", monospace;
            font-size: 8pt;
            margin: 0pt;
        }

        .s1 {
            font-family: Arial, sans-serif;
            font-style: italic;
            font-weight: bold;
            font-size: 10pt;
            text-align: center;
        }

        .s2 {
            font-family: Arial, sans-serif;
            font-style: italic;
            font-weight: bold;
            font-size: 8pt;
            text-align: center;
        }

        h1 {
            font-family: "Courier New", monospace;
            font-weight: bold;
            text-decoration: underline;
            font-size: 10pt;
            padding-left: 5pt;
            margin-top: 5pt;
        }

        .s3 {
            font-family: "Courier New", monospace;
            font-weight: bold;
            font-size: 8pt;
            padding-left: 5pt;
            margin-top: 2pt;
        }

        .a {
            font-family: "Courier New", monospace;
            font-size: 8pt;
        }
        .top-left-image {
            position: fixed;
            top: 15;
            left: 29;
            width: 100px; /* Ajustez la largeur selon vos besoins */
            height: auto;
            z-index: 1000; /* S'assure que l'image reste au-dessus des autres éléments */
        }
    </style>


</head>

<body>

    <div class="container">
        <!-- Conteneur pour l'image -->
        <div class="image-container">
            <img src="logo_poste.png" alt="Top Left Image" class="top-left-image">
        </div>

        <!-- Conteneur pour le texte -->
        <div class="text-container">
            <p style="text-indent: 0pt; text-align: left"><br /></p>
            <p class="s1">LA POSTE</p>
            <p class="s2">SOCIETE DES POSTES DU TOGO</p>
            <h1 class="s2">CONTRAT D'ABONNEMENT A LA BOITE POSTALE</h1>
        </div>
    </div>




    <br>

    <p style="padding-left: 5pt; text-indent: 0pt; text-align: left">
        Opération n° : {{ $record->id_operation }}
    </p>

    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 124%;">
        Référence contrat : {{ $record->ref_contrat }}
    </p>

    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 124%;">
        Entre les soussignés
    </p>

    <br>
    <br>


    <p style="padding-left: 5pt; text-indent: 0pt; text-align: left">
        La Société des Postes du Togo (SPT), située sur l'Avenue Nicolas Grunitzky 01 BP 2626 Lomé 01, Téléphone
        (00228)22 21 44 03, représentée par M. Kwadzo Dzodzro KWASI, son Directeur Général d'une part.
    </p>

    <br>

    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 124%;">
        Titulaire de la boîte : <b>{{ $record->titre }}. {{ $record->nom_abonne }} {{ $record->prenom_abonne }}</b>
    </p>

    <p style="padding-left: 5pt; text-indent: 0pt; text-align: left">
        Raison sociale : {{ $record->raison_sociale }}
    </p>

    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 124%;">
        Premier responsable : {{ $record->premier_resp }}
    </p>

    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 124%;">
        Nationalité : {{ $record->nationalite }}
    </p>

    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 124%;">
        Téléphone fixe : {{ $record->tel_fixe }}
    </p>


    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 124%;">
        Téléphone mobile : {{ $record->telephone }}
    </p>

    <p style="padding-left: 5pt; text-indent: 0pt; text-align: left">
        <a href="mailto:{{ $record->email }}" class="a" target="_blank">Email : {{ $record->email }}</a>
    </p>

    <br>

    <p style="padding-left: 5pt; text-indent: 0pt; text-align: left">
        Dénommé &lt;&lt;l'Abonné&gt;&gt; d'autre part.
    </p>

    <br>

    <p class="s3">ARTICLE 1: <u>OBJET</u></p>
    <p style="padding-left: 5pt; text-indent: 0pt; text-align: left">
        Le présent contrat a pour objet de définir les conditions d'admission et d'abonnement au service boîte postale.
    </p>

    <p class="s3">ARTICLE 2: <u>CATEGORISATION DES ABONNES</u></p>
    <p style="padding-left: 10pt; text-indent: 0pt; text-align: justify;">
        1 - Abonné &lt;&lt; Personne Physique&gt;&gt;<br>
        2 - Abonné &lt;&lt; Personne Morale&gt;&gt;<br>
        3 - Abonné &lt;&lt; Administration&gt;&gt;
    </p>

    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 124%; text-align: justify;">
        NB: Lorsqu'une boîte poste dont l'abonnement a été souscrit par une &lt;&lt;personne physique&gt;&gt; est
        utilisée par la Société, Etablissement, Association, ONG, Ambassade, etc. (personne morale), ladite boîte
        postale est reclassée d'office dans la catégorie Abonné &lt;&lt;Personne Morale&gt;&gt;. Dans ce cas, le premier
        contrat devient caduc.
    </p>

    <p class="s3">ARTICLE 3: <u>FRAIS D'ABONNEMENT</u></p>



    <p style="padding-top: 2pt; padding-left: 5pt; text-indent: 0pt; line-height: 124%; text-align: left;">
        3.1. La location postale est payante. Le montant exigible varie selon les critères définis par LA POSTE. Tout
        nouveau tarif devra être notifié aux abonnés au moins un (1) mois avant sa mise en vigueur.
    </p>

    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 124%; text-align: left;">
        3.2. Les frais d'abonnement sont payables à la souscription du contrat et au début d'une nouvelle période de
        consommation, ceci avant l'exécution de la prestation du service.
    </p>

    <p class="s3" style="padding-left: 5pt; text-indent: 0pt; text-align: left">
        ARTICLE 4: <u>CONCESSION DE BOITE</u>
    </p>

    <p style="padding-top: 2pt; padding-left: 5pt; text-indent: 0pt; line-height: 124%; text-align: left;">
        Il est concédé à l'abonné une boîte postale identifiée, <b>{{ $bureau?->code_postal_buro }} BP
            {{ $record->designation_bp }}</b>, localisée au bureau de poste de
       {{$bureau->designation_buro}}
    </p>

    <p class="s3" style="padding-left: 5pt; text-indent: 0pt; text-align: left">
        ARTICLE 5: <u>CESSION DE L'ABONNEMENT</u>
    </p>

    <p style="padding-left: 5pt; text-indent: 0pt; line-height: 124%; text-align: left;">
        5.1 L'abonné peut, avec l'autorisation de LA POSTE et sous réserve du paiement d'une redevance par le
        bénéficiaire,céder son abonnement à:
        - toute personne lui succédant dans ses activités professionnelles
        - au conjoint, ascendant ou descendant
        Les frais de cession s'élèvent à mille(1.000) francs CFA pour les personnes physiques et de deux mille(2.000)
        pour
        les personnes morales.

    </p>

    <p style="padding-top: 2pt; padding-left: 5pt; text-indent: 0pt; line-height: 124%; text-align: left;">
        5.2 La cession ne sera possible que si le cédant solde son compte vis-à-vis de LA POSTE.
    </p>


    <p style="padding-top: 2pt; padding-left: 5pt; text-indent: 0pt; line-height: 124%; text-align: left;">
        5.3 En cas de décès de l'abonné, le conjoint survivant, les héritiers et les ayants droit peuvent maintenir
        l'usage
        de la boîte en leur nom collectif ou le céder à l'un d'entre eux, aux conditions qui leur sont précisées par LA
        POSTE.

    </p>

    <p class="s3" style="padding-left: 5pt; text-indent: 0pt; text-align: left">
        ARTICLE 6: <u> ATTESTATION </u>
    </p>


    <p style="padding-top: 2pt; padding-left: 5pt; text-indent: 0pt; line-height: 124%; text-align: left;">
        6.1. L'abonné a le droit de se faire délivrer une attestation de boîte postale à la demande.
    </p>


    <p style="padding-top: 2pt; padding-left: 5pt; text-indent: 0pt; line-height: 124%; text-align: left;">
        6.2. A part le titulaire, un utilisateur peut également se faire délivrer une attestation de BP sur présentation
        d'une autorisation émise par le titulaire de la boîte.
    </p>

    <p style="padding-top: 2pt; padding-left: 5pt; text-indent: 0pt; line-height: 124%; text-align: left;">
        6.3. Les frais d'attestation sélèvent à:
        - Titulaire: cinq cents (500) frnacs CFA pour personne physique et mille(1.000) francs CFA pour personne morale
        - Utilisateur: mille (1.000) francs pour personne physique et deux mille (2.000) francs CFA pour personne morale
    </p>


    <p style="padding-top: 2pt; padding-left: 5pt; text-indent: 0pt; line-height: 124%; text-align: left;">
        6.4. Une personne physique n'est pas autorisée à délivrer l'attestation à une personne morale.
    </p>

    <p class="s3" style="padding-left: 5pt; text-indent: 0pt; text-align: left">
        ARTICLE 7: <u> SUSPENSION ET RESILIATION </u>
    </p>


    <p style="padding-top: 2pt; padding-left: 5pt; text-indent: 0pt; line-height: 124%; text-align: left;">
        7.1 La suspension de l'abonnement est la cessation momentanée de la prestation fournie par LA POSTE. Elle
        intervient suite au non-paiement de la redevance et est frappée d'une pénalité; la suspension ne peut, en aucun
        cas, excéder neuf(9) mois.
    </p>


    <p style="padding-top: 2pt; padding-left: 5pt; text-indent: 0pt; line-height: 124%; text-align: left;">
        7.2. LA POSTE pourra procéder à la résiliation du contrat après les délais. Dans ce cas, LA POSTE utilisera tous
        les moyens légaux pour recouver ses créances.
    </p>

    <p style="padding-top: 2pt; padding-left: 5pt; text-indent: 0pt; line-height: 124%; text-align: left;">
        7.3. L'abonné est tenu de restituer en bon état les clés de sa boîte lors de la résiliation du contrat; cas
        échéant, il en remboursera la valeur à LA POSTE.
    </p>

    <p class="s3" style="padding-left: 5pt; text-indent: 0pt; text-align: left">
        ARTICLE 8: <u> CHANGEMENT DE SERRURE </u>
    </p>


    <p style="padding-top: 2pt; padding-left: 5pt; text-indent: 0pt; line-height: 124%; text-align: left;">
        8.1. Le changement de serrure s'effectue soit sur demande du client ou après résiliation du contrat.
    </p>

    <p style="padding-top: 2pt; padding-left: 5pt; text-indent: 0pt; line-height: 124%; text-align: left;">
        8.2. Tout changement sur demande du client suite à la perte de la clef ou toute autre erreur provenant de
        l'abonné
        est facturé suivant les tarifs en vigueur.
    </p>

    <p class="s3" style="padding-left: 5pt; text-indent: 0pt; text-align: left">
        ARTICLE 9: <u> ENTREE EN VIGUEUR ET DENONCIATION </u>
    </p>

    <p style="padding-top: 2pt; padding-left: 5pt; text-indent: 0pt; line-height: 124%; text-align: left;">
        9.1. La date d'entrée en vigueur du contrat est la date d'abonnement.
    </p>

    <p style="padding-top: 2pt; padding-left: 5pt; text-indent: 0pt; line-height: 124%; text-align: left;">
        9.2. Le contrat est valable pour la durée de l'abonnement à la boîte et pourra être dénoncé à l'initiative de
        l'une
        des parties après un préavis de deux(2) mois au plus tard avant la fin de la période.
    </p>

    <br>
    <p style="padding-left: 154pt; text-indent: 0pt; text-align: left">
        Fait à {{ $bureau->designation_buro }}, le {{ today()->format('d/m/Y') }}
    </p>
</body>

</html>
