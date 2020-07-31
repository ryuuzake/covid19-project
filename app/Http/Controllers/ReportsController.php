<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.reports.index');
    }

    public function countries()
    {
        $data = $this->fetchCountryIdentity();

        return view('pages.reports.countries', compact('data'));
    }

    public function fetchCountryIdentity()
    {
        //Country name data
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api-corona.azurewebsites.net/country",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
            $data = null;
        } else {
            $data = json_decode($response, TRUE);
        }

        return $data;
    }

    public function fetchGlobal()
    {
        //Country name data
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api-corona.azurewebsites.net/summary",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
            $data = null;
        } else {
            $data = json_decode($response, TRUE);
            $data = $data['countries'];
        }

        return $data;
    }

    public function show($selectedSlug)
    {
        $currentData = $this->fetchCurrent($selectedSlug);

        $historyData = $this->fetchHistory($selectedSlug);
        $historyData = $this->getFromFirstCase($historyData);

        $data = $this->fetchCountryIdentity();

        return view('pages.reports.countries', compact('currentData', 'historyData', 'data'));
    }

    public function getFromFirstCase($historyData)	
    {	
        $index = 0;	
        foreach($historyData as $i => $rowHistoryData){	
            if($rowHistoryData['Confirmed'] > 0){	
                $index = $i;	
                break;	
            }	
        }	
        $collection = collect($historyData);	
        $slice = $collection->slice($index);	
        $i = 0;	
        foreach($slice->all() as $data){	
            $historyDataArr[$i] = $data;	
            $i++;	
        }	

        for($idx = 0; $idx<count($historyDataArr); $idx++){	
            $dateParts = explode("-",$historyDataArr[$idx]['Date']);	
            $historyDataArr[$idx]['Date'] = ($dateParts[2] . '-' . $dateParts[0] . '-' . $dateParts[1]);	
        }	

        return $historyDataArr;	
    }

    public function fetchCurrent($slug)
    {
        //fetching current data country
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api-corona.azurewebsites.net/summary",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $data = json_decode($response, TRUE);
        if(count($data) <= 1){
            return 'failed to load';
        }

        $data = $data['countries'];
        foreach($data as $dataCountry){
            if($dataCountry['Slug'] == $slug){
                $currentData = $dataCountry;
                break;
            }
        }
        if(isset($currentData)){
            return $currentData;
        } else{
            return 'failed to load';
        }

    }

    public function fetchHistory($slug)
    {
        //fetching All data in the contry
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api-corona.azurewebsites.net/timeline/" . $slug,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $historyData = json_decode($response, TRUE);

        return $historyData;
    }

    public function getDataAroundDate($historyData, $startDate, $endDate)
    {
        $startDate = explode("/",$startDate);
        $startDate = $startDate[0] . '-' . $startDate[1] . '-' . $startDate[2];

        $endDate = explode("/",$endDate);
        $endDate = $endDate[0] . '-' . $endDate[1] . '-' . $endDate[2];
        
        foreach($historyData as $i => $rowHistoryData){
            if($rowHistoryData['Date'] >= $startDate && $rowHistoryData['Date'] <= $endDate){
                $historyDataArr[$i] = $rowHistoryData;
            }
        }

        return $historyDataArr;
    }

    public function global()
    {
        $data = $this->fetchGlobal();

        return view('pages.reports.global', compact('data'));
    }

    public function compareCountryData()
    {
        $data = $this->fetchCountryIdentity();

        return view('pages.reports.comparison', compact('data'));
    }

    public function processComparedData(Request $request)
    {
        $request->validate([
            'mainCountry' => ['required', 'string'],
            'comparedCountry' => ['required', 'string'],
            'count' => ['numeric', 'min:15']
        ]);
        
        $mainHistoryData = $this->fetchHistory($request->mainCountry);
        $mainHistoryData = $this->getFromFirstCase($mainHistoryData);
        $getMainHistoryData = array_slice($mainHistoryData, 0, $request->count);
        for($i = 0; $i < count($getMainHistoryData); $i++){
            $getMainHistoryData[$i] = array_merge($getMainHistoryData[$i], ["Label" => ('day ' . ($i+1))]);
            $getMainHistoryData[$i] = array_merge($getMainHistoryData[$i], ["Index" => $i]);
        }
        
        $comparedHistoryData = $this->fetchHistory($request->comparedCountry);
        $comparedHistoryData = $this->getFromFirstCase($comparedHistoryData);

        $maxCorrelation = 0;
        for($idx = 0; $idx < 15; $idx++){
            $getComparedHistoryData[$idx] = array_slice($comparedHistoryData, $idx, $request->count);            
            $correlations[$idx] = $this->countCountryCorrelation($getMainHistoryData, $getComparedHistoryData[$idx], $request->count);
            if($correlations[$idx] > $maxCorrelation){
                $maxCorrelation = $correlations[$idx];
                $maxIndex = $idx;
            }
        }

        for($i = 0; $i < count($getComparedHistoryData[$maxIndex]); $i++){
            $getComparedHistoryData[$maxIndex] = array_merge($getComparedHistoryData[$maxIndex], ["Label" => ('day ' . ($i+1))]);
            $getComparedHistoryData[$maxIndex] = array_merge($getComparedHistoryData[$maxIndex], ["Index" => $i]);
        }
        $getComparedHistoryData = $getComparedHistoryData[$maxIndex];

        $data = $this->fetchCountryIdentity();

        return view('pages.reports.comparison', compact(['data', 'getComparedHistoryData', 'getMainHistoryData', 'correlations', 'maxIndex']));
    }

    public function countCountryCorrelation($mainHistoryData, $comparedHistoryData, $total)
    {
        $sumX = 0; $sumY = 0;
        foreach($mainHistoryData as $i => $mainData){
            $sumX += $mainData['Confirmed'];
            $sumY += $comparedHistoryData[$i]['Confirmed'];
        }
        $avgX = ($sumX/$total);
        $avgY = ($sumY/$total);

        $diffX = 0; $diffY = 0; 
        foreach($mainHistoryData as $i => $mainData){
            $diffX = ($mainData['Confirmed'] - $avgX);
            $diffXSquare[$i] = pow($diffX, 2);
            $diffY = ($comparedHistoryData[$i]['Confirmed'] - $avgY);
            $diffYSquare[$i] = pow($diffY, 2);

            $correlationArr[$i] = ($diffX * $diffY);
        }
        $sumCorrelation = collect($correlationArr)->sum();
        $sumDiffXSquare = collect($diffXSquare)->sum();
        $sumDiffYSquare = collect($diffYSquare)->sum();
        $sx = sqrt($sumDiffXSquare / ($total - 1));
        $sy = sqrt($sumDiffYSquare / ($total - 1));
        
        $correlationFinal = $sumCorrelation / ($total * $sx * $sy);
        
        return $correlationFinal;
    }
}
