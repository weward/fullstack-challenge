<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Jobs\UpdateUserWeatherReport;
use Illuminate\Support\Facades\Bus;

class WeatherReportDataTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * @test
     */
    public function weatherReportIsDispatched()
    {
        Bus::fake();
        
        UpdateUserWeatherReport::dispatch();

        Bus::assertDispatched(UpdateUserWeatherReport::class);
    }

}
