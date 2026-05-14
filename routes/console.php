<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Enviar recordatorios de citas 24h antes, todos los días a las 8:00 AM
Schedule::command('appointments:send-reminders')->dailyAt('08:00');

Artisan::command('products:placeholders {--force : Reemplaza imagenes existentes}', function () {
    $palette = [
        '#1d4ed8',
        '#0f766e',
        '#b45309',
        '#9333ea',
        '#be123c',
        '#15803d',
        '#0ea5e9',
        '#7c3aed',
    ];

    $query = Product::query();
    if (!$this->option('force')) {
        $query->whereNull('image_path');
    }

    $products = $query->get();
    $disk = Storage::disk('public');
    $generated = 0;

    foreach ($products as $product) {
        $words = preg_split('/\s+/', trim((string) $product->name));
        $first = isset($words[0]) ? substr($words[0], 0, 1) : '';
        $second = isset($words[1]) ? substr($words[1], 0, 1) : '';
        $initials = strtoupper($first . $second);
        if ($initials === '') {
            $initials = 'P';
        }

        $color = $palette[$product->id % count($palette)];
        $path = 'products/product-' . $product->id . '.svg';
        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="160" height="160" viewBox="0 0 160 160">
  <rect width="160" height="160" rx="24" fill="{$color}" />
  <text x="50%" y="54%" text-anchor="middle" fill="#ffffff" font-family="Arial, sans-serif" font-size="64" font-weight="700" dominant-baseline="middle">{$initials}</text>
</svg>
SVG;

        $disk->put($path, $svg);
        $product->image_path = $path;
        $product->save();
        $generated++;
    }

    $this->info('Imagenes generadas: ' . $generated);
})->purpose('Genera imagenes de prueba para los productos.');
