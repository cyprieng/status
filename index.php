<html>
	<head>
		<meta charset="utf-8" />
		<style>
			body {
				background: #fff;
				font-family: Georgia, 'Times New Roman', serif;
				padding: 0;
				margin: 0;
			}
			#container {
				margin: 0 auto;
				width: 1000px;
				background: #fff;
				padding: 20px 50px 20px 50px;
			}
			header {
				margin-bottom: 40px;
				width: 500px;
				float: left;
			}
			header h1{
				font-weight: 100;
				font-size: 47px;
				margin: 0;
			}
			header h3 {
				font-size: 25px;
				font-weight: 100;
				margin: 0;
				margin-left: 30px;
				font-style: italic;
			}
			.block {
				margin-bottom: 40px;
				clear: both;
			}
			.block h3 {
				font-size: 35px;
				font-weight: 100;
				margin: 0;
			}
			.block p {
				font-size: 25px;
				margin: 0;
				margin-left: 35px;
			}
			.barContainer {
				width: 1000px;
				height: 40px;
				background-color: #eee;
				margin-left: 35px;
				overflow: hidden;
			}
			.bar {
				display: block;
				height: 40px;
				width: 0;
				background-color: #333;
				overflow: hidden;
			}
			a {
				color: #222;
				text-decoration: underline;
			}
			a:hover {
				text-decoration: none;
				background: #000;
				color: #fff;
			}
			#updateBlock{
				float: right;
			}
		</style>
		<script src="http://code.jquery.com/jquery-2.0.2.min.js"></script>
		<script>
		// I am not the best at AJAX or Javascript in general. Feel free to recommend changes.
		var GREEN = "#3DB015";
		var YELLOW = "#FAFC4F";
		var RED = "#C9362E";
		function loadColors(load)
		{
			if(load < 0.75)
			{
				return "<?=GREEN;?>";
			} else if(load < 1)
			{
				return "<?=YELLOW;?>";
			} else if(load > 1)
			{
				return "<?=RED;?>";
			}
		}
		function updateAll()
		{
			console.log("Updating all");
			$.get("result.php", function(raw) {
				stats = eval('(' + raw + ')');
				$("#uptime").html(stats.uptime);
				
				$("#temp").html(stats.temp + '°C');

				if(stats.load[0] < 0.75)
				{
					$("#loadStatus").html("Good");
					$("#loadStatus").css("color",GREEN);
				} else if(stats.load[0] < 0.75)
				{
					$("#loadStatus").html("Warning!");
					$("#loadStatus").css("color","#000");
				} else if(stats.load[0] > 1)
				{
					$("#loadStatus").html("Overloaded!");
					$("#loadStatus").css("color",RED);
				}
				$("#loadOne").html("Last 60 seconds: " + stats.load[0]);
				$("#loadTwo").html("Last 5 minutes: " + stats.load[1]);
				$("#loadThree").html("Last 15 minutes: " + stats.load[2]);
				$("#loadBarOne").animate({
					width: (stats.load[0] * 1000) + "px"
				},1000,function(){});
				$("#loadBarTwo").animate({
					width: (stats.load[1] * 1000) + "px"
				},1000,function(){});
				$("#loadBarThree").animate({
					width: (stats.load[2] * 1000) + "px"
				},1000,function(){});
				$("#loadBarOne").css("background-color",loadColors(stats.load[0]));	
				$("#loadBarTwo").css("background-color",loadColors(stats.load[1]));	
				$("#loadBarThree").css("background-color",loadColors(stats.load[2]));	

				$("#procSpeed").html(stats.proc);
				$("#cpuBar").animate({
					width: (stats.proc * 10) + "px"
				},1000,function(){});

				$("#diskInfo1").html(stats.disk1[0] + "%, " + stats.disk1[1] + " used / " + stats.disk1[2] + "total");
				$("#diskBar1").animate({
					width: (stats.disk1[0] * 10) + "px"
				},1000,function(){});

				$("#diskInfo2").html(stats.disk2[0] + "%, " + stats.disk2[1] + " used / " + stats.disk2[2] + "total");
				$("#diskBar2").animate({
					width: (stats.disk2[0] * 10) + "px"
				},1000,function(){});

				$("#memInfo").html(stats.memory[0] + "%, " + stats.memory[3] + " used / " + stats.memory[4] + "total");
				$("#ramBar").animate({
					width: (stats.memory[0] * 10) + "px"
				},1000,function(){});

				$("#httpStatus").html(stats.service.lighttpd);
				$("#mysqlStatus").html(stats.service.mysql);

				$("#ip").html(stats.network.ip);				
			});
		}

		function speedtest(){
			$.get("speedtest.php", function(raw) {
				$("#speedtest").html(raw);
			});
		}

		$(function(){
			$("#update").click(function(event)
			{
				event.preventDefault();
				updateAll();
			});
			$("#launchSpeedtest").click(function(event)
			{
				event.preventDefault();
				$("#speedtest").html("Loading...");
				speedtest();
			});
			updateAll();
			setInterval("updateAll()",5000);
		});

		</script>
	</head>
<body>
		<div id="container">
			<header>
				<h1>DNS-320 status panel</h1>
				<h3>what's up?</h3>
			</header>
			<div id="updateBlock">
				<a id="update" href="#">Update manually</a> (updates every 5 seconds)
			</div>
			<div class="block">
				<h3>uptime</h3>
				<p id="uptime"></p>
			</div>

			<div class="block">
				<h3>temperature</h3>
				<p id="temp"></p>
			</div>
			<div class="block">
				<h3>load averages</h3>
				<p>Current status:
				<span id="loadStatus"></span>
				</p>
				
				<p id="loadOne">Last 60 seconds: </p>
				<div class="barContainer">
					<div class="bar" id="loadBarOne" style="background-color: "></div>
				</div>

				<p id="loadTwo">Last 5 minutes: </p>
				<div class="barContainer">
					<div class="bar" id="loadBarTwo" style="background-color: "></div>
				</div>
				<p id="loadThree">Last 15 minutes: </p>
				<div class="barContainer">
					<div class="bar" id="loadBarThree" style="background-color: "></div>
				</div>
			</div>
			<div class="block">
				<h3>processor speed</h3>
				<p><span id="procSpeed"></span> %</p>
				<div class="barContainer">
					<div class="bar" id="cpuBar"></div>
				</div>
			</div>
			<div class="block">
				<h3>disk usage</h3>
				<p id="diskInfo1">%,  used /  total</p>
				<div class="barContainer">
					<div class="bar" id="diskBar1"></div>
				</div>
				<p id="diskInfo2">%,  used /  total</p>
				<div class="barContainer">
					<div class="bar" id="diskBar2"></div>
				</div>
			</div>
			<div class="block">
				<h3>memory</h3>
				<p id="memInfo">%,  used /  total</p>
				<div class="barContainer">
					<div class="bar" id="ramBar"></div>
				</div>
			</div>
			<div class="block">
				<h3>services<h3>
				<p >HTTP server: <span id="httpStatus"></span></p>
				<p>MySQL: <span id="mysqlStatus"></span></p>
			</div>
			<div class="block">
				<h3>network<h3>
				<p >IP: <span id="ip"></span></p>
			</div>
			<div class="block">
				<h3>speedtest<h3>
				<p id="speedtest"><a id="launchSpeedtest" href="#">Launch speedtest</a></p>
			</div>
			<div id="credits">
				<p><a href="https://github.com/cyprieng/status">Source code</a></p>
			</div>
		</div>
	</body>
</html>