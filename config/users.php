<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, SparkPost and others. This file provides a sane default
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'roles' => [
        'user' => 'Benutzer',
        'admin' => 'Admin',
    ],


    'employment' => [
        'free' => 'Pauschalist',
        'permanent' => 'Festanstellung',
    ],


    'can_see_other_appointments' =>
    [
        '0' => 'Nur eigene Termine',
        '1' => 'Nur Schichtkollegen',
        '2' => 'Alle',
    ],


    'appointment_types' =>
    [
        'birthdates' => 'Geburtstage',
        'events'     => 'Ereignisse',
        'fewo_dates' => 'FeWo',
        'leave_days' => 'Urlaub',
        'work'       => 'Arbeit',
        'private_dates' => 'Termin',
        'sick' => 'Krank',
    ],



];
