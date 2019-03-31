<!doctype html>
<?php
	// Establish a database connection
	$db = new mysqli('localhost', 'dillon', 'Dragon1985', 'temps');

	// If unable to connect to db, show error message
	if(mysqli_connect_errno()) {
		echo "<p>Error: Could not connect to database </br >
		Please Try again later.</p>";
		exit;
	}
?>
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

    <!-- Plotly.js -->
  <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>

    <title>Thermal Ranger: HawkHack 2019</title>
  </head>
  <body>
		<!-- navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
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
					<li class="nav-item">
						<a class="nav-link" href="tempsGraph.php">Graph</a>
					</li>
          <li class="nav-item">
              <a class="nav-link active" href="viewData.php">View the Data</a>
          </li>
        </ul>
      </div>
    </nav>

		<div class="container">
			<div class="row">
				<div class="col-sm-2">
					<!-- take up some space  -->
				</div>

				<div class="col-sm-8 justify-content-around">
					<?php
						// SQL Query to fetch an associative array populated with the db's temp data
						$query = $db->query("SELECT * FROM TEMPS");
							if ($query->num_rows > 0) {
								while ($row = $query-> fetch_assoc()) {
									echo '<em>Time: </em>'.$row['time'].
									'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em> Temp C:</em> '.$row['cTemp'].
									'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>Temp F</em>: '.$row['fTemp'].'<br />';
								}
							}
							else {
								echo "Unsuccessful query, please try again.";
							}
					 ?>
				</div>

				<div class="col-sm-2">
					<!-- take up some space  -->
				</div>
			</div>

		</div>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
