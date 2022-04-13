<?php

use App\Model\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/submitAnswer', function (Request $request) {

    $validatedData = $request->validate([
        'answer' => ['required',Rule::in(['Good', 'Fair','Neutral','Bad'])],
    ]);

    Survey::create(['rate' => $validatedData['answer']]);

    return response()->json(['Message' => 'Success!'], 200);

});

Route::get('/ratings', function () {

    $totalRatingsCount = Survey::count();
    $surveys = Survey::all();

    $goodRatingsCount = $surveys->where('rate', '=','Good')->count();
    $fairRatingsCount = $surveys->where('rate', '=','Fair')->count();
    $neutralRatingsCount = $surveys->where('rate', '=','Neutral')->count();
    $badRatingsCount = $surveys->where('rate', '=','Bad')->count();

    return collect([
        ['name'=>'Good','count'=>$goodRatingsCount,'percentage'=>round(((100*$goodRatingsCount)/$totalRatingsCount),2)],
        ['name'=>'Fair','count'=>$fairRatingsCount,'percentage'=>round(((100*$fairRatingsCount)/$totalRatingsCount),2)],
        ['name'=>'Neutral','count'=>$neutralRatingsCount,'percentage'=>round(((100*$neutralRatingsCount)/$totalRatingsCount),2)],
        ['name'=>'Bad','count'=>$badRatingsCount,'percentage'=>round(((100*$badRatingsCount)/$totalRatingsCount),2)],
    ])->all();

});
