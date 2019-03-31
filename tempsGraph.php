<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- custom CSS -->
    <link rel="stylesheet" href="thermal.css">

    <!-- Font Awesome  -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Germania+One" rel="stylesheet">

    <title>Thermal Ranger: HawkHack 2019</title>
  </head>
  <body>

    <!-- navbar -->
    <nav class="navbar navbar-expand-md navbar-light bg-light">
      <a class="navbar-brand brandText" href="index.html"><i class="fas fa-temperature-high mr-2"></i>Thermal Ranger</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="index.html">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="about.html">About</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="tempsGraph.php">Graph</a>
          </li>
        </ul>
      </div>
    </nav>

    <?php
        //address of the server where db is installed
        $servername = "localhost";
        //username to connect to the db
        //the default value is root
        $username = "dillon";
        //password to connect to the db
        //this is the value you would have specified during installation of WAMP stack
        $password = "Dragon1985";
        //name of the db under which the table is created
        $dbName = "temps";
        //establishing the connection to the db.
        $conn = new mysqli($servername, $username, $password, $dbName);
        //checking if there were any error during the last connection attempt
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }
        //the SQL query to be executed
        $query = "SELECT * FROM TEMPS";
        //storing the result of the executed query
        $result = $conn->query($query);
        //initialize the array to store the processed data
        $jsonArray = array();
        //check if there is any data returned by the SQL Query
        if ($result->num_rows > 0) {
          //Converting the results into an associative array
          while($row = $result->fetch_assoc()) {
            $jsonArrayItem = array();
            $jsonArrayItem['label'] = $row['time'];
            $jsonArrayItem['value'] = $row['fTemp'];
            //append the above created object into the main array.
            array_push($jsonArray, $jsonArrayItem);
          }
        }
        //Closing the connection to DB
        $conn->close();
        //set the response content type as JSON
        header('Content-Type: application/json');
        //output the return value of json encode using the echo function.
        echo json_encode($jsonArray);
    ?>

    <script type="text/javascript">
    var apiChart = new FusionCharts({
      type: 'column2d',
      renderAt: 'api-chart-container',
      width: '550',
      height: '350',
      dataFormat: 'json',
      dataSource: {
        "chart": chartProperties,
        "data": chartData
      }
      });
    </script>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
