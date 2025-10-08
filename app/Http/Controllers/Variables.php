<?php
namespace App\Http\Controllers;
$phone1 = '+237-657-528-859';
$phone2 = '+237-682-252-932';

// Nettoyage des numéros pour le lien 'tel:'
$phone = str_replace('-', '', $phone1);
$tel2 = str_replace('-', '', $phone2);
$email = 'brayeljunior8@gmail.com';