<?php
namespace App\Services;
use GuzzleHttp\Client as GuzzleClient;
use App\Movie;

class ToMovieService
{
    private $gClient;
    private $api_key;
    private $movie;

    public function __construct(Movie $movie, GuzzleClient $gClient, $api_key){
        $this->movie = $movie;
        $this->gClient = $gClient;
        $this->api_key = $api_key;
    }

    public function getMovies($title)
    {

        $movieApiUrl = 'https://api.themoviedb.org/3/search/movie?include_adult=false&page=1&language=en-US&api_key='.$this->api_key.'&query=\''.$title.'\'';

        $res          = $this->gClient->request('GET', $movieApiUrl);
        $jsonResponse = $res->getBody();
        $response    = json_decode($jsonResponse);

        return $response;
    }

    public function getAllOwnedMovieTitles($unique=false){

        $movieTitles = [];
        $movies = $this->movie->all();
        if($unique) {
            foreach ($movies as $movie) {
                array_push($movieTitles,$movie->getUniqueTitle());
            }
        } else {
            foreach ($movies as $movie) {
                array_push($movieTitles,$movie->getTitle());
            }
        }
        return $movieTitles;
    }

    public function prepDisplayMovieFormat($movies, $ownedUniqueMovieTitles){

        $displayedMovies = [];

        $movieCount = count($movies);

        if($movieCount > 0){

            $numMoviesToDisplay = ($movieCount < 10) ? $movieCount : 10;

            for($i = 0; $i < $numMoviesToDisplay ; $i++){
                $displayedMovies[$i]['id'] = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', preg_replace('/\s+/', '_', $movies[$i]->title)));
                $displayedMovies[$i]['title'] = $movies[$i]->title;
                $displayedMovies[$i]['unique_title'] = $movies[$i]->title.'_'.$movies[$i]->release_date;
                $displayedMovies[$i]['release_date'] = $movies[$i]->release_date;
                $displayedMovies[$i]['overview'] = $movies[$i]->overview;
                $displayedMovies[$i]['owned'] = false;
            }
        }

        foreach ($displayedMovies as &$displayedMovie) {
            if(in_array($displayedMovie['unique_title'], $ownedUniqueMovieTitles)){
                $displayedMovie['owned'] = true;
            }
        }

        return $displayedMovies;
    }
}