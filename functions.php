<?php

include_once "config.php";

function voicestormApiRequest($requestType, $url, $requestData = null)
{
    $data = '';
    $f = fopen($GLOBALS['voicestormLogFile'], 'w');
    $url = $GLOBALS['voicestormBaseUrl'] . $url;
    $ch = curl_init();
    if (($requestData !== null) && array_key_exists('Basic', $requestData))
    {
        $header = array('Authorization: Basic ' . $requestData['Basic']);
        $data = array("grant_type" => "client_credentials");
    }
    else
    {
        $token = voicestormBearerToken();
        if (isset($token["code"]) && $token["code"] == "error")
        {
            return $token;
        }
        else
        {
            $header = array('Authorization: Bearer ' . $token);
            if (($requestData !== null) && ($requestType == "PUT" || $requestType == "POST"))
            {
                $data = json_encode($requestData, true);
                array_push($header, 'Content-Type: application/json');
            }
            if (($requestData !== null) && $requestType == "GET")
            {
                $data = http_build_query($requestData);
                $url = $url . "?" . $data;
            }
        }
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    if ($requestType != "POST")
    {
        if ($requestType == "PUT" || $requestType == "DELETE")
        {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requestType);
        }
    }
    if ($requestType == "PUT" || $requestType == "POST")
    {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_STDERR, $f);
    $result = curl_exec($ch);
    if (curl_errno($ch))
    {
        die('The cURL experienced technical difficulties');
    }
    else
    {
        $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($resultStatus != 200)
        {
            die('Request failed:'.$result);
        }
    }
    curl_close($ch);
    fclose($f);
    return json_decode($result, true);
}

function voicestormBearerToken()
{
    $encAccess = urlencode($GLOBALS['voicestormAccessToken']);
    $encSecret = urlencode($GLOBALS['voicestormTokenSecret']);
    $cred = $encAccess . ":" . $encSecret;
    $baseCred = base64_encode($cred);
    $val = voicestormApiRequest("POST", "/oauth2/token", array("Basic" => $baseCred));
    if (isset($val["access_token"]))
    {
        return $val['access_token'];
    }
    else
    {
        return array("code" => "error", "message" => "Cannot get the Bearer Token");
    }
}
?>