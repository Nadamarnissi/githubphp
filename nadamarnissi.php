<?php 

// Exercice 1: 
$employes = [
    ["nom" => "Nada", "poste" => "Développeur", "salaire" => 4000],
    ["nom" => "Mohammed", "poste" => "Designer", "salaire" => 3500],
    ["nom" => "Wissal", "poste" => "Administrateur réseau", "salaire" => 4200]
];

function moyen($employes)
{
    $somme = 0.0;
    foreach ($employes as $employe) {
        $somme += $employe["salaire"];
    }
    $moyenne = $somme / count($employes);
    echo "Le salaire moyen des employés est : ", $moyenne, "$";
}

// Exercice 2 et Exercice 3: 

$utilisateurs = [
    [
        "email" => "nada@example.com",
        "motdepasse" => "nada123"
    ],
    [
        "email" => "simo@example.com",
        "motdepasse" => "simo666"
    ],
    [
        "email" => "wissal@example.com",
        "motdepasse" => "wissal777"
    ]
];

$result = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && (isset($_POST["exercice2"]) || isset($_POST["exercice3"]))) {
    $searchName = $_POST["search"];
    $email = $_POST["email"];
    $pwd = $_POST["pwd"];
    if (!empty($searchName)) {
        foreach ($employes as $employe) {
            if (strncasecmp($employe['nom'], $searchName, strlen($searchName)) === 0) {
                $result = "Employe trouve : <br>" .
                    "Nom : " . $employe["nom"] . "<br>" .
                    "Poste : " . $employe["poste"] . "<br>" .
                    "Salaire : " . $employe["salaire"] . "<br>";
                break;
            }
        }
        if (empty($result)) {
            $result = "Rien trouve pour : " . $searchName;
        }
    }

    $userfound = false;
    if (!empty($email) && !empty($pwd)) {
        foreach ($utilisateurs as $utilisateur) {
            if ($utilisateur['email'] === $email && $utilisateur['motdepasse'] === $pwd) {
                $userfound = true;
                break;
            }
        }
    }
}

// Exercice 4: 

session_start();

// Panier
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Ajouter
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajouter'])) {
    echo "testing";
    $nom = $_POST['nom'];
    $quantite = (int) $_POST['quantite'];
    $prix = (float) $_POST['prix'];

    if (isset($_SESSION['panier'][$nom])) {
        $_SESSION['panier'][$nom]['quantite'] += $quantite;
    } else {
        $_SESSION['panier'][$nom] = [
            'quantite' => $quantite,
            'prix' => $prix
        ];
    }
}

// Vider
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['vider'])) {
    if (isset($_SESSION['panier'])) {
        unset($_SESSION['panier']);
    }
}

//Total
function total($panier)
{
    $total = 0;
    foreach ($panier as $produit) {
        $total += $produit['quantite'] * $produit['prix'];
    }
    return $total;
}

// Exercice 5 :

if (!isset($_SESSION['commentaires'])) {
    $_SESSION['commentaires'] = [];
}

// Send Comments
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comments'])) {
    $commentaire = $_POST['commentaire'];
    if (!empty(trim($commentaire))) {
        $_SESSION['commentaires'][] = [
            'texte' => $commentaire,
            'date' => date('Y-m-d H:i:s')
        ];
    }
}

// vider comments
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['emptycomment'])) {
    if (isset($_SESSION['commentaires'])) {
        unset($_SESSION['commentaires']);
    }
}

// Exercice 6 :

$villes = [
    "Paris" => 22,
    "Londres" => 18,
    "Madrid" => 30,
    "Berlin" => 25,
    "Rome" => 28
];

function villepluschaud($villes)
{
    $temperaturemax = max($villes);
    $villemax = array_search($temperaturemax, $villes);

    echo "La Ville la plus chaude est : " , $villemax , " . La Température maximum est : " , $temperaturemax;
}

// Exercice 7 :

$produits = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["fichier_csv"])) {
    $file = $_FILES["fichier_csv"]["tmp_name"];
    if (is_uploaded_file($file)) {
        $handle = fopen($file, "r");
        if ($handle !== false) {
            $data = fgetcsv($handle, 1000, ",");
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $produits[] = [
                    "nom" => $data[0],
                    "prix" => (float) $data[1],
                    "quantite" => (int) $data[2]
                ];
            }
            fclose($handle);
        }
    }
}

// Exercice 8 :

$produits_ex8 = [
    "Produit A" => 10.5,
    "Produit B" => 15.0,
    "Produit C" => 7.25,
    "Produit D" => 20.0,
    "Produit E" => 12.0
];
$produit_selec = [];
$total = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["produits"])) {
    echo "test";
    $produit_selec = $_POST["produits"];

    foreach ($produit_selec as $value) {
        $total += $produits_ex8[$value];
    }
}

// Exercice 9 :

$etudiants = [
    "Nada" => [
        "Maths" => 17,
        "Physique" => 17,
        "Info" => 18
    ],
    "Simo" => [
        "Maths" => 20,
        "Physique" => 20,
        "Info" => 20
    ],
    "Wissal" => [
        "Maths" => 17,
        "Physique" => 16,
        "Info" => 17
    ]
];

$moyennes = [];
foreach ($etudiants as $nom => $notes) {
    $moyenne = array_sum($notes) / count($notes);
    $moyennes[$nom] = $moyenne;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercices Nada Marnissi</title>
</head>

<body>

    <div>
        <h4>Exercice 1:</h4>

        <?php
        moyen($employes);
        ?>
    </div>
    <div>
        <h4>Exercice 2:</h4>
        <form method="post">
            <label for="search">Entrez le nom d employe :</label>
            <input type="text" name="search" id=""><br>
            <button type="submit">Submit</button>
            <button type="reset" name="exercice2">Reset</button>
        </form>
        <?php
        echo $result;
        ?>
    </div>
    <div>
        <h4>Exercice 3:</h4>
        <form method="post">
            <input type="email" placeholder="email" name="email" id="">
            <input type="password" placeholder="password" name="pwd" id="">
            <button type="submit" name="exercice3">Submit</button>
        </form>
        <?php
        if ($userfound) {
            echo "Connexion réussie ! Bienvenue.";
        } else {
            echo "Adresse email ou mot de passe incorrect.";
        }
        ?>
    </div>
    <div>
        <h4>Exercice 4:</h4>
        <h2>Ajouter un Produit</h2>
        <form method="POST">
            <label for="nom">Nom du produit :</label>
            <input type="text" id="nom" name="nom" required>
            <br><br>
            <label for="quantite">Quantité :</label>
            <input type="number" id="quantite" name="quantite" min="1" required>
            <br><br>
            <label for="prix">Prix unitaire :</label>
            <input type="number" id="prix" name="prix" step="0.01" required>
            <br><br>
            <button type="submit" name="ajouter">Ajouter au panier</button>
        </form>
        <h4>Contenu du panier</h4>
        <?php if (!empty($_SESSION['panier'])): ?>
            <table style="border: 1px;">
                <tr>
                    <th>Nom</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Total</th>
                </tr>
                <?php foreach ($_SESSION['panier'] as $nom => $produit): ?>
                    <tr>
                        <td><?php echo $nom ?></td>
                        <td><?php echo $produit['quantite'] ?></td>
                        <td><?php echo $produit['prix'] ?></td>
                        <td><?php echo $produit['quantite'] * $produit['prix'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <p>Total du panier : <?php echo total($_SESSION['panier']) ?></p>
        <?php else: ?>
            <p>Votre panier est vide.</p>
        <?php endif; ?>
        <form method="post">
            <button type="submit" name="vider">Vider le panier</button>
        </form>
    </div>
    <div>
        <h4>Exercice 5</h4>
        <form method="POST">
            <label for="commentaire">Commentaire :</label><br>
            <textarea id="commentaire" name="commentaire" rows="4" cols="50"></textarea>
            <br><br>
            <button type="submit" name="comments">Soumettre</button>
            <button type="submit" name="emptycomment">empty list</button>
        </form>
        <?php if (!empty($_SESSION['commentaires'])): ?>
            <ul>
                <?php foreach ($_SESSION['commentaires'] as $comment): ?>
                    <li>
                        <strong><?php echo $comment['date']; ?></strong> :
                        <?php echo $comment['texte']; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucun commentaire n'a été soumis pour l'instant.</p>
        <?php endif; ?>
    </div>
    <div>
        <h4>Exercice 6</h4>
        <?php villepluschaud($villes) ?>
    </div>
    <div>
        <h4>Exercice 7</h4>
        <form method="POST" enctype="multipart/form-data">
            <label for="fichier_csv">Choisissez un fichier CSV :</label>
            <input type="file" name="fichier_csv" id="fichier_csv" accept=".csv" required><br>
            <button type="submit">Importer</button>
        </form>
        <?php if (!empty($produits)): ?>
            <h5>Liste des produits</h5>
            <table style="border: 1px;">
                <tr>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Quantite</th>
                </tr>
                <?php foreach ($produits as $produit): ?>
                    <tr>
                        <td><?php echo $produit["nom"]; ?></td>
                        <td><?php echo $produit["prix"]; ?></td>
                        <td><?php echo $produit["quantite"]; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
    <div>
        <h4>Exercice 8</h4>
        <form method="POST">
            <h5>Sélectionnez vos produits :</h5>
            <?php foreach ($produits_ex8 as $nom => $prix): ?>
                <label>
                    <input type="checkbox" name="produits[]" value="<?php echo $nom; ?>">
                    <?php echo $nom . "(" . $prix . ")"; ?>
                </label>
                <br>
            <?php endforeach; ?>
            <button type="submit">Valider</button>
        </form>
        <?php if (!empty($produit_selec)): ?>
            <h5>Produits Sélectionnés :</h5>
            <?php foreach ($produit_selec as $produit): ?>
                <p><?php echo $produit; ?></p>
            <?php endforeach; ?>
            <p><strong>Total :</strong><?php echo $total; ?></p>
        <?php endif; ?>
    </div>
    <div>
        <h4>Exercice 9</h4>
        <h5>Résultats des Étudiants</h5>
        <table style="border: 1px;">
            <tr>
                <th>Étudiant</th>
                <th>Maths</th>
                <th>Physique</th>
                <th>Info</th>
                <th>Moyenne</th>
            </tr>
            <?php foreach ($etudiants as $nom => $notes): ?>
                <tr>
                    <td><?php echo $nom; ?></td>
                    <td><?php echo $notes["Maths"]; ?></td>
                    <td><?php echo $notes["Physique"]; ?></td>
                    <td><?php echo $notes["Info"]; ?></td>
                    <td><strong><?php echo $moyennes[$nom]; ?></strong></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>

</html>