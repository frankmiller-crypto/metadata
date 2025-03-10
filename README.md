﻿# Metadata Extraction Script

Este proyecto permite extraer todos los metadatos EXIF de una imagen, incluyendo información de archivo, cámara, configuración de cámara y coordenadas GPS.

## Requisitos

- Servidor local XAMPP instalado
- Versión de PHP: **8.2.12**
- Librería **Imagick** instalada para la conversión de imágenes HEIC a JPG

## Instalación

1. Copia los archivos del proyecto en la siguiente ruta:
   ```
   C:\xampp\htdocs\metadata
   ```

2. Asegúrate de que XAMPP esté instalado y ejecutando el servidor Apache.

3. Verifica que la versión de PHP sea **8.2.12**.
   Para hacerlo, ingresa en tu navegador:
   ```
   http://localhost/dashboard/phpinfo.php
   ```

4. Asegúrate de que la extensión **Imagick** esté habilitada.
   Para habilitarla, abre el archivo **php.ini** y descomenta la línea:
   ```
   extension=imagick
   ```
   Reinicia Apache desde el panel de control de XAMPP.

## Uso

1. Coloca la imagen que deseas analizar dentro de la carpeta **metadata**.

2. Abre tu navegador y accede a la siguiente URL:
   ```
   http://localhost/metadata
   ```

3. El script procesará la imagen especificada en la variable `$filePath` y mostrará todos los metadatos disponibles.

## Notas

- Compatible con formatos **JPG** y **HEIC**.
- Si la imagen es HEIC, se convertirá automáticamente a JPG antes de extraer los metadatos.

## Autor
Desarrollado por Cazarez Industries.

## Licencia
Este proyecto se distribuye bajo la licencia MIT.

