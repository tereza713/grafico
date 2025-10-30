 <?php
 include("conn.php");

 $sql = "SELECT ano, cesta_basica, dolar, gasolina FROM precos ORDER BY ano ASC";
 $stmt = $conn->query($sql);
 

$chartData = [];
$chartData[] = "['Ano', 'Cesta Básica', 'Dólar', 'Gasolina']";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $ano = $row['ano'];
    $cesta = $row['cesta_basica'];
    $dolar = $row['dolar'];
    $gasolina = $row['gasolina'];

    $chartData[] = "[$ano, $cesta, $dolar, $gasolina]";
}
?>
 
 
 
 
 <html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
        <?php echo implode(",", $chartData); ?>
        ]);

        var options = {
          title: 'Company Performance',
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
