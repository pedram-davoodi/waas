<?php

/**
 * Retrieves the value of an environment variable from the .env file.
 *
 * @param string $item The name of the environment variable to retrieve the value for.
 *
 * @return mixed|null The value of the specified environment variable if found, or null if not found or an error occurs.
 *
 * @throws Exception If the .env file does not exist or if there is an error while parsing it.
 */
function getGPEnv($item)
{
    try {
        $filePath = __DIR__ . '/../.env';
        $envData = [];
        if (!file_exists($filePath)) {
            throw new Exception("The .env file '$filePath' does not exist.");
        }
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || strpos($line, '#') === 0) {
                continue; // Skip empty lines and comments starting with '#'
            }
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            // Remove quotes if the value is quoted
            if (($value[0] === '"' && $value[strlen($value) - 1] === '"') || ($value[0] === "'" && $value[strlen($value) - 1] === "'")) {
                $value = substr($value, 1, -1);
            }
            $envData[$key] = $value;
        }
        return  $envData[$item];
    } catch (Exception $exception) {
        return null;
    }
}