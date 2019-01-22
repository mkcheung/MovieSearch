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

    protected $toMovieService;
    protected $toMovieController;
    public function setUp()
    {
        parent::setUp();
        $this->toMovieService = Mockery::mock(ToMovieService::class);
        $this->toMovieController = new ToMovieController();

    }
    /** @test **/
    public function test_index()
    {
        $movieTitles = [
            'Star Trek Into Darkness',
            'Star Trek II: The Wrath of Khan',
            'Star Trek 25th Anniversary Special'
        ];

        $this->toMovieService
            ->shouldReceive('getAllOwnedMovieTitles')
            ->andReturn($movieTitles);

        $response = $this->call('GET', '/', [$this->toMovieService]);
        $response->assertViewHas('movieTitles',$movieTitles);
    }
}
