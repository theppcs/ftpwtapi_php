<?php

// USAGE (POST):
//   getweight.php
//   palletno=<palletno>
//   weightmachine=<weightmachine>

require_once __DIR__ . '/config/mysecret.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/connectdb.php';
require_once __DIR__ . '/tools/mytools.php';
require_once __DIR__ . '/tools/apikeycheck.php';
require_once __DIR__ . '/class/ftpweight.php';

main();

function getWeightData(?string $site, ?string $weightmachine, ?string $palletno): array
{
    $resultRow = array();
    //$resultRow['error'] = '';

    if (IsNullOrEmptyString($site) || IsNullOrEmptyString($palletno)) { //weightmachine is optional
        $resultRow['error'] = 'Not complete data';
        return $resultRow;
    }

    try {
        $WtClient = new FtpWeight($site, $weightmachine);
        $data = $WtClient->GetWeight($palletno);
        if ($data) {
            $resultRow['palletno'] = $data['palletno'];
            $resultRow['weight'] = round(floatval($data['weight']), 0);
            $resultRow['datetimeStr'] = MyStrToDateStr($data['dateDMY']) . ' ' . $data['timeHMS'];
            // $resultRow['datetime'] = MyStrToDateTime($resultRow['datetimeStr']);
            $resultRow['rawdata'] = $data;
            return $resultRow;
        } else
            $resultRow['error'] = 'No data';
    } catch (Exception $e) {
        $resultRow['error'] = $e->getMessage();
        return $resultRow;
    }
    return $resultRow;
}

function main()
{
    // echo 'hello world';
    // phpinfo();

    header('Content-Type: application/json');
    try {
        $palletNo = $_POST['palletno'] ?? "";
        $palletNo = mb_strtoupper($palletNo);
        $weightMachineNo = $_POST['weightmachine'] ?? "";
        $key = $_POST["apikey"];
        $site = $_POST["site"] ?? $_GET["site"] ?? "";

        if (ApiKeyPass($key, $site)) {
            $site = mb_strtoupper($site);

            $weightData = getWeightData($site, $weightMachineNo, $palletNo);
            $resJson = json_encode($weightData);
            $weightResult = $weightData['weight'] ?? 0.00;
            if (($resJson['error'] ?? null) === null)
                $logType = "Get: $site, $weightMachineNo, $palletNo => wt = $weightResult";
            else
                $logType = "Get: $site, $weightMachineNo, $palletNo => error = " . $resJson['error'];
            AddUsageLog($logType, mb_strlen($resJson, '8bit'));
        } else
            $resJson = json_encode(array("error" => "Invalid api key!!!"));

        echo $resJson;
    } catch (Exception $e) {
        echo json_encode(array("error" => $e->getMessage()));
    }
}
