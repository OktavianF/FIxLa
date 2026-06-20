<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class NewsController extends Controller
{
    /**
     * Fetch latest news related to Lamongan.
     * Caches the result for 1 hour to save API quota.
     */
    public function index(): JsonResponse
    {
        $cacheKey = 'news_lamongan';
        $cacheTtl = 60 * 60; // 1 hour

        $news = Cache::remember($cacheKey, $cacheTtl, function () {
            $apiKey = env('GNEWS_API_KEY');
            if (!$apiKey || $apiKey === 'YOUR_GNEWS_API_KEY_HERE') {
                return []; // Fallback if API key is not configured
            }

            try {
                $response = Http::get('https://gnews.io/api/v4/search', [
                    'q' => 'Lamongan',
                    'lang' => 'id',
                    'country' => 'id',
                    'max' => 10,
                    'apikey' => $apiKey,
                ]);

                if ($response->successful()) {
                    return $response->json('articles') ?? [];
                }
                
                return [];
            } catch (\Exception $e) {
                return [];
            }
        });

        return response()->json([
            'success' => true,
            'data' => $news,
        ]);
    }
}
