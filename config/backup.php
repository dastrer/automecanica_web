
<?php
// Incluimos la conexión a la base de datos y las constantes
require_once "../config/global.php";

// Configuración de la base de datos usando las constantes de global.php
$host = DB_HOST;
$user = DB_USERNAME;
$password = DB_PASSWORD;
$dbname = DB_NAME;

// Nombre del archivo de respaldo
date_default_timezone_set('America/La_Paz');
$date = date("Y-m-d_H-i-s");
$backup_file = "{$dbname}_backup_{$date}.sql";

// Ruta de mysqldump (ajústala según tu configuración en XAMPP)
$mysqldump = 'C:\xampp\mysql\bin\mysqldump.exe';

// Comando para realizar el respaldo
$command = "$mysqldump --user=$user --password=$password --host=$host $dbname";

// Ejecutar el comando y capturar el resultado
$output = null;
exec("$command", $output, $result);

// Verificar si la copia de seguridad se realizó correctamente
if ($result === 0) {
    // Crear el archivo temporal para la descarga
    header('Content-Type: application/sql');
    header('Content-Disposition: attachment; filename="' . $backup_file . '"');
    header('Content-Length: ' . strlen(implode("\n", $output)));
    echo implode("\n", $output); // Enviar el contenido del respaldo para descargar
    exit;
} else {
    echo "Error al crear la copia de seguridad";
}
?>
