<?php

set_exception_handler(function($Exception) {
	header("Content-Type: text/html; charset=UTF-8");
	
	print '
		<html>
			<head>
				<title>Nem kezelt kivétel</title>
				<style type="text/css">
					body {
						font-family: sans-serif;
						font-size: 10pt;
					}
					
					h3 {
						margin-bottom: 5px;
					}
				</style>
			</head>
			
			<body>
				<h1>'.get_class($Exception).'</h1>
				
				<p>
					<h3>Üzenet:</h3>
					'.$Exception->getMessage().'
				</p>
				
				<p>
					<h3>Kiváltó utasítás helye:</h3>
					'.$Exception->getFile().'<br>
					'.$Exception->getLine().'. sor
				</p>
				
				<p>
					<h3>Hívási lánc:</h3>
					<pre>'.$Exception->getTraceAsString().'</pre>
				</p>
			</body>
		</html>
	';
});