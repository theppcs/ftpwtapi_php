<?php
//copied some part from jaibot mytools.php
require_once __DIR__ . '/../config/globals.php';

function IsNullOrEmptyString($str)
{
    return (!isset($str) || trim($str) === '');
}

function pdo_debugStrParams($stmt)
{
    ob_start();
    $stmt->debugDumpParams();
    $r = ob_get_contents();
    ob_end_clean();
    return $r;
}
function DateOnly($dateTime)
{
    if (!isset($dateTime))
        $dateTime = NowWTZ();
    $rdateTime = new \DateTime(
        $dateTime->format("Y/m/d"),
        new \DateTimeZone(Globals::$AppTimeZone)
    );
    return $rdateTime;
}
function MyStrToDateStr(?string $str): ?string
//accept case yyyy/m/d or bbbb/m/d or d/m/yyyy or d/m/bbbb 
{
    if (IsNullOrEmptyString($str))
        return null;
    try {
        $split = preg_split("/[\/-]+/", $str);
        if (isset($split[0]) && isset($split[1]) && isset($split[2])) {
            $d = "";
            $m = intval($split[1]);
            $y = "";

            $x = intval($split[0]);
            if ($x > 2200) { //pee por sor
                $y = $x - 543;
                $d = $split[2];
            } elseif ($x > 35) {
                $y = $x;
                $d = $split[2];
            } else {
                $d = $split[0];

                $y = intval($split[2]);
                //กรณีส่งปีมา 2 หลัก
                if ($y < 100) {
                    if ($y > 50)  //่น่าจะเป็นปี พ.ศ.
                        $y += 2500;
                    else
                        $y += 2000;
                }

                if ($y > 2200)
                    $y = $y - 543;
            }
            return (new DateTime("$y/$m/$d"))->format("Y/m/d");
        }
    } catch (Exception $e) {
    }
    return "";
}
function MyStrToDateTime(?string $str): ?DateTime
{
    if (IsNullOrEmptyString($str))
        return null;
    $dateTime = new \DateTime(
        $str,
        new \DateTimeZone(Globals::$AppTimeZone)
    );
    return $dateTime;
}
//Now with Timezone
function NowWTZ($dateOnly = false)
{
    $dateTime = new \DateTime(
        'now',
        new \DateTimeZone(Globals::$AppTimeZone)
    );
    if ($dateOnly)
        return DateOnly($dateTime);
    return $dateTime;
}
function MakePasskeyOfTheDay(?string $userId)
{
    $currentDay = NowWTZ()->format("Y/m/d");
    return hash('tiger192,3', $currentDay . $userId);
}
