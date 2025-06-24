<?php
// /var/www/html/index.php
if (isset($_GET['url'])) {
    $url = escapeshellarg($_GET['url']); // Escapar la URL por seguridad básica
    $output_path = "/var/www/html/output.pdf"; // Ruta absoluta donde se guardará el PDF

    $wkhtmltopdf_bin = "/usr/local/bin/wkhtmltopdf"; 

    $command = "$wkhtmltopdf_bin --enable-javascript $url $output_path 2>&1";

    $output = []; // Array para almacenar la salida del comando
    $return_var = -1; // Variable para almacenar el código de retorno del comando

    // Ejecuta el comando
    exec($command, $output, $return_var);

    // --- Depuración ---
    echo "<h2>Diagnóstico:</h2>";
    echo "<strong>Comando ejecutado:</strong> <pre>" . htmlspecialchars($command) . "</pre>";
    echo "<strong>Código de retorno de exec():</strong> " . $return_var . "<br>";
    echo "<strong>Salida del comando (stderr/stdout de wkhtmltopdf):</strong><pre>";
    echo htmlspecialchars(implode("\n", $output));
    echo "</pre>";
    echo "<strong>Ruta del PDF esperada:</strong> " . $output_path . "<br>";
    echo "<strong>¿Existe output.pdf en esa ruta? </strong> " . (file_exists($output_path) ? "Sí" : "No") . "<br>";
    // --- Fin Parte de Depuración ---

    if ($return_var === 0 && file_exists($output_path)) {
        echo "<h2>PDF Generado:</h2>";
        echo "<a href='/output.pdf' target='_blank'>Ver PDF</a>"; // Usa /output.pdf para la URL web
    } else {
        echo "<h2>Error en la Generación del PDF.</h2>";
        if (!file_exists($output_path)) {
            echo "<p>El archivo <code>output.pdf</code> no se encontró en <code>" . htmlspecialchars($output_path) . "</code> después de la ejecución.</p>";
        }
    }
} else {
    echo "<form method='GET'><input type='text' name='url' placeholder='http://localhost:8000/malicious.html'><input type='submit'></form>";
}
?>
