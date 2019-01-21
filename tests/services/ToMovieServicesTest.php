<?php
/**
 * Created by PhpStorm.
 * User: marscheung
 * Date: 1/21/19
 * Time: 12:05 AM
 */


namespace App\Services;
use App\Movie;
use App\Services\ToMovieService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;
use function var_dump;

class ToMovieServiceTest extends TestCase{


    public function testGetMovies()
    {
        $expected = file_get_contents(__DIR__ . '/../mockedToMovieResponse.txt');
        $mock = new MockHandler([new Response(200, [], $expected)]);
        $handler = HandlerStack::create($mock);
        $mockMovieModel = \Mockery::mock(Movie::class);
        $client = new Client(['handler' => $handler]);
        $this->toMovieService = new ToMovieService(
            $mockMovieModel,
            $client,
            'testing123'
        );

        $result = $this->toMovieService->getMovies('Star Trek 2');
        $resultArray = (array)$result;
        $this->assertArrayHasKey('results', $resultArray);
        $this->assertCount(3, $resultArray['results']);
        $this->assertEquals("Star Trek Into Darkness", $resultArray['results'][0]->title);
        $this->assertEquals("Star Trek II: The Wrath of Khan", $resultArray['results'][1]->title);
        $this->assertEquals("Star Trek 25th Anniversary Special", $resultArray['results'][2]->title);
    }

    public function testGetAllOwnedMovieTitlesNonUnique(){

        $expected = file_get_contents(__DIR__ . '/../mockedToMovieResponse.txt');
        $mock = new MockHandler([new Response(200, [], $expected)]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $retMovies = [
            $this->createMovie('Star Trek Into Darkness', 1, 'Star Trek Into Darkness_2013-05-05'),
            $this->createMovie('Star Trek II: The Wrath of Khan', 2, 'Star Trek II: The Wrath of Khan_1982-06-03'),
            $this->createMovie('Star Trek 25th Anniversary Special', 3, 'Star Trek 25th Anniversary Special_1991-09-28'),
        ];

        $mockMovieModel = \Mockery::mock(Movie::class);
        $mockMovieModel->shouldReceive('all')->andReturn($retMovies );

        $toMovieService = new ToMovieService(
            $mockMovieModel,
            $client,
            'testing123'
        );

        $movieTitles = $toMovieService->getAllOwnedMovieTitles();
        $this->assertIsArray($movieTitles);
        $this->assertCount(3, $movieTitles);
        $this->assertEquals("Star Trek Into Darkness", $movieTitles[0]);
        $this->assertEquals("Star Trek II: The Wrath of Khan", $movieTitles[1]);
        $this->assertEquals("Star Trek 25th Anniversary Special", $movieTitles[2]);
    }

    public function testGetAllOwnedMovieTitlesUnique(){

        $expected = file_get_contents(__DIR__ . '/../mockedToMovieResponse.txt');
        $mock = new MockHandler([new Response(200, [], $expected)]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $retMovies = [
            $this->createMovie('Star Trek Into Darkness', 1, 'Star Trek Into Darkness_2013-05-05'),
            $this->createMovie('Star Trek II: The Wrath of Khan', 2, 'Star Trek II: The Wrath of Khan_1982-06-03'),
            $this->createMovie('Star Trek 25th Anniversary Special', 3, 'Star Trek 25th Anniversary Special_1991-09-28'),
        ];

        $mockMovieModel = \Mockery::mock(Movie::class);
        $mockMovieModel->shouldReceive('all')->andReturn($retMovies );

        $toMovieService = new ToMovieService(
            $mockMovieModel,
            $client,
            'testing123'
        );

        $movieTitles = $toMovieService->getAllOwnedMovieTitles(true);
        $this->assertIsArray($movieTitles);
        $this->assertCount(3, $movieTitles);
        $this->assertEquals("Star Trek Into Darkness_2013-05-05", $movieTitles[0]);
        $this->assertEquals("Star Trek II: The Wrath of Khan_1982-06-03", $movieTitles[1]);
        $this->assertEquals("Star Trek 25th Anniversary Special_1991-09-28", $movieTitles[2]);
    }

    public function testPrepDisplayMovieFormat(){

        $expected = file_get_contents(__DIR__ . '/../mockedToMovieResponse.txt');
        $mock = new MockHandler([new Response(200, [], $expected)]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $retMovies = [
            $this->createMovie('Star Trek Into Darkness', 1, 'Star Trek Into Darkness_2013-05-05'),
            $this->createMovie('Star Trek II: The Wrath of Khan', 2, 'Star Trek II: The Wrath of Khan_1982-06-03'),
            $this->createMovie('Star Trek 25th Anniversary Special', 3, 'Star Trek 25th Anniversary Special_1991-09-28'),
        ];

        $mockMovieModel = \Mockery::mock(Movie::class);
        $mockMovieModel->shouldReceive('all')->andReturn($retMovies );

        $toMovieService = new ToMovieService(
            $mockMovieModel,
            $client,
            'testing123'
        );

        $movies = file_get_contents(__DIR__ . '/../mockedToMovieResponse.txt');
        $movies = json_decode($movies);
        $displayedMovies = $toMovieService->prepDisplayMovieFormat($movies->results, []);
        $this->assertCount(3, $displayedMovies);

        $this->assertEquals("startrekintodarkness", $displayedMovies[0]['id']);
        $this->assertEquals("Star Trek Into Darkness", $displayedMovies[0]['title']);
        $this->assertEquals("Star Trek Into Darkness_2013-05-05", $displayedMovies[0]['unique_title']);
        $this->assertEquals("2013-05-05", $displayedMovies[0]['release_date']);
        $this->assertTrue(is_string($displayedMovies[0]['overview']));
        $this->assertFalse($displayedMovies[0]['owned']);

        $this->assertEquals("startrekiithewrathofkhan", $displayedMovies[1]['id']);
        $this->assertEquals("Star Trek II: The Wrath of Khan", $displayedMovies[1]['title']);
        $this->assertEquals("Star Trek II: The Wrath of Khan_1982-06-03", $displayedMovies[1]['unique_title']);
        $this->assertEquals("1982-06-03", $displayedMovies[1]['release_date']);
        $this->assertTrue(is_string($displayedMovies[1]['overview']));
        $this->assertFalse($displayedMovies[1]['owned']);

        $this->assertEquals("startrek25thanniversaryspecial", $displayedMovies[2]['id']);
        $this->assertEquals("Star Trek 25th Anniversary Special", $displayedMovies[2]['title']);
        $this->assertEquals("Star Trek 25th Anniversary Special_1991-09-28", $displayedMovies[2]['unique_title']);
        $this->assertEquals("1991-09-28", $displayedMovies[2]['release_date']);
        $this->assertTrue(is_string($displayedMovies[2]['overview']));
        $this->assertFalse($displayedMovies[2]['owned']);
}

public function testPrepDisplayMovieFormatOwnedEqualsTrue(){

    $expected = file_get_contents(__DIR__ . '/../mockedToMovieResponse.txt');
    $mock = new MockHandler([new Response(200, [], $expected)]);
    $handler = HandlerStack::create($mock);
    $client = new Client(['handler' => $handler]);

    $retMovies = [
        $this->createMovie('Star Trek Into Darkness', 1, 'Star Trek Into Darkness_2013-05-05'),
        $this->createMovie('Star Trek II: The Wrath of Khan', 2, 'Star Trek II: The Wrath of Khan_1982-06-03'),
        $this->createMovie('Star Trek 25th Anniversary Special', 3, 'Star Trek 25th Anniversary Special_1991-09-28'),
    ];

    $mockMovieModel = \Mockery::mock(Movie::class);
    $mockMovieModel->shouldReceive('all')->andReturn($retMovies );

    $toMovieService = new ToMovieService(
        $mockMovieModel,
        $client,
        'testing123'
    );

    $ownedMovies = [
        'Star Trek Into Darkness_2013-05-05'
    ];

    $movies = file_get_contents(__DIR__ . '/../mockedToMovieResponse.txt');
    $movies = json_decode($movies);
    $displayedMovies = $toMovieService->prepDisplayMovieFormat($movies->results, $ownedMovies);
    $this->assertCount(3, $displayedMovies);

    $this->assertEquals("startrekintodarkness", $displayedMovies[0]['id']);
    $this->assertEquals("Star Trek Into Darkness", $displayedMovies[0]['title']);
    $this->assertEquals("Star Trek Into Darkness_2013-05-05", $displayedMovies[0]['unique_title']);
    $this->assertEquals("2013-05-05", $displayedMovies[0]['release_date']);
    $this->assertTrue(is_string($displayedMovies[0]['overview']));
    $this->assertTrue($displayedMovies[0]['owned']);

    $this->assertEquals("startrekiithewrathofkhan", $displayedMovies[1]['id']);
    $this->assertEquals("Star Trek II: The Wrath of Khan", $displayedMovies[1]['title']);
    $this->assertEquals("Star Trek II: The Wrath of Khan_1982-06-03", $displayedMovies[1]['unique_title']);
    $this->assertEquals("1982-06-03", $displayedMovies[1]['release_date']);
    $this->assertTrue(is_string($displayedMovies[1]['overview']));
    $this->assertFalse($displayedMovies[1]['owned']);

    $this->assertEquals("startrek25thanniversaryspecial", $displayedMovies[2]['id']);
    $this->assertEquals("Star Trek 25th Anniversary Special", $displayedMovies[2]['title']);
    $this->assertEquals("Star Trek 25th Anniversary Special_1991-09-28", $displayedMovies[2]['unique_title']);
    $this->assertEquals("1991-09-28", $displayedMovies[2]['release_date']);
    $this->assertTrue(is_string($displayedMovies[2]['overview']));
    $this->assertFalse($displayedMovies[2]['owned']);
}

    private function createMovie($title, $userId, $uniqueTitle){
        return new Movie(['title' => $title, 'user_id' => $userId, 'uniqueTitle' => $uniqueTitle]);
    }
}