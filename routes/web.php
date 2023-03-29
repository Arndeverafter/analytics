<?php

use App\Http\Controllers\AnalyticsController;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Myoutdeskllc\LaravelAnalyticsV4\Filters\StringFilter;
use Myoutdeskllc\LaravelAnalyticsV4\Period;
use Myoutdeskllc\LaravelAnalyticsV4\PrebuiltRunConfigurations;
use Myoutdeskllc\LaravelAnalyticsV4\RunReportConfiguration;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/analytics', function () {
    // $client = App::make('laravel-analytics-v4');
    // $lastMonth = Period::months(1);
    // $results = $client->runReport(PrebuiltRunConfigurations::getMostVisitedPages($lastMonth));

    // Prepare a filter
    $analytics = App::make('laravel-analytics-v4');
    $filter = new StringFilter();
    $filter->setDimension('country');
    // $filter->setDimension('country')->exactlyMatches('United States');
    $filter->setDimension('country');

    $lastMonth = Period::days(7);

    // Prepare a report
    $reportConfig = (new RunReportConfiguration())
        // ->setStartDate(Carbon::now()->subYear()->format('Y-m-d'))
        // ->setEndDate(Carbon::now()->format('Y-m-d'))
        ->setDateRange($lastMonth)
        ->addDimensions(['country', 'date', 'newVsReturning', 'city', 'userGender',  'operatingSystem', 'userAgeBracket', 'language', 'languageCode'])
        ->addMetric('screenPageViews')
        ->orderByMetric('screenPageViews', true);
    // ->limit(200);

    // ->addMetric('activeUsers')
    // ->orderByMetric('activeUsers', true)
    // ->addFilter($filter);

    $result = $analytics->runReport($reportConfig);

    return response()->json(["message" => $result]);
});

Route::get('/analytics-v2', [AnalyticsController::class, 'index']);
