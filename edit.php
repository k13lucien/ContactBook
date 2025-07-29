<?php
require __DIR__.'/vendor/SimplePHPForm.php';
require __DIR__.'/DataProcessing.php';

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
                break;
            }
        }
    }
    else
    {
        header('location: view.php');
        exit;
    }

}

    // Adding fields to the form
    $form = new SimplePHPForm();
    $form->add('surname', 'text', $contact['surname'] ?? '', ['required'], 'First name*', '', 'The last name is required');
    $form->add('name', 'text', $contact['name'] ?? '', ['required'], 'Last name*', '', 'The first name is required');
    $form->add('phone', 'text', $contact['phone'] ?? '', ['required', 'phone'], 'Phone number*', '', 'The phone number is required');
    $form->add('email', 'text', $contact['email'] ?? '', ['required', 'email'], 'Email address*', '', 'The email address is required');
    $form->add('birthdate', 'date', $contact['birthdate'] ?? '', ['date'], 'Birth date', '', 'The birth date is incorrect');
    $form->add('image_url', 'file', '', [''], 'Add profil image', '', '');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if ($form->validate())
    {
        // Save data if valid

        $data = $form->input_list;
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

        if ($data['image_url']->data !== '')
        {
            $storage = 'data/uploads/';
            $file_name = basename($data['image_url']->data['name']);
            $file_path = $storage . $id . preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $file_name);
        
            if (!is_dir($storage))
                mkdir($storage, 0755, true);
        
            move_uploaded_file($data['image_url']->data['tmp_name'], $file_path);
        }

        $full_contacts = DataProcessing::loadContacts();

        foreach ($full_contacts as &$contact)
        {
            if ($contact['id'] == $_GET['id'])
            {
                $contact['name'] = $data['name']->data ?? null;
                $contact['surname'] = $data['surname']->data ?? null;
                $contact['phone'] = $data['phone']->data ?? null;
                $contact['email'] = $data['email']->data ?? null;
                $contact['birthdate'] = $data['birthdate']->data ?? null;
                $contact['image_url'] = $file_path ?? null;
                break;
            }
        }
        unset($contact);

        DataProcessing::saveContacts($full_contacts);

        $form->reset();
        header('location: view.php?id='.$_GET['id']);
        exit;
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editer un contact</title>
</head>
<body>
    
    <?= 
    // Display form
    $form->display()
    ?>

</body>
</html>