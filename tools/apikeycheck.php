<?php

function CheckAgainstDatabase(?string $key): bool
{
    $username = $_GET["username"] ?? null;
    if ($username !== null) {
        try {
            $dbCon = connect_MySQL_DB();

            //test query
            $sql = "select * from apikey_granted ";
            $sql .= "where apikey = :apikey ";
            $sql .= "and username = :username ";
            $pp_sel = $dbCon->prepare($sql);
            $pp_sel->bindValue(':apikey', $key);
            $pp_sel->bindValue(':username', $username);
            $sel_result = $pp_sel->execute();
            //$debug = pdo_debugStrParams($pp_sel);
            $result = $pp_sel->fetchAll(PDO::FETCH_ASSOC);

            $found = false;
            foreach ($result as $apirow) {
                if ($apirow["apikey"] === $key) {
                    $found = true;
                    break;
                }
            }
            return $found;
            $dbCon = null;
        } catch (PDOException $e) {
            //show exception
            echo $e->getMessage();
        }
    }
    return false;
}
function ApiKeyPass(?string $key, ?string $site): bool
{
    $checkKey = MakePasskeyOfTheDay($site);
    if ($key === $checkKey)
        return true;

    if (CheckAgainstDatabase($key))
        return true;

    return false;
}

function AddUsageLog(?string $logDetail, ?int $bytesSent): bool
{
    $apikey = $_GET["apikey"] ?? null;
    if (($apikey !== null) && (($_GET["username"] ?? null) !== null) && ($bytesSent > 0)) {
        try {
            $sql = "INSERT INTO usage_log(apikey, detail, numbytes, use_date_time) VALUE (:apikey,:detail,:numbytes,NOW())";
            $con = connect_MySQL_DB();
            $result = $con->prepare($sql);
            //must use bindValue / bindParam for the null parameters
            $result->bindValue(':apikey', $apikey);
            $result->bindValue(':detail', $logDetail);
            $result->bindValue(':numbytes', $bytesSent);
            $qresult = $result->execute();
            $con = null; //close the connection
            return $qresult;
        } catch (PDOException $e) {
            return false;
        }
    }
    return false;
}
