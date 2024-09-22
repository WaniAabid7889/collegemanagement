<?php 
session_start();
if(isset($_SESSION['username']) && isset($_SESSION['password'])){
    require_once('header.php');
    ?>

<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Task', 'Hours per Day'],
                ['Work',     11],
                ['function',      2],
                ['placements',  2],
                ['courses', 2],
                ['profit',    7]
            ]);

            var options = {
               title: 'Daily College Activities'
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

            chart.draw(data, options);
      }
</script>

<script>
        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
            var data = google.visualization.arrayToDataTable([
            ['Year', 'students', 'PassOut', 'placements'],
            ['2019', 1000, 400, 200],
            ['2020', 1170, 460, 250],
            ['2021', 660, 1120, 300],
            ['2022', 1030, 540, 350]
        ]);

        var options = {
          chart: {
            title: 'college Performance',
            subtitle: 'Total Students, PassOut, and Profit: 2019-2024',
          }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
</script>

    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body p-2">
                    <h5 class="card-title">Students</h5>
                    <p class="card-text">Manage student information, enrollment, and more.</p>
                    <a href="../pages/students.php" class="btn btn-light align-center" style="position: relative;left: 75px;">Go to Students</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body p-2">
                    <h5 class="card-title">Teachers</h5>
                    <p class="card-text">Manage teacher profiles, assignments, and more.</p>
                    <a href="teachers.php" class="btn btn-light" style="position: relative;left: 75px;">Go to Teachers</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body p-2">
                    <h5 class="card-title">Courses</h5>
                    <p class="card-text">Manage courses, schedules, and more.</p>
                    <a href="course.php" class="btn btn-light" style="position: relative;left: 75px;" >Go to Courses</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body p-2">
                    <h5 class="card-title">Enrollments</h5>
                    <p class="card-text">Manage enrollments and related information.</p>
                    <a href="../pages/enrollments.php" class="btn btn-light" style="position: relative;left: 75px;">Go to Enrollments</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div id="columnchart" style="width: 700px; height: 400px;"></div>
        </div>
        <div class="col-lg-3">
            <div id="piechart" style="width: 500px; height: 450px;"></div>
        </div>
    </div>

<?php
    require_once('footer.php');
}
?>