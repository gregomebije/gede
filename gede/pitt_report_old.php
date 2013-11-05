<!DOCTYPE HTML>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <title>Highcharts Example</title>
    <script type="text/javascript" src="assets/js/jquery.js"></script>
     <script type="text/javascript">
      $(function () {
        $('#container').highcharts({
            chart: {
                type: 'column'
            },
    
            title: {
                text: 'Graphic presentation of #s reached'
            },
    
            xAxis: {
                categories: ['Cohort Total', 'Reached 1', 'Reached 2', 
                 'Reached 3', 'OSY', 'ISY', 'PLWHA', 'MSM', 'IDUs', 
                 'TWKersM', 'CSWM', 'USMM', 'Others']
            },
    
            yAxis: {
                allowDecimals: false,
                min: 0,
                title: {
                    text: '#s reached'
                }
            },
    
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.x +'</b><br/>'+
                        this.series.name +': '+ this.y +'<br/>'+
                        'Total: '+ this.point.stackTotal;
                }
            },
    
            plotOptions: {
                column: {
                    stacking: 'normal'
                }
            },
    
            series: [{
                name: 'Male',
                data: [396, 0, 0, 396, 0, 0, 0, 0, 0, 396, 0, 0, 0],
                stack: 'male'
            }, {
                name: 'Female',
                data: [542, 0, 0, 542, 0, 0, 0, 0, 0, 1, 541, 0, 0],
                stack: 'female'
            }] 
        });
    });
    

		</script>
	</head>
	<body>
        <script src="assets/js/highcharts.js"></script>
        <script src="assets/js/modules/exporting.js"></script>
        <table>
         <thead>
          <tr>
           <td>Cohort Total</td>
           <td>Reached 1</td>
           <td>
<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>

	</body>
</html>
