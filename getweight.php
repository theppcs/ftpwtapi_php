<?php

// USAGE (POST):
//   getweight.php
//   palletno=<palletno>
//   weightmachine=<weightmachine>

main();

function main()
{
    // echo 'hello world';
    // phpinfo();

    $palletNo = $_POST('palletno');
    $palletNo = strtoupper($palletNo);
    $weightMachineNo = $_POST('weightmachine');
    $key = $_POST["apikey"];
}
