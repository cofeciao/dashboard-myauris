<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 22-03-2019
 * Time: 10:57 AM
 */

namespace backend\components;

use Yii;
use yii\helpers\Url;
use GuzzleHttp\Client;
use backend\components\Email;

class GapiComponent
{
    public static function getClient()
    {
        $client = new \Google_Client();
        $urlCredential = Url::to('@backend/modules/clinic/token/credentials.json');
        $tokenPath = Url::to('@backend/modules/clinic/token/token.json');
        if (/*\Yii::$app->request->getUserIP() == '127.0.0.1' && */ YII2_ENV_DEV == true || (defined('EXPORT_DATABASE') && EXPORT_DATABASE === true)) {
            $http = new Client([
                'verify' => Url::to('@backend/modules/clinic/token/cacert.pem')
            ]);
            $client->setHttpClient($http);
            $urlCredential = Url::to('@backend/modules/clinic/token/credentials-bachbidlg6.json');
            $tokenPath = Url::to('@backend/modules/clinic/token/token-bachbidlg6.json');
        }
        $client->setApplicationName('Google Drive API PHP Quickstart');
        $client->setScopes(\Google_Service_Drive::DRIVE_METADATA_READONLY);
        $client->setAuthConfig($urlCredential);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }

    public static function getFile($service, $fileId = '')
    {
        try {
            if ($fileId == '') {
                return null;
            }
            $file = $service->files->get($fileId, [
                'fields' => '*'
            ]);
            return [
                'id' => $fileId,
                'name' => $file->getName(),
                'mimeType' => $file->getMimeType(),
                'webContentLink' => $file->getWebContentLink(),
                'iconLink' => $file->getIconLink(),
                'webViewLink' => $file->getWebViewLink(),
                'thumbnailLink' => $file->getThumbnailLink(),
                'fileExtension' => $file->getFileExtension(),
                'size' => $file->getSize(),
                'file' => $file->permissions
            ];
        } catch (\Exception $ex) {
            return null;
        }
    }

    public static function getListFile($service, $parentId, $pageSize = 100, $orderBy = '', $pageToken = '')
    {
        try {
            $params = [
                'q' => "mimeType!='application/vnd.google-apps.folder'",
                'fields' => '*'
            ];
            if (is_numeric($pageSize) && $pageSize > 0) {
                $params['pageSize'] = $pageSize;
            }
            if ($parentId != '') {
                $params['q'] .= " and '{$parentId}' in parents";
            }
            if ($orderBy != '') {
                $params['orderBy'] = $orderBy;
            }
            if ($pageToken != '') {
                $params['pageToken'] = $pageToken;
            }
            $results = $service->files->listFiles($params);
            $data = [];
            if ($results != null) {
                foreach ($results->getFiles() as $file) {
                    $filePermissions = $file->getPermissions();
                    if (is_array($filePermissions)) {
                        $hasPermissionReader = false;
                        foreach ($filePermissions as $permission) {
                            if ($permission->role == 'reader') {
                                $hasPermissionReader = true;
                                break;
                            }
                        }
                        if ($hasPermissionReader == false) {
                            self::setPermissionReader($service, $file->getId());
                        }
                    }
                    $data[] = [
                        'type' => 'gFile',
                        'id' => $file->getId(),
                        'name' => $file->getName(),
                        'mimeType' => $file->getMimeType(),
                        'webContentLink' => $file->getWebContentLink(),
                        'iconLink' => $file->getIconLink(),
                        'webViewLink' => $file->getWebViewLink(),
                        'thumbnailLink' => $file->getThumbnailLink(),
                        'fileExtension' => $file->getFileExtension(),
                        'size' => $file->getSize(),
                    ];
                }
            }
            return $data;
        } catch (\Exception $ex) {
            return null;
        }
    }

    public static function downloadFile($service, $fileId, $url)
    {
        $response = $service->files->get($fileId, array(
            'alt' => 'media'
        ));
        $content = $response->getBody()->getContents();
        file_put_contents($url, $content);
        return $url;
    }

    public static function checkFolderExist($service, $folderName, $parentId)
    {
        if ($folderName == '') {
            return null;
        }
        try {
            $params = [
                'q' => "mimeType='application/vnd.google-apps.folder' and name='{$folderName}'"
            ];
            if ($parentId != '') {
                $params['q'] .= " and '{$parentId}' in parents";
            }
            $results = $service->files->listFiles($params);
            $data = $results->getFiles();
            if (count($data) > 0) {
                return $data[0]->id;
            }
        } catch (\Exception $ex) {
            return null;
        }
    }

    public static function createFolder($service, $folderName = '', $parentId = '')
    {
        if ($folderName == '') {
            return null;
        }
        try {
            $params = [
                'name' => $folderName,
                'mimeType' => 'application/vnd.google-apps.folder',
            ];
            if ($parentId != '') {
                $params['parents'] = [$parentId];
            }
            $fileMetadata = new \Google_Service_Drive_DriveFile($params);
            $file = $service->files->create($fileMetadata, ['fields' => 'id']);
            $idFolder = $file->id;
            return $idFolder;
        } catch (\Exception $ex) {
            return null;
        }
    }

    public static function createOrGetFolderByname($service, $folderName = '', $parentId = '')
    {
        $checkFolder = self::checkFolderExist($service, $folderName, $parentId);
        if ($checkFolder != null) {
            return $checkFolder;
        }
        return self::createFolder($service, $folderName, $parentId);
    }

    public static function initFolderMyAuris($service)
    {
        return self::createOrGetFolderByname($service, 'MyAuris');
    }

    public static function initFolderForCustomer($service, $customerName = '')
    {
        $folderMyAuris = self::initFolderMyAuris($service);
        if ($folderMyAuris == null) {
            return null;
        }
        return self::createOrGetFolderByname($service, $customerName, $folderMyAuris);
    }

    public static function initFolderForCustomerByDate($service, $customerName = '', $date = '')
    {
        $folderCustomer = self::initFolderForCustomer($service, $customerName);
        if ($folderCustomer == null) {
            return null;
        }
        return self::createOrGetFolderByname($service, $date, $folderCustomer);
    }

    public static function initSubFolderForCustomerByDate($service, $customerName = '', $date = '', $subFolder = '')
    {
        $folderDate = self::initFolderForCustomerByDate($service, $customerName, $date);
        if ($folderDate == null) {
            return null;
        }
        return self::createOrGetFolderByname($service, $subFolder, $folderDate);
    }

    public static function getImage($service, $fileId)
    {
        try {
            $file = $service->files->get($fileId, [
                'fields' => 'thumbnailLink,webContentLink'
            ]);
            return [
                $file->getThumbnailLink(),
                $file->getWebContentLink()
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function uploadImageToDrive($service, $file, $alias, $folderId, $is_image = true, $file_info = null, $debug = false)
    {
        try {
            $fileMetadata = new \Google_Service_Drive_DriveFile([
                'name' => $file,
                'parents' => array($folderId)
            ]);
            $content = file_get_contents(\Yii::getAlias($alias) . '/' . $file);
            if ($is_image === true) {
                $file_info = [
                    'data' => $content,
                    'mimeType' => 'image/jpeg',
                    'uploadType' => 'multipart',
                    'fields' => 'id',
                ];
            }
            $file = $service->files->create($fileMetadata, $file_info);
            $id = $file->id;
            if (!$id) {
                return null;
            }
            self::setPermissionReader($service, $id);
            return $id;
        } catch (\Exception $ex) {
            if($debug == true) return $ex;
            return null;
        }
    }

    public static function setPermissionReader($service, $id)
    {
        $client = new Client();
        $client->request('POST', 'https://www.googleapis.com/drive/v3/files/' . $id . '/permissions', [
            'headers' => [
                'Content-length' => '0',
                'cache-control' => 'no-cache',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $service->getClient()->getAccessToken()['access_token']
            ],
            'body' => json_encode([
                "role" => "reader",
                "type" => "anyone",
            ])
        ]);
        return true;
    }

    public static function getService()
    {
        $client = self::getClient();
        return new \Google_Service_Drive($client);
    }

    public static function deleteImageToDrive($service, $google_id)
    {
        return $service->files->delete($google_id);
    }
}
