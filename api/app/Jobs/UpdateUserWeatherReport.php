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
            return $response->json();
        }

        return false;
    }
}
