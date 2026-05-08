<script src="{{asset('assets/js/jquery-3.6.0.js')}}"></script>
<script src="{{asset('assets/js/jquery.validate.js')}}"></script>
<script src="{{asset('assets/landing/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/landing/js/progresscircle.js')}}" defer></script>
<script src="{{asset('assets/landing/js/select2.min.js')}}" defer></script>
<script src="{{asset('assets/landing/js/jquery.magnific-popup.min.js')}}" defer></script>
<script src="{{asset('assets/landing/js/jquery.waypoints.min.js')}}" defer></script>
<script src="{{asset('assets/landing/js/jquery.counterup.min.js')}}" defer></script>
<script src="{{asset('assets/landing/js/slick.min.js')}}" defer></script>
<script src="{{asset('assets/landing/js/tooltip.js')}}" defer></script>
<script src="{{asset('assets/landing/js/main.js')}}" defer></script>
<script type='text/javascript' src='{{asset('assets/vendor/toastify/toastify.js')}}' defer></script>
<script src="{{asset('assets/admin/libs/sweetalert2/sweetalert2.min.js')}}" defer></script>
<script src="{{asset('assets/vendor/lottie-player/lottie-player.js')}}" defer></script>


<script src="{{asset('assets/vendor/timepicker/jquery.timepicker.min.js')}}" defer></script>
<link rel="stylesheet" href="{{asset('assets/vendor/timepicker/jquery.timepicker.min.css')}}">


<script src="{{asset('assets/admin/libs/datatables/js/jquery.dataTables.min.js')}}" defer></script>
<script src="{{asset('assets/admin/libs/datatables/js/dataTables.bootstrap5.min.js')}}" defer></script>
<script src="{{asset('assets/admin/libs/datatables/js/dataTables.responsive.min.js')}}" defer></script>
<script src="{{asset('assets/admin/libs/datatables/js/dataTables.buttons.min.js')}}" defer></script>
{{--<script src="https://cdn.datatables.net/select/3.0.0/js/dataTables.select.min.js"></script>--}}
<script src="{{asset('assets/admin/libs/datatables/js/buttons.print.min.js')}}" defer></script>
<script src="{{asset('assets/admin/libs/datatables/js/buttons.html5.min.js')}}" defer></script>
<script src="{{asset('assets/admin/libs/datatables/js/vfs_fonts.js')}}" defer></script>
<script src="{{asset('assets/admin/libs/datatables/js/pdfmake.min.js')}}" defer></script>
<script src="{{asset('assets/admin/libs/datatables/js/jszip.min.js')}}" defer></script>
<!-- jQuery UI JS -->
<script src="{{asset('assets/vendor/jquery-ui/jquery-ui.min.js')}}" defer></script>

<script !src="">
        $( function() {
            // General datepicker initialization
            $(".datepicker").not("#dateOfBirth, #from, #to, #single_pickup_date, #single_return_date").datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 3,
                minDate: 0,
                defaultDate: new Date()
            });

            // Start Date
            $("#from").datepicker({
                numberOfMonths: 3,
                dateFormat: "yy-mm-dd",
                minDate: 0,
                changeMonth: true,
                changeYear: true,
                onSelect: function (selected) {
                    var startDate = $(this).datepicker('getDate');
                    // Optional: add 1 day if you want the end date to be strictly after start
                    startDate.setDate(startDate.getDate());
                    $("#to").datepicker("option", "minDate", startDate);

                    // Optional: clear end date if it's before new start date
                    var endDate = $("#to").datepicker('getDate');
                    if (endDate && endDate < startDate) {
                        $("#to").val('');
                    }
                }
            });

            // End Date
            $("#to").datepicker({
                numberOfMonths: 3,
                dateFormat: "yy-mm-dd",
                minDate: 0,
                changeMonth: true,
                changeYear: true
            });

            // Date of Birth
            $("#dateOfBirth").datepicker({
                numberOfMonths: 1, // Show only one month
                dateFormat: "dd - M- yy",
                maxDate: 0,
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0", // allows up to 100 years back
                onSelect: function(selected) {
                    var dob = $(this).datepicker('getDate');
                }
            });

        } );
    $(document).ready(function () {
        $('.pickuptime').timepicker({
            timeFormat: 'h:mm p',
            interval: 30,
            minTime: '7',
            maxTime: '12:00pm',
            startTime: '07:00',
            dynamic: false,
            dropdown: true,
            scrollbar: true,
            defaultTime: '10:00 AM'
        });

        $('.dropoffTime').timepicker({
            timeFormat: 'h:mm p',
            interval: 60,
            minTime: '8',
            maxTime: '06:00pm',
            startTime: '08:00',
            dynamic: false,
            dropdown: true,
            scrollbar: true,
            defaultTime: '10:00 AM'
        });
    });

    $(document).ready(function () {

        $(document).delegate('.carRequestQuote','click',function (e){
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var image = $(this).attr('data-image');
            var insurance_price = $(this).attr('data-insurance');

            // localStorage.removeItem('requestQuoteCars');
            let items = JSON.parse(localStorage.getItem('requestQuoteCars')) || [];

            let exists = items.some(item => item.id === id);
            if (!exists) {
                items.push({ id: id, name: name, image: image, insurance_price: insurance_price });
                localStorage.setItem('requestQuoteCars', JSON.stringify(items));
            }
            window.location.href = "{{ route('my.bookings') }}"
        });

        // New handler for single car page
        $(document).delegate('#checkAvailabilityBtn', 'click', function (e) {
            e.preventDefault();

            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var image = $(this).attr('data-image');
            var startDate = $('#single_pickup_date').val();
            var endDate = $('#single_return_date').val();
            var insurance_price=$('#insurance_price').val();

            let items = JSON.parse(localStorage.getItem('requestQuoteCars')) || [];

            let exists = items.some(item => item.id === id);
            if (!exists) {
                items.push({ id: id, name: name, image: image,insurance_price:insurance_price });
                localStorage.setItem('requestQuoteCars', JSON.stringify(items));
            }

            // Save dates to localStorage
            if (startDate || endDate) {
                localStorage.setItem('bookingDates', JSON.stringify({
                    start_date: startDate,
                    end_date: endDate
                }));
            }

            window.location.href = "{{ route('my.bookings') }}";
        });

    })

    {{--$(document).on('click', '.checkLogin', function () {--}}
    {{--    sendWarning('login is required!');--}}
    {{--    --}}{{--setTimeout(() => { window.location.href = "{{ route('login') }}"; }, 1000);--}}
    {{--    localStorage.setItem('redirectUrl',"{{ route('my.bookings') }}")--}}
    {{--    window.location.href = "{{ route('login') }}";--}}
    {{--});--}}
</script>

