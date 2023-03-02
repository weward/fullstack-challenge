<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use App\Models\User;
use App\Models\WeatherReport;

class UpdateUserWeatherReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $apiKey;

    protected $tempUsed = 'fahrenheit';

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $api = config('api.using');
        $this->apiKey = config("api.{$api}.app_id");

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        User::orderBy('id')
        ->chunk(1000, function($users) {
            foreach ($users as $user) {
                $data = $this->getWeatherdata($user->latitude, $user->longitude);

                $data = json_encode($data) ?: null;

                WeatherReport::updateOrCreate(['user_id' => $user->id], [
                    'data' => $data,
                ]);

                if (!$data) {
                    Redis::del("user_{$user->id}");
                } else {
                    Redis::set("user_{$user->id}", $data);
                }

            }
        });
    }

    protected function getWeatherdata($lat, $lng)
    {
        $response = Http::get("https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lng}&appid={$this->apiKey}");
     
        if ($response->successful()) {
            $res = $response->json();

            return $this->formatData($res);
        }

        return false;
    }

    protected function formatData($res)
    {
        return [
            'id' => [
                'label' => 'Id',
                'value' => $res['id'] ?? null,
            ],
            'name' => [
                'label' => 'Location', 
                'value' => $res['name'] ?? null,
            ],
            'description' => [
                'label' => 'Description', 
                'value' => $res['weather'][0]['description'] ?? null,
            ],
            'temp' => [
                'label' => 'Temperature', 
                'value' => $res['main']['temp'] ? temperature($res['main']['temp'], $this->tempUsed) : null,
            ],
            'feels_like' => [
                'label' => 'Feels Like', 
                'value' => $res['main']['feels_like'] ? temperature($res['main']['feels_like'], $this->tempUsed) : null,
            ],
            'temp_min' => [
                'label' => 'Temp Min.', 
                'value' => $res['main']['temp_min'] ? temperature($res['main']['temp_min'], $this->tempUsed) : null,
            ],
            'temp_max' => [
                'label' => 'Temp Max.', 
                'value' => $res['main']['temp_max'] ? temperature($res['main']['temp_max'], $this->tempUsed) : null,
            ],
            'humidity' => [
                'label' => 'Humidity', 
                'value' => $res['main']['humidity'] ? $res['main']['humidity'] . '%' : null,
            ],
            'pressure' => [
                'label' => 'Pressure', 
                'value' => $res['main']['pressure'] ? $res['main']['pressure'] .' hPa' : null,
            ],
            'sea_level' => [
                'label' => 'Sea Level', 
                'value' => $res['main']['sea_level'] ? $res['main']['sea_level'] .' hPa' : null,
            ],
            'sunrise' => [
                'label' => 'Sunrise', 
                'value' => $res['sys']['sunrise'] ? \Carbon\Carbon::parse($res['sys']['sunrise'])->format('m/d/Y g:i A') : null,
            ],
            'sunset' => [
                'label' => 'Sunset', 
                'value' => $res['sys']['sunset'] ? \Carbon\Carbon::parse($res['sys']['sunset'])->format('m/d/Y g:i A') : null,
            ],
            'speed' => [
                'label' => 'Speed', 
                'value' => $res['wind']['speed'] ? $res['wind']['speed'] . 'm/s' : null,
            ],
            'gust' => [
                'label' => 'Gust', 
                'value' => $res['wind']['gust'] ? $res['wind']['gust'] . 'm/s' : null,
            ],
            'visibility' => [
                'label' => 'Visibility', 
                'value' => $res['visibility'] ? $res['visibility'] . 'm' : null,
            ],
        ];
    }
}
