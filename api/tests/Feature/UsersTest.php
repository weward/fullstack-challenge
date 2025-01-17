<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\WeatherReport;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * User Index Page Accessible
     * @test
     */
    public function userIndexPageAccessible(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * User Index
     * @test
     */
    public function userListLoading(): void
    {
        $this->prepareData();

        $response = $this->getJson('/');

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('users', 20)
                ->etc()
            );
    }

    /**
     * Specific User's weather data
     * @test
     */
    public function weatherDataOfUserIsAvailable(): void
    {
        $this->prepareData();

        $user = User::first();
        $data = WeatherReport::where('user_id', $user->id)->first();
        $data = $data ? $data->data : null;
        $data = $data ? json_decode($data) : null;

        $response = $this->getJson("/{$user->id}");

        $response->assertStatus(200);
    }

    protected function prepareData()
    {
        User::factory()->count(20)->create();
        Artisan::call('db:seed', ['--class' => 'WeatherReportSeeder']);
    }


}
