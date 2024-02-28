<?php
//Carga las librerias importadas del composer
use App\Models\GeneralFunctions;

require(__DIR__ .'/vendor/autoload.php');

// Carga las variables de entorno desde el archivo .env
GeneralFunctions::loadEnv();

// Configuración de la base de datos
$user_root = 'root';
$password_root = '';

$host = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$database = $_ENV['DB_DATABASE'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];

try {
	// Crea una nueva instancia de PDO
	$pdo = new PDO("mysql:host=$host;port=$port;charset=utf8", $user_root, $password_root);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	// Comando SQL para crear el usuario si no existe
	$createUserSQL = "CREATE USER IF NOT EXISTS '$username'@'$host' IDENTIFIED BY '$password';";
	$createUserSQL .= "GRANT ALL PRIVILEGES ON $database.* TO '$username'@'$host';";

	// Ejecuta el comando SQL
	if ($pdo->exec($createUserSQL)){
		echo "Usuario $username creado o actualizado en la base de datos $database.";
	}

	// Lee el script SQL desde el archivo
	$sqlScript = file_get_contents(__DIR__ . '/database/Script.sql');

	// Ejecuta el script SQL
	$pdo->exec($sqlScript);

	echo "¡Configuración de la base de datos completada exitosamente!";
} catch (PDOException $e) {
	echo "Error al configurar la base de datos: " . $e->getMessage();
}
