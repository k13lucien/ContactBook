<?php
require __DIR__.'/DataProcessing.php';

$contact = null;

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    $idContact = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
    if ($idContact)
    {
        $full_contacts = DataProcessing::loadContacts();

        $contact = null;
        foreach ($full_contacts as $c)
        {
            if ($c['id'] == $idContact)
            {
                $contact = $c;
            }
        }
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="templates/styles/styles.css" rel="stylesheet">
    <title>
        <?= $contact ? htmlspecialchars($contact['name']) . ' ' . htmlspecialchars($contact['surname']) : 'Contact introuvable' ?>
    </title>
</head>
<body>
    
    <main class="container">

        <div>
            <div class="main-content">

                <?php if ($contact): ?>

                    <?= DataProcessing::displayContact($contact) ?>

                <?php else: ?>

                    <p>Contact not found</p>

                <?php endif; ?>
            </div>

            <div class="buttons">
                <a href="edit.php?id=<?= $contact['id'] ?>">Edit</a>
                <a href="delete.php?id=<?= $contact['id'] ?>">Delete</a>
                <a href="index.php">Back to home</a>
            </div>
        </div>

    </main>

</body>
</html>