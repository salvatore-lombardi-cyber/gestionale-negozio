<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
{
    /**
     * Ottiene i dati meteorologici per una città usando Open-Meteo API
     */
    public function getCurrentWeather(Request $request)
    {
        $city = $request->query('city', 'Roma');
        
        // Cache key per evitare troppe chiamate API
        $cacheKey = 'weather_' . strtolower(str_replace(' ', '_', $city));
        
        // Controlla la cache (10 minuti per Open-Meteo)
        $cachedData = Cache::get($cacheKey);
        if ($cachedData) {
            return response()->json($cachedData);
        }
        
        try {
            // Prima ottieni le coordinate della città
            $coordinates = $this->getCityCoordinates($city);
            
            if (!$coordinates) {
                throw new \Exception('Città non trovata: ' . $city);
            }
            
            // Chiamata a Open-Meteo per i dati meteo attuali
            $response = Http::timeout(10)->get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => $coordinates['lat'],
                'longitude' => $coordinates['lon'],
                'current' => 'temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m,wind_direction_10m,pressure_msl',
                'timezone' => 'Europe/Rome',
                'forecast_days' => 1
            ]);
            
            if (!$response->successful()) {
                throw new \Exception('Errore nella risposta Open-Meteo: ' . $response->status());
            }
            
            $data = $response->json();
            
            if (!isset($data['current'])) {
                throw new \Exception('Dati meteo non disponibili');
            }
            
            $current = $data['current'];
            
            // Converti weather_code in condizione testuale
            $condition = $this->getWeatherCondition($current['weather_code'] ?? 0);
            
            // Formatta i dati per il frontend
            $weatherData = [
                'location' => $coordinates['name'],
                'region' => $coordinates['region'] ?? '',
                'country' => $coordinates['country'] ?? 'Italia',
                'temperature' => round($current['temperature_2m'] ?? 0),
                'condition' => $condition,
                'humidity' => round($current['relative_humidity_2m'] ?? 0),
                'windSpeed' => round(($current['wind_speed_10m'] ?? 0) * 3.6), // m/s to km/h
                'visibility' => 10, // Open-Meteo non fornisce visibilità, valore fisso
                'pressure' => round($current['pressure_msl'] ?? 0),
                'feelsLike' => round($current['temperature_2m'] ?? 0), // Approssimazione
                'uvIndex' => 0, // Non disponibile in Open-Meteo basic
                'windDirection' => round($current['wind_direction_10m'] ?? 0),
                'lastUpdated' => $current['time'] ?? now()->toISOString(),
            ];
            
            // Salva in cache per 10 minuti
            Cache::put($cacheKey, $weatherData, 600);
            
            return response()->json($weatherData);
            
        } catch (\Exception $e) {
            Log::error('Errore Open-Meteo', [
                'city' => $city,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Impossibile ottenere i dati meteo per ' . $city . '. ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Ottiene le coordinate di una città usando Nominatim (OpenStreetMap)
     */
    private function getCityCoordinates($city)
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'Laravel Weather App/1.0'
                ])
                ->get('https://nominatim.openstreetmap.org/search', [
                    'q' => $city . ', Italia',
                    'format' => 'json',
                    'limit' => 1,
                    'addressdetails' => 1
                ]);
            
            if (!$response->successful()) {
                return null;
            }
            
            $data = $response->json();
            
            if (empty($data)) {
                // Prova senza ", Italia"
                $response = Http::timeout(10)
                    ->withHeaders([
                        'User-Agent' => 'Laravel Weather App/1.0'
                    ])
                    ->get('https://nominatim.openstreetmap.org/search', [
                        'q' => $city,
                        'format' => 'json',
                        'limit' => 1,
                        'addressdetails' => 1
                    ]);
                
                if (!$response->successful()) {
                    return null;
                }
                
                $data = $response->json();
            }
            
            if (empty($data)) {
                return null;
            }
            
            $location = $data[0];
            
            return [
                'lat' => floatval($location['lat']),
                'lon' => floatval($location['lon']),
                'name' => $location['display_name'] ?? $city,
                'region' => $location['address']['state'] ?? '',
                'country' => $location['address']['country'] ?? 'Italia'
            ];
            
        } catch (\Exception $e) {
            Log::error('Errore geocoding', [
                'city' => $city,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
    
    /**
     * Converte il weather_code di Open-Meteo in condizione testuale
     */
    private function getWeatherCondition($code)
    {
        $conditions = [
            0 => 'Sereno',
            1 => 'Principalmente sereno',
            2 => 'Parzialmente nuvoloso',
            3 => 'Nuvoloso',
            45 => 'Nebbia',
            48 => 'Nebbia con brina',
            51 => 'Pioggerella leggera',
            53 => 'Pioggerella moderata',
            55 => 'Pioggerella intensa',
            61 => 'Pioggia leggera',
            63 => 'Pioggia moderata',
            65 => 'Pioggia intensa',
            80 => 'Rovesci leggeri',
            81 => 'Rovesci moderati',
            82 => 'Rovesci intensi',
            95 => 'Temporale',
            96 => 'Temporale con grandine leggera',
            99 => 'Temporale con grandine intensa'
        ];
        
        return $conditions[$code] ?? 'Condizioni sconosciute';
    }
    
    /**
     * Cerca città per autocompletamento (opzionale)
     */
    public function searchCities(Request $request)
    {
        $query = $request->query('q', '');
        $apiKey = env('WEATHER_API_KEY');
        
        if (!$apiKey || strlen($query) < 3) {
            return response()->json([]);
        }
        
        try {
            $response = Http::timeout(5)->get('http://api.weatherapi.com/v1/search.json', [
                'key' => $apiKey,
                'q' => $query
            ]);
            
            if ($response->successful()) {
                $cities = collect($response->json())->map(function ($city) {
                    return [
                        'name' => $city['name'],
                        'region' => $city['region'] ?? '',
                        'country' => $city['country'] ?? '',
                        'display' => $city['name'] . ($city['region'] ? ', ' . $city['region'] : '') . ', ' . $city['country']
                    ];
                })->take(10);
                
                return response()->json($cities);
            }
            
            return response()->json([]);
            
        } catch (\Exception $e) {
            Log::error('Errore ricerca città', [
                'query' => $query,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([]);
        }
    }
}