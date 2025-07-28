<?php
require __DIR__.'/DataProcessing.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    $idContact = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
    if ($idContact)
    {
        $full_contacts = DataProcessing::loadContacts();
        
        $full_contacts_filtered = array_values(array_filter($full_contacts, function($contact) use ($idContact) {
            return $contact['id'] !== $idContact;
        }));

        DataProcessing::saveContacts($full_contacts_filtered);
        header('location: index.php');
        exit;
    }
}