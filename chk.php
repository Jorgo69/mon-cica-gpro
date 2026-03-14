<?php
echo "<h1>Diagnostic LWS</h1>";
echo "<strong>PHP Version:</strong> " . phpversion() . "<br/>";
echo "<strong>Server Software:</strong> " . $_SERVER['SERVER_SOFTWARE'] . "<br/>";
echo "<h2>PHP Info Complete:</h2>";
phpinfo();
