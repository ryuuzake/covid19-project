@extends('layouts.master')

@push('header-scripts')
    <link href="{{URL::asset('theme/css/plugins/select2/select2.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('theme/css/plugins/select2/select2-bootstrap4.min.css')}}" rel="stylesheet">
@endpush

@section('page-heading')
    @component('components.breadcrumb', ['name' => 'Reports'])
        <li class="breadcrumb-item active">
            <a href="/reports/">Reports</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="/reports/countries">Countries</a>
        </li>
    @endcomponent
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-md-8">
            <h4 class="text-left">Covid-19 Statistics data of a country</h4>
        </div>
        <div class="col-md-3">
            <select class="select2_demo_3 form-control country">
                <option disabled selected>-- choose country --</option>
                @foreach ($data as $row)
                    <option value="{{$row['Slug']}}">{{$row['Country']}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-primary" id="submitButton">Submit</button>
        </div>
    </div>
@endsection

@section('content')
<div class="row mb-2" id="currentData">
    <div class="col-lg-12">
        <div class="ibox ">
            <div class="ibox-title">
                <h4 class="text-center" id="countryTitle"><strong> Data in {{(isset($currentData['country']) ? $currentData['country'] : 'a country')}}</strong></h4>

                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title text-success" id="recovered">{{(isset($currentData['recovered']) ? $currentData['recovered'] : '???')}}</h2>
                <h6 class="card-subtitle mb-2 text-muted"></h6>
                <h4 class="card-text">patient recovered</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title text-warning" id="confirmed">{{(isset($currentData['cases']) ? $currentData['cases'] : '???')}}</h2>
                <h6 class="card-subtitle mb-2 text-muted"></h6>
                <h4 class="card-text">patient confirmed</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title text-danger" id="deaths">{{(isset($currentData['deaths']) ? $currentData['deaths'] : '???')}}</h2>
                <h6 class="card-subtitle mb-2 text-muted"></h6>
                <h4 class="card-text">patient deaths</h4>
            </div>
        </div>
    </div>    
</div>

    @component('components.chart')
            
    @endcomponent()

    @push('footer-scripts')
        {{-- Numeral JS --}}
        <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
        {{-- select2 --}}
        <script src="{{URL::asset('theme/js/plugins/select2/select2.full.min.js')}}"></script>
        <!-- ChartJS-->
        <script src="{{URL::asset('theme/js/plugins/chartJs/Chart.min.js')}}"></script>
        <script src="{{URL::asset('theme/js/demo/chartjs-demo.js')}}"></script>

        <script>
            $(document).ready(function () {

                $(".select2_demo_3").select2({
                    theme: 'bootstrap4',
                    placeholder: "Select a country",
                    allowClear: true
                });

                $('#chart').hide();
                $("#submitButton").click(function(){
                    var selectedName = $("select.country").children("option:selected").html();
                    var selectedSlug = $("select.country").children("option:selected").val();
                    $('#countryTitle').html(selectedName + ' all data');
                    var url = '/reports/countries/' + selectedSlug + '-' + selectedName;
                    document.location.href=url;
                });                

                function loops(){
                    setInterval(function () {
                        semuaData();
                    }, 2000);
                }

                // menampilkan chart ketika data sudah masuk
                if( ($('#recovered').html() && $('#confirmed').html() && $('#deaths').html() ) != '???'){
                    $('#chart').show();
                }

                // number formatting
                if($('#recovered').html() != '???'){
                    let recovered = numeral($('#recovered').html()).format('0,0');
                    $('#recovered').html(recovered);
                }
                if($('#confirmed').html() != '???'){
                    let confirmed = numeral($('#confirmed').html()).format('0,0');
                    $('#confirmed').html(confirmed);
                }
                if($('#deaths').html() != '???'){
                    let deaths = numeral($('#deaths').html()).format('0,0');
                    $('#deaths').html(deaths);
                }
        
            });
    
    </script>
    @endpush

@endsection