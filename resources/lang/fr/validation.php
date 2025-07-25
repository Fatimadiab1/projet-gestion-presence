<?php

return [
    'required' => 'Le champ :attribute est obligatoire.',
    'email' => 'Le champ :attribute doit être une adresse e-mail valide.',
    'min' => [
        'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
    ],
    'confirmed' => 'Le champ :attribute ne correspond pas à la confirmation.',
    'unique' => 'Le champ :attribute est déjà utilisé.',
    'exists' => 'Le champ :attribute sélectionné est invalide.',
    'image' => 'Le champ :attribute doit être une image.',
    'max' => [
        'file' => 'Le fichier :attribute ne doit pas dépasser :max kilo-octets.',
        'string' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
    ],

    'attributes' => [
        'nom' => 'nom',
        'prenom' => 'prénom',
        'email_prefix' => 'adresse e-mail',
        'mot_de_passe' => 'mot de passe',
        'mot_de_passe_confirmation' => 'confirmation du mot de passe',
        'role_id' => 'rôle',
        'photo' => 'photo',
    ],
];
