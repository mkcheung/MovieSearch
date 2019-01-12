<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\movie;
use App\Services\ToMovieService;

class ToMovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ToMovieService $toMovieService)
    {
        $movieTitles = $toMovieService->getAllOwnedMovieTitles();

        return view('tomovies.index')->with('movieTitles',$movieTitles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        var_dump($request->getContent());
        $postParameters['movie_owned_list'] = $request->input('movie_owned_list');
        $postParameters['movie_not_owned_list'] = $request->input('movie_not_owned_list');

        $displaySuccessMessage = false;

        try {

            $movieTitlesToOwn = (!empty($postParameters['movie_owned_list'])) ? $postParameters['movie_owned_list'] : [] ; ;
            $movieNotOwned = (!empty($postParameters['movie_not_owned_list'])) ? $postParameters['movie_not_owned_list'] : [] ;


            if(!empty($movieTitlesToOwn) || !empty($movieNotOwned)){
                $displaySuccessMessage = true;
            }
            $movieNotOwnedResults = movie::whereIn('uniqueTitle',  $movieNotOwned)->get();

            foreach ($movieNotOwnedResults as $movieNotOwnedResult){
                $movieToDelete = movie::where('movie_id',7);
                if($movieToDelete){
                    $movieToDelete->delete();
                }
            }

            $movieTitleResults = movie::whereIn('uniqueTitle',  $movieTitlesToOwn)->get();
            foreach ($movieTitleResults as $movieTitleResult) {

                $uniqueTitle = $movieTitleResult->getUniqueTitle();
                if(in_array($uniqueTitle, $movieTitlesToOwn)){
                    $key = array_search($uniqueTitle, $movieTitlesToOwn);
                    unset($movieTitlesToOwn[$key]);
                }
            }


            $titlesToSave = [];
            $date = new \DateTime();
            foreach ($movieTitlesToOwn as $movieTitleToOwn){
                $movie = new movie();
                $movieTitleAndReleaseDate = explode('_', $movieTitleToOwn);
                $titlesToSave[] = [
                    'title' => $movieTitleAndReleaseDate[0],
                    'user_id' => 1,
                    'uniqueTitle' => $movieTitleToOwn,
                    'createdAt' => $date,
                    'modifiedAt' => $date
                ];
                $movie->title = $movieTitleAndReleaseDate[0];
                $movie->user_id = 1;
                $movie->uniqueTitle = $movieTitleToOwn;
                $movie->createdAt = $date;
                $movie->modifiedAt = $date;
                $movie->save();
            }

            flash('Owned Movies modified!');

            return redirect('/');
        } catch (\Exception $e){

//            $this->addFlash(
//                'warning',
//                'Error modifying owned movies!'
//            );
            return redirect('/');

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function getFromMovieDb(Request $request, ToMovieService $toMovieService)
    {
        $displayedMovies = [];

        if(empty($request->input('title'))){
            var_dump('error found');
            die;
//            $this->addFlash(
//                'warning',
//                'Please enter appropriate title.'
//            );
//            return $this->redirect($this->generateUrl('tomovies_index'));
        }

        $title = $request->input('title');

        $response = $toMovieService->getMovies($title);

        $movies = $response->results;

        if(empty($movies)){
            var_dump('error found');
            die;

//            $this->addFlash(
//                'warning',
//                'No movies with this title found.'
//            );
//            return $this->redirect($this->generateUrl('tomovies_index'));
        }
//
        $ownedUniqueMovieTitles = $toMovieService->getAllOwnedMovieTitles(true);
//
        $displayedMovies = $toMovieService->prepDisplayMovieFormat($movies, $ownedUniqueMovieTitles);

        return view('tomovies.showfromMovieDb')->with([
            'page_title'   => 'Movies',
            'numMatches' => $response->total_results,
            'movies' => $displayedMovies
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
