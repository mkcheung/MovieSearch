<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Http\Controllers\ToMovieController;
use App\Services\ToMovieService;
use Mockery;

class ToMovieControllerTest extends TestCase
{
    /** @test **/
    public function test_index()
    {


        $toMovieServiceMock = Mockery::mock(ToMovieService::class);
        ;

        $toMovieServiceMock->shouldReceive('getAllOwnedMovieTitles')
        ->once()
        ->andReturn([
            'movieTitle1',
            'movieTitle2'
        ]);
        $this->call('GET', '/');

        $this->assertResponseOk();
//        $this->app->instance('CookBook\Recipes\RecipeRepository', $toMovieServiceMock);
//        $mock->shouldReceive('getAllPaginated')->once();

//        $toMovieController = new ToMovieController();
//        $response = $toMovieController->index($toMovieServiceMock);
//        $response->assertViewIs('tomovies.index');
//        $response = $controller->store($request);
//        $response->assertViewHas('unit');

//        $this->action('GET', 'ToMovieController@index');
//        $this->assertTrue(true);
    }
}
