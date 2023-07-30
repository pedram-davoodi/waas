<?php

namespace WHMCS\Module\Server\greenplusWaaS;

class GreenPlusProvider
{
    private $apiToken;
    private $baseUrl;

    public function __construct()
    {
        $this->apiToken = 'uT9qhvzh8um8MvXayImnavCLAibiHZXOLwxmwajAf9c=.gAAAAABkvLwM9YDn2pKetro54vnPQ3GvdoW8c_nc5Vwscws19E8vyIwQ_dJx2TzGsfGviOA6-_5StbcRpZbmrmpLNp8YaxioS_5KyK48OFRty03TT3OzIIZoo6MnqEjGTbN4H1YiK5HgOpCwj4KtOBc0KEYaBnX_h9t-L3NavY9hJhO-yBrBj-U=';

        $this->baseUrl = 'https://portal.greenwebplus.com/api/v1';
    }

    public function getSpecs()
    {
        try {
            $apiEndpoint = $this->baseUrl.'/wordpress/specs/';
            $apiKey = $this->apiToken;

            $ch = curl_init();
            $options = array(
                CURLOPT_URL => $apiEndpoint,
                CURLOPT_RETURNTRANSFER => true, // Return the response as a string instead of outputting it directly
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $apiKey, // Set the Authorization header
                ),
            );
            curl_setopt_array($ch, $options);
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'cURL error: ' . curl_error($ch);
            }
            curl_close($ch);
            return json_decode($response)->data;

        }catch (Exception $exception){
            return [];
        }
    }
}