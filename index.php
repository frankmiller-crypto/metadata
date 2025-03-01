<?php

// Función para extraer TODOS los metadatos EXIF
function extractAllExifMetadata($filePath) {
    // Verificar si el archivo existe
    if (!file_exists($filePath)) {
        return "El archivo no existe.";
    }

    // Convertir HEIC a JPG si es necesario
    if (strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) == 'heic') {
        $filePath = convertHeicToJpg($filePath);
    }

    // Obtener TODOS los metadatos EXIF
    $exifData = exif_read_data($filePath, 'EXIF', true);

    if (!$exifData) {
        return "No se pudieron extraer los metadatos EXIF.";
    }

    return $exifData;
}

// Función para convertir coordenadas GPS EXIF a decimal
function getGpsCoordinates($gpsData) {
    if (!isset($gpsData['GPSLatitude']) || !isset($gpsData['GPSLongitude'])) {
        return null;
    }

    $lat = convertToDecimal($gpsData['GPSLatitude'], $gpsData['GPSLatitudeRef']);
    $lon = convertToDecimal($gpsData['GPSLongitude'], $gpsData['GPSLongitudeRef']);
    $alt = isset($gpsData['GPSAltitude']) ? convertToNumber($gpsData['GPSAltitude']) : null;

    return ['Latitud' => $lat, 'Longitud' => $lon, 'Altitud' => $alt];
}

// Función para convertir coordenadas EXIF a decimal
function convertToDecimal($coords, $ref) {
    $degrees = count($coords) > 0 ? convertToNumber($coords[0]) : 0;
    $minutes = count($coords) > 1 ? convertToNumber($coords[1]) : 0;
    $seconds = count($coords) > 2 ? convertToNumber($coords[2]) : 0;

    $decimal = $degrees + ($minutes / 60) + ($seconds / 3600);

    if ($ref == 'S' || $ref == 'W') {
        $decimal = -$decimal;
    }

    return $decimal;
}

// Función para convertir fracciones EXIF a números
function convertToNumber($coordPart) {
    $parts = explode('/', $coordPart);
    if (count($parts) <= 0) {
        return 0;
    }
    if (count($parts) == 1) {
        return $parts[0];
    }
    return floatval($parts[0]) / floatval($parts[1]);
}

// Función para mostrar un array de forma legible
function formatArray($array) {
    return implode(', ', $array);
}

// Función para convertir HEIC a JPG usando Imagick
function convertHeicToJpg($filePath) {
    $imagick = new Imagick($filePath);
    $newPath = preg_replace('/\.heic$/i', '.jpg', $filePath);
    $imagick->setImageFormat('jpeg');
    $imagick->writeImage($newPath);
    $imagick->clear();
    $imagick->destroy();

    return $newPath;
}

// Uso del script
$filePath = 'foto2.jpg'; // Cambia esto por la ruta de tu imagen
$metadata = extractAllExifMetadata($filePath);

if (is_array($metadata)) {
    echo "<pre>";
    echo "Metadatos EXIF:\n";
    echo "----------------\n";

    // Mostrar información general
    if (isset($metadata['FILE'])) {
        echo "Información del archivo:\n";
        foreach ($metadata['FILE'] as $key => $value) {
            if (is_array($value)) {
                echo "$key: " . formatArray($value) . "\n";
            } else {
                echo "$key: $value\n";
            }
        }
        echo "----------------\n";
    }

    // Mostrar información de la cámara
    if (isset($metadata['IFD0'])) {
        echo "Información de la cámara:\n";
        foreach ($metadata['IFD0'] as $key => $value) {
            if (is_array($value)) {
                echo "$key: " . formatArray($value) . "\n";
            } else {
                echo "$key: $value\n";
            }
        }
        echo "----------------\n";
    }

    // Mostrar configuración de la cámara
    if (isset($metadata['EXIF'])) {
        echo "Configuración de la cámara:\n";
        foreach ($metadata['EXIF'] as $key => $value) {
            if (is_array($value)) {
                echo "$key: " . formatArray($value) . "\n";
            } else {
                echo "$key: $value\n";
            }
        }
        echo "----------------\n";
    }

    // Mostrar coordenadas GPS
    if (isset($metadata['GPS'])) {
        $gpsCoordinates = getGpsCoordinates($metadata['GPS']);
        echo "Coordenadas GPS:\n";
        echo "Latitud: " . $gpsCoordinates['Latitud'] . "\n";
        echo "Longitud: " . $gpsCoordinates['Longitud'] . "\n";
        if ($gpsCoordinates['Altitud'] !== null) {
            echo "Altitud: " . $gpsCoordinates['Altitud'] . " metros\n";
        }
        echo "----------------\n";
    }
    echo "</pre>";
} else {
    echo $metadata;
} ?>
