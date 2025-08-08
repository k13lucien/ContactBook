<?php
session_start();
require __DIR__.'/vendor/SimplePHPForm.php';
require __DIR__.'/DataProcessing.php';

// Recover session data if exist
$name = $_SESSION['old']['name'] ?? '';
$surname = $_SESSION['old']['surname'] ?? '';
$phone = $_SESSION['old']['phone'] ?? '';
$email = $_SESSION['old']['email'] ?? '';
$birthdate = $_SESSION['old']['birthdate'] ?? '';
unset($_SESSION['old']);


// Adding fields to the form
$form = new SimplePHPForm();
$form->add('surname', 'text', $surname, ['required'], 'First name*', '', 'The first name is required');
$form->add('name', 'text', $name, ['required'], 'Last name*', '', 'The last name is required');
$form->add('phone', 'text', $phone, ['required', 'phone'], 'Phone number*', '', 'You must respect the format of a phone number');
$form->add('email', 'text', $email, ['required', 'email'], 'Email address*', '', 'You must respect the format of a email address');
$form->add('birthdate', 'date', $birthdate, ['date'], 'Birth date', '', '');
$form->add('image_url', 'file', '', [''], 'Add profil image', '', '');

if ($form->validate())
{
    // Save data if valid and redirect to home

    $data = $form->input_list;
    $id = uniqid();

    if ($data['image_url']->data !== '')
    {
        $storage = 'data/uploads/';
        $file_name = basename($data['image_url']->data['name']);
        $file_path = $storage . $id . preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $file_name);
    
        if (!is_dir($storage))
            mkdir($storage, 0755, true);
    
        move_uploaded_file($data['image_url']->data['tmp_name'], $file_path);
    }

    $new_contact = [
        'id' => $id,
        'name' => $data['name']->data ?? null,
        'surname' => $data['surname']->data ?? null,
        'phone' => $data['phone']->data ?? null,
        'email' => $data['email']->data ?? null,
        'birthdate' => $data['birthdate']->data ?? null,
        'image_url' => $file_path ?? null,
        'created_at' => date('Y-m-d H:i:s')
    ];

    $full_contacts = DataProcessing::loadContacts();
    $full_contacts[] = $new_contact;

    DataProcessing::saveContacts($full_contacts);

    $form->reset();
    header('location: index.php');
    exit;
}
else
{
    // Store old data in session if isn't valid
    $data = $form->input_list;
    $new_contact = [
        'name' => $data['name']->data,
        'surname' => $data['surname']->data,
        'phone' => $data['phone']->data,
        'email' => $data['email']->data,
        'birthdate' => $data['birthdate']->data
    ];
    $_SESSION['old'] = $new_contact;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./templates/styles/styles.css">
    <title>New Contact</title>
</head>
<body>

    <main class="container">
        <?= 
        // Display form
        $form->display()
        ?>
    </main>
    
</body>
</html>