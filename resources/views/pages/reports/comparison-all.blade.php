@extends('layouts.master')

@push('header-scripts')
    <link href="{{URL::asset('theme/css/plugins/select2/select2.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('theme/css/plugins/select2/select2-bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('theme/css/plugins/datapicker/datepicker3.css')}}" rel="stylesheet">
    <link href="{{URL::asset('theme/css/plugins/morris/morris-0.4.3.min.css')}}" rel="stylesheet">
@endpush


@section('page-heading')
    <form action="/reports/compare-all" method="post" id="compare-countries">
    @csrf
    <div class="row wrapper page-heading">
        <div class="col-md-12 vertical-align-middle">
            <h2>Mencari Relasi terdekat Suatu Negara</h2>
        </div>
    </div>
    <div class="row wrapper page-heading">
        <div class="col-md-2 vertical-align-middle">
            <h3>Pilih Negara</h3>
        </div>
        <div class="col-md-3 mr-1">
            <select class="select2_country form-control country @error('mainCountry') is-invalid @enderror" required name="mainCountry">
                @isset($data)
                    @foreach ($data as $row)
                        <option value="" disabled selected></option>
                        <option value="{{$row['Slug']}}">{{$row['Country']}}</option>
                    @endforeach                    
                @endisset
            </select>
            @error('mainCountry')
                <div class="invalid-feedback">{{$message}}</div>
            @enderror
        </div>
        <div class="ml-1 col-md-2">
            <h3>Pilih Periode Data (hari)</h3>
        </div>
        <div class="col-md-3">
            <input type="number" min="15" required placeholder="" name="count" class="form-control @error('count') is-invalid @enderror">
            @error('count')
              <div class="invalid-feedback">{{$message}}</div>
            @enderror
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-primary" id="submitButton">Submit</button>
        </div>
    </div>
    </form>
@endsection

@section('content')

<div id="contentWrapper">   
    @isset($maxCorrelation)
    <div class="tabs-container">
        <ul class="nav nav-tabs" role="tablist">
            @foreach ($getComparedHistoryData as $i => $countryData)
                <li><a class="nav-link" data-toggle="tab" href="#country-{{$i}}">{{$countryData[0]['Country']}}</a></li>
            @endforeach
        </ul>
        <div class="tab-content">
            @foreach ($getComparedHistoryData as $i => $countryData)
                <div role="tabpanel" id="country-{{$i}}" class="tab-pane">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h3 class="text-center font-weight-bold">
                                {{$getMainHistoryData[0]['Country']}} with {{$countryData[0]['Country']}}
                            </h3>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-footer" id="ibox_ost">
                            <div>
                                <h5 class="font-weight-bold">Highest correlation between 
                                    {{$getMainHistoryData[0]['Country']}} and {{$countryData[0]['Country']}} is {{$maxCorrelation[$i]}}
                                </h5>
                            </div>
                            <div>
                                @component('components.allComparisonChart.confirmedChart', ['getMainHistoryData' => $getMainHistoryData, 'getComparedHistoryData' => $countryData])
                                @endcomponent

                                @component('components.allComparisonChart.recoveredChart', ['getMainHistoryData' => $getMainHistoryData, 'getComparedHistoryData' => $countryData])
                                @endcomponent

                                @component('components.allComparisonChart.deathsChart', ['getMainHistoryData' => $getMainHistoryData, 'getComparedHistoryData' => $countryData])
                                @endcomponent
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endisset

    

</div>

    @push('footer-scripts')
    <!-- ChartJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>        
    <script src="{{URL::asset('theme/js/plugins/datapicker/bootstrap-datepicker.js')}}"></script>

    {{-- MorrisJS --}}
    <script src="{{URL::asset('theme/js/plugins/morris/raphael-2.1.0.min.js')}}"></script>
    <script src="{{URL::asset('theme/js/plugins/morris/morris.js')}}"></script>

    <script>
        
        $(document).ready(function () {
            $('#contentWrapper').hide();

            @isset($getComparedHistoryData)
                $('#contentWrapper').show();
            @endisset

            $(".select2_country").select2({
                theme: 'bootstrap4',
                placeholder: "Select a target country",
                // allowClear: true
            });

            $('.input-daterange').datepicker({
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true
            });

            $(".select2_countries").select2({
                theme: 'bootstrap4',
                placeholder: "Select countries here",
            });

            $("#submitButton").click(function(){
                $('#compare-countries').submit();
            });

        });

    </script>
    @endpush
@endsection