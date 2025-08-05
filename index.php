<?php
require __DIR__.'/DataProcessing.php';

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="templates/styles/styles.css" rel="stylesheet">
    <title><?= APP_NAME ?></title>
</head>
<body>

    <main class="container">

        <div class="main-content">

            <h1 class="title"><?= APP_NAME ?></h1>

            <ul>

                <?php

                $full_contacts = DataProcessing::loadContacts();

                foreach ($full_contacts as $contact)
                {
                    $image_url = htmlspecialchars($contact['image_url']);
                    $nom = htmlspecialchars($contact['name']);
                    $prenom = htmlspecialchars($contact['surname']);

                    echo <<<CONTACT
                        <li>
                            <a href="view.php?id={$contact['id']}">
                                <img src="{$contact['image_url']}"/> {$nom} {$prenom}
                            </a>
                        </li>
                    CONTACT;
                }

                ?>

                <a href="add.php" class="add-contact">Add contact</a>
                
            </ul>
        </div>

    </main>
    
</body>
</html>