<?php

function loadConfig($filePath)
{
    if (!file_exists($filePath)) {
        throw new Exception("El archivo de configuración no se encuentra.");
    }

    $json = file_get_contents($filePath);
    $config = json_decode($json, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Error al decodificar el archivo JSON: " . json_last_error_msg());
    }

    return $config;
}
