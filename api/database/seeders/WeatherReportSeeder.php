<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\WeatherReport;

class WeatherReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        if ($users->count()) {
            $data = $this->getSampleData();

            foreach ($users as $user) {
                $weather[] = [
                    'user_id' => $user->id,
                    'data' => json_encode($data),
                    'created_at' => now()->format('Y-m-d H:i:s'),
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ];
            }
            
            WeatherReport::insert($weather);
        }
    }

    protected function getSampleData()
    {
        return '{"dt": 1677735491, "id": 0, "cod": 200, "sys": {"sunset": 1677724126, "sunrise": 1677677924}, "base": "stations", "main": {"temp": 296.7, "humidity": 83, "pressure": 1021, "temp_max": 296.7, "temp_min": 296.7, "sea_level": 1021, "feels_like": 297.28, "grnd_level": 1021}, "name": "", "wind": {"deg": 332, "gust": 3.72, "speed": 3.07}, "coord": {"lat": -34.4391, "lon": -117.8029}, "clouds": {"all": 73}, "weather": [{"id": 803, "icon": "04n", "main": "Clouds", "description": "broken clouds"}], "timezone": -28800, "visibility": 10000}';
    }

}

