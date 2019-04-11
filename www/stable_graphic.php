<?php
	
	ini_set("max_execution_time", 0);

	//Connection to db
	$servername = "localhost:3306";
	$username = "root";
	$password = "root";

	// Create connection
	$conn = new mysqli($servername, $username, $password);

	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 
	echo "Connected successfully";

	$limit = 10;

	//Get the logs
	$sql = "SELECT * FROM logs.log_table ORDER BY id DESC LIMIT $limit;";
	$result = $conn->query($sql);

	global $timestamp_array;
	$timestamp_array = array();

	global $timestamp_array2;
	$timestamp_array2 = array();
	
	global $istanbul_array;
	$istanbul_array = array();

	global $london_array;
	$london_array = array();

	global $tokyo_array;
	$tokyo_array = array();

	global $beijing_array;
	$beijing_array = array();

	global $moscow_array;
	$moscow_array = array();

	if ($result->num_rows > 0) {
	    // output data of each row

	    while($row = $result->fetch_assoc()) {
	        //echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
	        //echo "id: " . $row["id"]." " . $row["timestamp"]. "<br>";

	        $timestamp = $row["timestamp"];

	        //$date = date('H:i:s', strtotime( $timestamp . '+ 2 minutes') );
	        $date = date('H:i:s', strtotime( $timestamp));
	        
	        array_push($timestamp_array, $date);

	        array_push($timestamp_array2, $timestamp);
	      
	        
	   	 }
		} 
		else {
	    echo "0 results";
			}

	//Istanbul counts
	//$sqlIstanbul = "SELECT COUNT(*) FROM logs.log_table where log_server_city_name='Istanbul';";
	
		foreach ($timestamp_array2 as $y)
	{
	
	

	$sqlIstanbul = "SELECT COUNT(*) FROM logs.log_table where logs.log_table.timestamp = '$y' and log_server_city_name='Istanbul' ORDER BY id DESC LIMIT $limit;";
		
	$resultIstanbul = $conn->query($sqlIstanbul);

	if ($resultIstanbul->num_rows > 0) {
	    // output data of each row
	    while($row = $resultIstanbul->fetch_assoc()) {
	        $istanbul_row_count = $row["COUNT(*)"];
	        array_push($istanbul_array, $istanbul_row_count);

	    }
	} else {
	    echo "0 results";
			}

	//London
		$sqlLondon = "SELECT COUNT(*) FROM logs.log_table where logs.log_table.timestamp = '$y' and log_server_city_name='London' ORDER BY id DESC LIMIT $limit;";
				
			$resultLondon = $conn->query($sqlLondon);

			if ($resultLondon->num_rows > 0) {
			    // output data of each row
			    while($row = $resultLondon->fetch_assoc()) {

			        $london_row_count = $row["COUNT(*)"];
			        array_push($london_array, $london_row_count);

			    }
			} else {
			    echo "0 results";
					}

	//Tokyo
		$sqlTokyo = "SELECT COUNT(*) FROM logs.log_table where logs.log_table.timestamp = '$y' and log_server_city_name='Tokyo' ORDER BY id DESC LIMIT $limit;";
				
			$resultTokyo = $conn->query($sqlTokyo);

			if ($resultTokyo->num_rows > 0) {
			    // output data of each row
			    while($row = $resultTokyo->fetch_assoc()) {

			        $tokyo_row_count = $row["COUNT(*)"];
			        array_push($tokyo_array, $tokyo_row_count);

			    }
			} else {
			    echo "0 results";
				}

			//Beijing
			$sqlBeijing = "SELECT COUNT(*) FROM logs.log_table where logs.log_table.timestamp = '$y' and log_server_city_name='Beijing' ORDER BY id DESC LIMIT $limit;";
				
			$resultBeijing = $conn->query($sqlBeijing);

			if ($resultBeijing->num_rows > 0) {
			    // output data of each row
			    while($row = $resultBeijing->fetch_assoc()) {

			        $beijing_row_count = $row["COUNT(*)"];
			        array_push($beijing_array, $beijing_row_count);

			    }
			} else {
			    echo "0 results";
				}

			//Moscow
			$sqlMoscow = "SELECT COUNT(*) FROM logs.log_table where logs.log_table.timestamp = '$y' and log_server_city_name='Moscow' ORDER BY id DESC LIMIT $limit;";
				
			$resultMoscow = $conn->query($sqlMoscow);

			if ($resultMoscow->num_rows > 0) {
			    // output data of each row
			    while($row = $resultMoscow->fetch_assoc()) {

			        $moscow_row_count = $row["COUNT(*)"];
			        array_push($moscow_array, $moscow_row_count);

			    }
			} else {
			    echo "0 results";
				}

	}


	$conn->close();
?>


<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
      	   	

        var data = google.visualization.arrayToDataTable([
          ['Time', 'London', 'Tokyo', 'Beijing', 'Moscow', 'Istanbul'],
          //['09:00',  120,      40,      123,      57,        45],
          //['<?echo $times[1];?>',  120,      40,      123,      57,        45],

          <?
	      $i = 0;
	      foreach ($timestamp_array as $deger1){
          	//echo $deger1 . "<br>";
          	echo "['$deger1',  $london_array[$i], $tokyo_array[$i], $beijing_array[$i], $moscow_array[$i],  $istanbul_array[$i] ],";
          	$i++;
          }
          ?>




        ]);

        var options = {
          title: 'how many logs are coming from which cities',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="curve_chart" style="width: 900px; height: 500px"></div>
  </body>
</html>

