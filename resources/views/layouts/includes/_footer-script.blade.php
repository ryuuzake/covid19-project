{{-- Laravel Mix - JS File --}}
{{-- <script src="{{ mix('js/gate.js') }}"></script> --}}
    <!-- Mainly scripts -->
    <script src="{{URL::asset('theme/js/jquery-3.1.1.min.js')}}"></script>
    <script src="{{URL::asset('theme/js/popper.min.js')}}"></script>
    <script src="{{URL::asset('theme/js/bootstrap.js')}}"></script>
    <script src="{{URL::asset('theme/js/plugins/metisMenu/jquery.metisMenu.js')}}"></script>
    <script src="{{URL::asset('theme/js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>

    <!-- Custom and plugin javascript -->
    <script src="{{URL::asset('theme/js/inspinia.js')}}"></script>
    <script src="{{URL::asset('theme/js/plugins/pace/pace.min.js')}}"></script>

    <!-- jQuery UI -->
    <script src="{{URL::asset('theme/js/plugins/jquery-ui/jquery-ui.min.js')}}"></script>

    <!-- Data Table-->
    <script src="{{URL::asset('theme/js/plugins/dataTables/datatables.min.js')}}"></script>
    <script src="{{URL::asset('theme/js/plugins/dataTables/dataTables.bootstrap4.min.js')}}"></script>

    <!-- Numeral JS -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    
    <!-- select2 -->
    <script src="{{URL::asset('theme/js/plugins/select2/select2.full.min.js')}}"></script>

    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function(){
            $('.dataTables-example').DataTable({
                responsive: true,
                searchable: false,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    { extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel', title: 'ExampleFile'},
                    {extend: 'pdf', title: 'ExampleFile'},

                    {extend: 'print',
                        customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                        }
                    }
                ]

            });

        });
    </script>


    <script>
        $(window).bind("scroll", function () {
            let toast = $('.toast');
            toast.css("top", window.pageYOffset + 20);

        });
    </script>
