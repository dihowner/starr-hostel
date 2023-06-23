@php
    $totalPrice = 0;
@endphp

@include('components.head')">

<body class="app sidebar-mini light-mode default-sidebar">
    @include('sweetalert::alert')

    <div class="page">
        <div class="page-main">
            <div class="app-content main-content">
                <div class="side-app">

                    @include('components.topnav')

                    <div class="row mt-4">
                        <div class="col-lg-12">

                            <div class="row mb-4">

                                <div class="col-lg-7">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Shopping Cart</h3>
                                        </div>
                                        <div class="card-body ">
                                            <div class="table-responsive ">
                                                <table class="table table-bordered text-nowrap border-top">
                                                    <thead class="">
                                                        <tr>
                                                            <th>Hotel Name</th>
                                                            <th>Hotel Fee</th>
                                                            <th class="w-5"></th>
                                                        </tr>
                                                    </thead>
                                                        <tbody>
                                                            @if (count($cartItems) > 0)
                                                                @foreach ($cartItems as $cart)
                                                                    @php
                                                                        $hotelPrice = $cart->hotel->price;
                                                                        $totalPrice += $hotelPrice;
                                                                    @endphp
                                                                    <tr>
                                                                        <td>
                                                                            <div class="d-flex w-200">
                                                                                <img src="{{ asset($cart->hotel->images) }}" alt="Hotel Picture" title="{{ $cart->hotel->hotel_name }}" class="w-7 h-7">
                                                                                <h6 class="mb-0 mt-4 font-weight-bold ml-4">{{ $cart->hotel->hotel_name }}</h6>
                                                                            </div>
                                                                        </td>
                                                                        <td>&#8358;{{ number_format($hotelPrice, 2) }}</td>
                                                                        <td class="text-center">
                                                                            <a href="{{ route('delete-cart', ['id' => $cart->hotel_id]) }}" class="remove_cart">
                                                                                <svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path>
                                                                                    <path d="M8 9h8v10H8z" opacity=".3"></path><path d="M15.5 4l-1-1h-5l-1 1H5v2h14V4zM6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM8 9h8v10H8V9z"></path>
                                                                                </svg>
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach

                                                                <tr>
                                                                    <td colspan="1">Total</td>
                                                                    <td class="total h4 mb-0 font-weight-bold" colspan="2">&#8358;{{ number_format($totalPrice, 2) }}</td>
                                                                </tr>
                                                            @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-body">
                                            @if (count($cartItems) > 0)
                                                <h4>To reserve this room, kindly fill this booking form</h4>

                                                <form action="{{ route('create-payment-shopping') }}" method="POST">
                                                    @csrf
                                                    <div class="form-group mb-2">
                                                        <label><b>Check-in Date</b></label>
                                                        <input class="form-control checkInDate" name="check_in_date" readonly>
                                                    </div>

                                                    <div class="form-group mb-2">
                                                        <label><b>Check-out Date</b></label>
                                                        <input class="form-control checkOutDate" name="check_out_date" readonly>
                                                    </div>

                                                    <div class="lodge-area mb-2"></div>

                                                    <button class="btn btn-dark btn-block mb-2" type="submit"><i class="fa fa-credit-card"></i> Proceed To Payment</button>

                                                </form>
                                            @else
                                                <div class="alert alert-danger">Your shopping basket is empty</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/vendors/jquery-3.5.1.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script>
        $(document).ready(function() {

            var defaultCheckInDate = moment().format("MM/DD/YYYY");
            var defaultCheckOutDate = moment(defaultCheckInDate, 'MM/DD/YYYY').add(1, 'days')
            $('.checkInDate').val(defaultCheckInDate);
            $('.checkOutDate').val(defaultCheckOutDate.format("MM/DD/YYYY"));

            displayAdditionalInfo($('.lodge-area'))

            $('.checkInDate').datepicker({
                minDate: 0, // Restrict selection to current date and future dates
                dateFormat: 'mm/dd/yy', // Format the date as 'mm/dd/yyyy'
                onSelect: function(selectedDate) {
                    var minCheckOutDate = moment(selectedDate, 'MM/DD/YYYY').add(1, 'days'); // Add one day to Check-In date
                    var formattedMinCheckOutDate = minCheckOutDate.format('MM/DD/YYYY'); // Format the minimum Check-Out date

                    // Set the minimum Check-Out date and clear the previously selected value
                    $('.checkOutDate').datepicker('option', 'minDate', formattedMinCheckOutDate).val(formattedMinCheckOutDate);
                    displayAdditionalInfo($('.lodge-area'));
                }
            });

            // Initialize datepicker for Check-Out date
            $('.checkOutDate').datepicker({
                dateFormat: 'mm/dd/yy', // Format the date as 'mm/dd/yyyy'
                beforeShow: function() {
                    var minCheckOutDate = $('.checkInDate').val(); // Get the selected Check-In date
                    if (minCheckOutDate) {
                        minCheckOutDate = moment(minCheckOutDate, 'MM/DD/YYYY').add(1, 'day'); // Add one day to Check-In date
                        return { minDate: minCheckOutDate.format('MM/DD/YYYY') }; // Set the minimum Check-Out date
                    }
                },
                onSelect: function(selectedDate) {
                    if($(".checkInDate").val() == "") {
                        var minCheckInDate = moment(selectedDate, 'MM/DD/YYYY').subtract(1, 'days'); // Add one day to Check-In date
                        var formattedMinCheckInDate = minCheckInDate.format('MM/DD/YYYY'); // Format the minimum Check-Out date
                        // Set the minimum Check-Out date and clear the previously selected value
                        $('.checkInDate').val(formattedMinCheckInDate);
                    }
                    displayAdditionalInfo($('.lodge-area'))
                }
            });
        });

        function displayAdditionalInfo(resultField) {
            @php
                $vat = $allSettings['vat'];
            @endphp

            // Convert date strings to Date objects
            const date1 = moment($('.checkInDate').val(), 'MM/DD/YYYY');
            const date2 = moment($('.checkOutDate').val(), 'MM/DD/YYYY');

            // Calculate the time difference in milliseconds
            lodgeDays = date2.diff(date1, 'days');

            totalLodgingFee = vat = hotelTotalPrice = estimatedFee = 0;
            vat = parseFloat("{{ $vat }}");
            hotelTotalPrice = parseFloat("{{ $totalPrice }}");
            totalLodgingFee = parseFloat(hotelTotalPrice) * parseFloat(lodgeDays);
            estimatedFee = parseFloat(totalLodgingFee) + parseFloat(vat);

            resultHtml = `<ul class="booking-info">
                <li>
                    <strong>Lodging Day</strong>
                    <span>${lodgeDays} days</span>
                </li>

                <li>
                    <strong>Total Booking Fee</strong>
                    <span>&#8358;${hotelTotalPrice.toLocaleString()}</span>
                </li>

                <li>
                    <strong>VAT</strong>
                    <span>&#8358;${vat.toLocaleString()}</span>
                </li>

                <li>
                    <strong>Total Lodging Fee</strong>
                    <span>&#8358;${totalLodgingFee.toLocaleString()}</span>
                </li>

                <li>
                    <strong>Sub Total Lodging Fee</strong>
                    <span>&#8358;${estimatedFee.toLocaleString()}</span>
                </li>

            </ul>`

            resultField.html(resultHtml)
        }
    </script>

</body>


