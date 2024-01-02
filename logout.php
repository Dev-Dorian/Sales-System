
<?php
   session_start();
   if (session_destroy()) {
       unset($_SESSION['usuario']);
       $_SESSION['admin']=0;
       echo 'Saliendo...';
       header('Refresh: 2; URL = login.php');
   }
?>

<!DOCTYPE html>
<html>
<head>
	
	<title>Facturacion</title>
</head>
<body>

</body>
</html>