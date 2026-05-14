<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FonnteService
{
    public static function send($target, $message)
    {
        
        $token = 'a1VneuXppXcH1cAA27tB'; 

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
            ]);

            return $response->json();
        } catch (\Exception $e) {
            return null;
        }
    }
}