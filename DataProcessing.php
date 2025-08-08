<?php
declare(strict_types=1);
require __DIR__ . '/config.php';



class DataProcessing
{

    public function __construct(
        
    )
    {

    }

    /**
     * Charge tout les contacts depuis un fichier JSON
     * 
     * @return array<int, array{nom:string, prenom:string, numero:int, email:string}>
     */
    public static function loadContacts():array
    {
        if (!file_exists(DATA_FILE))
        {
            return [];
        }
        $json = file_get_contents(DATA_FILE);
        return json_decode($json, true) ?? [];
    }


    /**
     * Sauvegarde tout les contacts dans un fichier JSON
     * 
     * @param array $contacts
     * @return void
     */
    public static function saveContacts(array $contacts):void
    {
        $json = json_encode($contacts, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        file_put_contents(DATA_FILE, $json);
    }

    /**
     * Affiche les informations relatif à un contact
     * 
     * @param array{nom:string, prenom:string, numero:int, email:string} $contact
     * @return string Code d'affichage en HTML
     */
    public static function displayContact(array $contact):string
    {
        $name = htmlspecialchars($contact['name']);
        $surname = htmlspecialchars($contact['surname']);
        $phone = htmlspecialchars($contact['phone']);
        $email = htmlspecialchars($contact['email']);
        $birthdate = htmlspecialchars($contact['birthdate']);
        $created_at = htmlspecialchars($contact['created_at']);
        $image_url = $contact['image_url'];
    
        return <<<CONTACT
            <div class="image"><img src="{$image_url}" alt=""></div>
            <hr>
            <p><span>First Name :</span> {$surname}</p>
            <p><span>Last Name :</span> {$name}</p>
            <p><span>Phone number :</span> {$phone}</p>
            <p><span>Email address :</span> {$email}</p>
            <p><span>Birth date :</span> {$birthdate}</p>
            <p><span>Contact created at {$created_at}</span></p>
        CONTACT;
    }
}