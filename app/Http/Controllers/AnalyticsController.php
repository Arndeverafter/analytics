<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use AkkiIo\LaravelGoogleAnalytics\Facades\LaravelGoogleAnalytics;
use AkkiIo\LaravelGoogleAnalytics\Period;
use Google\Analytics\Data\V1beta\Filter\StringFilter\MatchType;
use Google\Analytics\Data\V1beta\MetricAggregation;
use Google\Analytics\Data\V1beta\Filter\NumericFilter\Operation;
use AkkiIo\LaravelGoogleAnalytics\Traits\CustomDemographicsTrait;
use Illuminate\Support\Arr;

class AnalyticsController extends Controller
{

    use CustomDemographicsTrait;

    public function index()
    {
        // $payload =  LaravelGoogleAnalytics::dateRanges(Period::days(1), Period::days(7))
        //     ->metrics('active1DayUsers', 'active7DayUsers')
        //     ->dimensions('language', 'country', 'date', 'newVsReturning', 'city', 'userGender',  'operatingSystem', 'userAgeBracket', 'languageCode')
        //     // ->metricAggregations(MetricAggregation::TOTAL, MetricAggregation::MINIMUM)
        //     // ->whereDimension('browser', MatchType::CONTAINS, 'firefox')
        //     // ->whereMetric('active7DayUsers', Operation::GREATER_THAN, 50)
        //     // ->orderByDimensionDesc('date')
        //     ->get();


        $data = collect(
            [
                'UsersByDate' => LaravelGoogleAnalytics::getTotalUsersByDate(Period::days(7)),
                'AverageSessionDuration' => LaravelGoogleAnalytics::getAverageSessionDuration(Period::days(7)),
                'AverageSessionByDate' => LaravelGoogleAnalytics::getAverageSessionDurationByDate(Period::days(7)),
                'MostUsersByCountry' => LaravelGoogleAnalytics::getMostUsersByCountry(Period::days(7)),
                'MostUsersByCity' => LaravelGoogleAnalytics::getMostUsersByCity(Period::days(7)),
                'TotalUsersByAge' => LaravelGoogleAnalytics::getTotalUsersByAge(Period::days(7)),
                'TotalUsersByGender' => LaravelGoogleAnalytics::getTotalUsersByGender(Period::days(7)),
                'MostUsersByLanguage' => LaravelGoogleAnalytics::getMostUsersByLanguage(Period::days(7)),
                'TotalViews' => LaravelGoogleAnalytics::getTotalViews(Period::days(7)),
                'NewVsReturning' => LaravelGoogleAnalytics::getTotalNewAndReturningUsers(Period::days(7)),
                'ReturningUsersByDate' => LaravelGoogleAnalytics::getTotalNewAndReturningUsersByDate(Period::days(7)),
                'ActiveUsers' => $this->getActiveUsers(1)
            ]
        );

        return response()->json(["payload" => $data]);
    }

    private function getActiveUsers($period)
    {
        $result = LaravelGoogleAnalytics::dateRange(Period::days($period))
            ->metrics('activeUsers')
            ->get()
            ->table;

        return (int) Arr::first(Arr::flatten($result));
    }
}
