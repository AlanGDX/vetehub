<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Appointment;

$appointments = Appointment::with(['client', 'pet', 'user'])
    ->whereDate('appointment_date', '2026-02-26')
    ->get();

$output = "Total: " . $appointments->count() . "\n";

foreach ($appointments as $a) {
    $output .= "ID:{$a->id} | {$a->appointment_date} | {$a->client->name} <{$a->client->email}> | {$a->pet->name} | {$a->status} | Vet:{$a->user->name} <{$a->user->email}>\n";
}

file_put_contents(__DIR__ . '/check_result.log', $output);
echo "Done - check check_result.log";
