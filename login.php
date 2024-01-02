<html>
	<?php include 'session.php'?>
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
	<link rel="stylesheet" href="milligram.css"/>
	<title>Facturacion</title>
	<body>
		<nav class = "navigation">
			<section class = "container">
			
				<ul class="navigation-list float-right">
            		<li class="navigation-item">
               			
            		</li>
            		<li class="navigation-item">
               			
            		</li>
        		</ul>
			</section>
		</nav>
		<div class="blank"></div>
		<?php
			include 'core/conn.php';
			include 'PasswordHash.php';
			$error='';

			// Login
			if (isset($_POST['login']) && !empty($_POST['USUARIO']) && !empty($_POST['PASSWORD'])) {
				$statement = 'SELECT * FROM CUENTA WHERE usuario = :usuario_b';
				$stid = oci_parse($conn, $statement);
				
				$usuario = $_POST['USUARIO'];
				$password = $_POST['PASSWORD'];

				// Password hashing framework taken from http://www.openwall.com/phpass/
				// due to jasmine server php version being too low to use built-in password_hash()
				$phash = new PasswordHash(8,false);
				$hashedPass = '*';

				oci_bind_by_name($stid, ':usuario_b', $usuario);
				oci_execute($stid);
				if (!$row = oci_fetch_row($stid)) {
					$error = "Usuario no existe";
				} else if ($row[3]==1) {
					$error = "No puede ingresar!";
				} else {
					// Check passhash against stored hash
					$hashedPass = $row[1];
					$check = $phash->CheckPassword($password, $hashedPass);
					if (!$check) {
						$error = "Invalid password.";
					} else {
						$_SESSION['usuario'] = $usuario;
						$_SESSION['admin'] = $row[2];

						$row[2];
						oci_close($conn);
						header('Refresh: 1; URL = index.html');
					}
				}
			}

			// Register
			else if (isset($_POST['register']) && !empty($_POST['USUARIO']) && !empty($_POST['PASSWORD'])) {
				$usuario = $_POST['USUARIO'];
				$password = $_POST['PASSWORD'];
				
				// Checks to see if usuario exists
				$statement = 'SELECT usuario from cuenta where usuario = :usuario_b';
				$stid = oci_parse($conn, $statement);
				oci_bind_by_name($stid, ':usuario_b', $usuario);
				oci_execute($stid);

				if ($row = oci_fetch_row($stid)) {
					$error = "Usuario ya existe!";
				} else {
					// Registers new account if usuario is fresh
					$statement = 'INSERT INTO CUENTA VALUES (:usuario_b, :passwordhash_b, \'0\', \'0\')';
					$stid = oci_parse($conn, $statement);
	
					// Password hashing framework taken from http://www.openwall.com/phpass/
					// due to jasmine server php version being too low to use the built-in password_hash()
					$phash = new PasswordHash(8,false);
					$hasehedPassword = $phash->HashPassword($password);
					if (strlen($hasehedPassword) < 20) {
						echo "La contraseña no se ha corregido correctamente";
					} else {
						oci_bind_by_name($stid, ':usuario_b', $usuario);
						oci_bind_by_name($stid, ':passwordhash_b', $hasehedPassword);
						if (oci_execute($stid)) {
							oci_close($stid);
							header("Location: index.html");
						} else {
							$e = oci_error($stid); 
							echo $e['message']; 
						}
					}
				}
			}
		?>
		
		<section class="container" styles="width:30%">
            <form action="login.php" method="post">
				<label>Usuario</label>
				<input name="USUARIO" type="text" required pattern="^[a-zA-Z0-9]+$">
				<span class="validity"></span>
				<label>Contraseña</label>
				<input name="PASSWORD" type="password" required pattern="^([a-zA-Z0-9@#]{3,15})$">
				<span class="validity"></span>
				<input class="button" value="Ingresar" name ="login" type="submit">
				<input class="button" value="Registrarse" name ="register" type="submit">
				<p><?php echo $error;?></p>
			</form>
		</section>

	</body>
</html>