<?php

namespace WHMCS\Module\Server\greenplusWaaS\Providers;

use Exception;
use Throwable;

/**
 * Class GreenPlusProvider
 *
 * This class provides methods to interact with the GreenPlus WaaS API for managing WordPress installations.
 *
 * @package WHMCS\Module\Server\greenplusWaaS
 */
class GreenPlusProvider
{
    /**
     * @var string The API token used for authentication with the GreenPlus API.
     */
    private $apiToken;

    /**
     * @var string The base URL for the GreenPlus API.
     */
    private $baseUrl;

    /**
     * GreenPlusProvider constructor.
     *
     * Initializes the GreenPlusProvider object with the API token and base URL retrieved from GPEnv.
     * @throws Exception
     */
    public function __construct()
    {
        require_once __DIR__ . '/../helper.php';
        $this->apiToken = getGPEnv('GreenPlusToken');
        $this->baseUrl = getGPEnv('GreenPlusURL');
    }

    /**
     * Retrieves WordPress specifications from the GreenPlus API.
     *
     * @return array An associative array containing the API response:
     *               - 'success' (bool) Whether the request was successful.
     *               - 'data' (mixed) The decoded data from the API response, containing WordPress specifications.
     *               If the request was not successful, the response may contain an error message.
     * @throws Throwable
     */
    public function getSpecs()
    {
        try {
            $apiEndpoint = $this->baseUrl . '/wordpress/specs/';
            $apiKey = $this->apiToken;

            $ch = curl_init();
            $options = array(
                CURLOPT_URL => $apiEndpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $apiKey,
                ),
            );
            curl_setopt_array($ch, $options);
            $response = curl_exec($ch);
            throw_if(empty($response) , new Exception('The provider didn\'t send any response'));

            if (curl_errno($ch)) {
                echo 'cURL error: ' . curl_error($ch);
            }
            curl_close($ch);
            return [
                'success' => true,
                'data' => json_decode($response)->data
            ];
        } catch (Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * Creates a new WordPress installation using the GreenPlus API.
     *
     * @param string $name The name of the WordPress installation.
     * @param string $url The URL of the WordPress installation.
     * @param string $php_version The PHP version for the WordPress installation.
     * @param string $webserver The web server for the WordPress installation.
     * @param string $language The language for the WordPress installation.
     * @param string $wp_user The WordPress admin username.
     * @param string $wp_password The WordPress admin password.
     * @param string $wp_email The email address of the WordPress admin.
     * @param string $plan The plan for the WordPress installation.
     * @param string $platform The platform for the WordPress installation.
     *
     * @return array An associative array containing the API response:
     *               - 'success' (bool) Whether the request was successful.
     *               - 'data' (mixed) The decoded data from the API response, containing information about the created WordPress installation.
     *               If the request was not successful, the response may contain an error message.
     * @throws Throwable
     */
    public function create($name, $url, $php_version, $webserver, $language, $wp_user, $wp_password, $wp_email, $plan, $platform)
    {
        try {
            $apiEndpoint = $this->baseUrl . '/wordpress/';

            $data = array(
                'name' => $name,
                'url' => $url,
                'php_version' => $php_version,
                'webserver' => $webserver,
                'language' => $language,
                'wp_user' => $wp_user,
                'wp_password' => $wp_password,
                'wp_email' => $wp_email,
                'plan' => $plan,
                'platform' => $platform,
            );
            $token = $this->apiToken;

            $ch = curl_init();
            $options = array(
                CURLOPT_URL => $apiEndpoint,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $token,
                ),
            );

            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt_array($ch, $options);
            $response = json_decode(curl_exec($ch) , true);


            throw_if(empty($response) , new Exception('The provider didn\'t send any response'));
            throw_if(!$response['status'] ,
                new Exception(
                    $response['message']
                    . " : "
                    . collect($response['errors'])->pluck('detail')->implode(' , ') )
            );

            if (curl_errno($ch)) {
                throw new Exception(curl_error($ch));
            }
            return [
                'success' => true,
                'data' => $response['data']
            ];

        } catch (Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * Deletes a WordPress installation using the GreenPlus API.
     *
     * @param string $uuid The UUID of the WordPress installation to be deleted.
     *
     * @return array An associative array containing the API response:
     *               - 'success' (bool) Whether the request was successful.
     *               - 'data' (mixed) The decoded data from the API response, containing information about the deleted WordPress installation.
     *               If the request was not successful, the response may contain an error message.
     * @throws Throwable
     */
    public function delete($uuid)
    {
        try {
            $apiEndpoint = $this->baseUrl . '/wordpress/'.$uuid;

            $token = $this->apiToken;

            $ch = curl_init();
            $options = array(
                CURLOPT_URL => $apiEndpoint,
                CURLOPT_CUSTOMREQUEST => "DELETE",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $token,
                ),
            );
            curl_setopt_array($ch, $options);

            $response = json_decode(curl_exec($ch) , true);


            if (curl_errno($ch)) {
                throw new Exception(curl_error($ch));
            }
            throw_if(empty($response) , new Exception('The provider didn\'t send any response'));
            throw_if(!$response['status'] , new Exception($response['message']));


            return [
                'success' => true,
                'data' => $response['data']
            ];

        } catch (Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }
}
