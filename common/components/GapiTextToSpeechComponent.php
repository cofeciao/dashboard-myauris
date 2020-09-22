<?php

namespace common\components;

use common\helpers\MyHelper;
use GuzzleHttp\Client;
use yii\helpers\Url;

// Imports the Cloud Client Library
use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\SsmlVoiceGender;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;

class GapiTextToSpeechComponent
{
    /*private $tokenPath = null;
    private $accessToken = null;
    private $uriRefreshAccessToken = "https://developers.google.com/oauthplayground/refreshAccessToken";
    private $uriSynthesize = "https://texttospeech.googleapis.com/v1/text:synthesize";

    public function isTokenExpired($accessToken = null)
    {
        if ($accessToken == null) $accessToken = $this->accessToken;
        if ($accessToken == null) return true;
        return ($accessToken['created'] + ($accessToken['expires_in'] - 30)) < time();
    }

    public function fetchAuthToken($local = false)
    {
        if ($this->accessToken == null) return null;
        $data = [
            'token_uri' => 'https://www.googleapis.com/oauth2/v4/token',
            'refresh_token' => $this->accessToken['refresh_token']
        ];
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->uriRefreshAccessToken,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
            ),
        ));

        if (\Yii::$app->request->getUserIP() == '127.0.0.1') {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($curl, CURLOPT_CAINFO, Url::to('@backend/modules/clinic/token/cacert.pem'));
        }

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if (!$err) {
            $response = json_decode($response);
            var_dump($response);
            if(isset($response->error) && strlen($response->error) > 0) $this->accessToken = null;
            else {
                $newToken = [
                    'access_token' => $response->access_token,
                    'expires_in' => $response->expires_in,
                    'scope' => $this->uriRefreshAccessToken,
                    'token_type' => 'Bearer',
                    'created' => time(),
                    'refresh_token' => $this->accessToken['refresh_token']
                ];
                if (file_put_contents($this->tokenPath, json_encode($newToken))) $this->accessToken = $newToken;
            }
        } else $this->accessToken = null;
    }

    public function setAccessToken($tokenPath)
    {
        $this->tokenPath = $tokenPath;
        if (file_exists($this->tokenPath)) {
            $this->accessToken = json_decode(file_get_contents($this->tokenPath), true);
            if ($this->isTokenExpired()) {
                $this->fetchAuthToken();
            }
        }
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function renderTextToSpeech($text)
    {
        if ($this->accessToken == null || $text == '') return null;
        $data = [
            'input' => [
                'text' => $text
            ],
            'voice' => [
                'languageCode' => 'vi-vn',
                'name' => 'vi-VN-Standard-A',
                'ssmlGender' => 'FEMALE'
            ],
            'audioConfig' => [
                'audioEncoding' => 'MP3'
            ]
        ];
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->uriSynthesize,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "authorization: " . $this->accessToken['token_type'] . ' ' . $this->accessToken['access_token'],
                "cache-control: no-cache",
                "charset: utf-8",
                "content-type: application/json",
            ),
        ));

        if (\Yii::$app->request->getUserIP() == '127.0.0.1') {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($curl, CURLOPT_CAINFO, Url::to('@backend/modules/clinic/token/cacert.pem'));
        }

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) return null;
        $response = json_decode($response);
        var_dump($response);die;

        $audioContent = base64_decode($response->audioContent);
        return $audioContent;
    }

    public function renderTextToSpeechFile($text, $alias)
    {
        $title = md5(MyHelper::createAlias($text));
        if (!file_exists(Url::to('@backend/web') . '/' . $alias . '/' . $title . '.mp3')) {
            $audioContent = $this->renderTextToSpeech($text);
            if ($audioContent == null || !file_put_contents(Url::to('@backend/web') . '/' . $alias . '/' . $title . '.mp3', $audioContent)) return null;
        }
        return $title . '.mp3';
    }*/

    public function test($text, $alias)
    {
        $title = md5(MyHelper::createAlias($text));
        if (!file_exists(Url::to('@backend/web') . '/' . $alias . '/' . $title . '.mp3')) {
            // instantiates a client
            putenv('GOOGLE_APPLICATION_CREDENTIALS=../../backend/modules/clinic/token/text-to-speech/credentials.json');

            $client = new TextToSpeechClient();

            /*if (\Yii::$app->request->getUserIP() == '127.0.0.1'){
                $http = new Client([
                    'verify' => Url::to('@backend/modules/clinic/token/cacert.pem')
                ]);
                $client->setHttpClient($http);
            }*/

//            $client->getHttpClient()->setDefaultOption('verify', Url::to('@backend/modules').'/clinic/token/cacert.pem');

            // sets text to be synthesised
            $synthesisInputText = (new SynthesisInput())
                ->setText('Hello, world!');

            // build the voice request, select the language code ("en-US") and the ssml
            // voice gender
            $voice = (new VoiceSelectionParams())
                ->setLanguageCode('en-US')
                ->setSsmlGender(SsmlVoiceGender::FEMALE);

            // Effects profile
            $effectsProfileId = "telephony-class-application";

            // select the type of audio file you want returned
            $audioConfig = (new AudioConfig())
                ->setAudioEncoding(AudioEncoding::MP3)
                ->setEffectsProfileId(array($effectsProfileId));

            // perform text-to-speech request on the text input with selected voice
            // parameters and audio file type
            $response = $client->synthesizeSpeech($synthesisInputText, $voice, $audioConfig);
            $audioContent = $response->getAudioContent();

            /*// the response's audioContent is binary
            file_put_contents('output.mp3', $audioContent);
            echo 'Audio content written to "output.mp3"' . PHP_EOL;*/
            if ($audioContent == null || !file_put_contents(Url::to('@backend/web') . '/' . $alias . '/' . $title . '.mp3', $audioContent)) {
                return null;
            }
        }
        return $title . '.mp3';
    }
}
