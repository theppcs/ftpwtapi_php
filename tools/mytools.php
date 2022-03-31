<?php
//copied some part from jaibot mytools.php
require_once __DIR__ . '/../config/globals.php';

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
