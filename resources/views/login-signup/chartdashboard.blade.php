<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top 10 Report Data</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            /* Light gray background */
        }

        .container {
            margin-top: 5px;
        }

        #donutchart {
            width: 100%;
            /* Full width for responsiveness */
            height: 500px;
            /* Fixed height */
        }
    </style>
</head>

<body>
    <nav>
        <x-welcome-header />
    </nav>
    <div class="  rounded  mt-3">
                    <h1 class="text-center"><em>DashBoard- Page</em></h1>
                   </div>
    <div class="container">
        <div class="row m-5">
            <div class="col-md-3">
                <label for="categorySelect">Select Category:</label>
                <select id="categorySelect" class="form-control">
                    <option value="2">Fitness</option>
                    <option value="1">Meditation</option>
                    <option value="3">Education</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="startDate">Start Date:</label>
                <input type="text" id="startDate" class="form-control datepicker" placeholder="Select Start Date"
                    readonly>
            </div>
            <div class="col-md-3">
                <label for="endDate">End Date:</label>
                <input type="text" id="endDate" class="form-control datepicker" placeholder="Select End Date" readonly>
            </div>
            <div class="col-md-3 mt-4">
                <button id="fetchData" class="btn btn-primary">Fetch Videos</button>
            </div>
            <div class="col-md-3 mt-4">
                <button id="downloadData" class="btn btn-secondary">downloadData</button>
            </div>
        </div>

        <h2 class="text-center mt-4">Top 10 Report of </h2>
        <div class="customder" style="display:none;">
            <center>
                <img class="loading-image" src="https://cdn.breathewellbeing.in/downloads/videos/images/ajaxloader.gif"
                    alt="loading..">
            </center>
        </div>
        <div id="donutchart"></div>

        <table class="table table-bordered" id="fitnessTable">
            <thead>
                <tr>
                    <th>Category Title</th>
                    <th>Total Workout Sessions</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be appended here via AJAX -->
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsleyjs/2.9.2/parsley.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize datepickers
            $(".datepicker").datepicker({
                dateFormat: "yy-mm-dd", // Format for the date input
                maxDate: 0
            });
            var downloadButton = $('#downloadData');
            downloadButton.prop('disabled', true);

            var today = new Date();
            var sixMonthsLater = new Date();
            sixMonthsLater.setMonth(today.getMonth() - 6);

            // Format dates to YYYY-MM-DD
            var formattedToday = today.toISOString().split('T')[0];
            var formattedSixMonthsLater = sixMonthsLater.toISOString().split('T')[0];

            // Set the value of the datepickers
            $('#startDate').val(formattedToday);
            $('#endDate').val(formattedSixMonthsLater);

            $('#fetchData').on('click', function () {
                downloadButton.prop('disabled', true);
                var fetchButton = $(this);
                fetchButton.prop('disabled', true);
                $('.customder').show();
                var categoryId = $('#categorySelect').val();
                var startDate = $('#startDate').val(); // Get start date
                var endDate = $('#endDate').val(); // Get end date
                console.log(categoryId, startDate, endDate);

                $.ajax({
                    url: '{{ route('account.chartdata') }}', // Ensure this matches your route
                    method: 'GET',
                    data: {
                        category_id: categoryId,
                        start_date: startDate,
                        end_date: endDate
                    },

                    dataType: 'json', // Expect JSON data back

                    success: function (response) {
                        fetchButton.prop('disabled', false);
                        downloadButton.prop('disabled', false);
                        console.log(response);
                        $('#fitnessTable tbody').empty();//  Clear existing table body
                        var chartData = [['Category Title', 'Total Workout Sessions']];

                        // Loop through the response data and populate the table
                        $.each(response.data, function (index, category) {
                            $('#fitnessTable tbody').append(
                                `<tr>
                                    <td>${category.title}</td>
                                    <td>${category.video_count}</td>
                                     <td>${category.start_date}</td>
                                      <td>${category.end_date}</td>
                                </tr>`
                            );

                            // Add to chart data
                            chartData.push([category.title, parseInt(category.video_count)]);
                        });

                        // Draw the chart after populating data
                        google.charts.load('current', { packages: ['corechart'] });
                        google.charts.setOnLoadCallback(function () {
                            var dataTable = google.visualization.arrayToDataTable(chartData);
                            var options = {
                                title: 'Top 10 Sessions',
                                pieHole: 0.4, // For donut chart
                            };
                            var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
                            chart.draw(dataTable, options);
                        });
                        $('.customder').hide();
                        // $('#fitnessTable').DataTable().clear().destroy(); 
                         initializeYajraTable(response.data);

                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching data: ", xhr.responseText);
                        fetchButton.prop('disabled', true);
                        $('.customder').hide();
                    }
                });
            });
            // // Function to initialize Yajra DataTable
            function initializeYajraTable(data) {
                // Destroy existing DataTable if it exists
                if ($.fn.dataTable.isDataTable('#fitnessTable')) {
                    $('#fitnessTable').DataTable().clear().destroy();
                }

                // Populate the table with the fetched data
                $('#fitnessTable tbody').empty(); // Clear existing table body
                $.each(data, function (index, category) {
                    $('#fitnessTable tbody').append(
                        `<tr>
                                    <td>${category.title}</td>
                                    <td>${category.video_count}</td>
                                     <td>${category.start_date}</td>
                                      <td>${category.end_date}</td>
                                </tr>` 
                    );
                });

                // Initialize Yajra DataTable
                $('#fitnessTable').DataTable({
                    paging: true,
                    searching: true,
                    ordering: true,
                });
            }


            // Fetch data on button click

            // here i add the download function
            $('#downloadData').on('click', function () {
                var downloadButton = $(this);
                downloadButton.prop('disabled', true);
                var fetchButton = $('#fetchData');
                fetchButton.prop('disabled', true);//for fetch button
                $('.customder').show();
                var categoryId = $('#categorySelect').val();
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();

                // Construct the URL for downloading data
                var url = '{{ route('download.data') }}?category_id=' + categoryId + '&start_date=' + startDate + '&end_date=' + endDate;

                // Trigger the download by redirecting the user
                window.location.href = url;
                setTimeout(function () {
                    downloadButton.prop('disabled', false);
                    fetchButton.prop('disabled', false);
                    $('.customder').hide();
                }, 10000);

            });


            // Optionally, fetch default data on page load
            $('#fetchData').click();
        });
    </script>
</body>

</html>