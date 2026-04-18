<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Super Admin PIN Security
    |--------------------------------------------------------------------------
    |
    | The secret suffix appended to today's DDMM to form the daily PIN.
    | PIN Formula: now()->format('dm') . config('security.super_admin_pin_secret')
    | Example: 11 March + '99' = '110399'
    |
    */

    'super_admin_pin_secret' => env('SUPER_ADMIN_PIN_SECRET', '99'),

];
