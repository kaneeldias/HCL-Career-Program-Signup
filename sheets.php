<?php

require_once __DIR__ . '/vendor/autoload.php';

$client = new Google_Client();
$client->setApplicationName('Google Sheets and PHP');
$client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
$client->setAccessType('offline');
$client->setAuthConfig(__DIR__ . '/credentials.json');
$sheet_service = new Google_Service_Sheets($client);

$spreadsheetIds = [
    'CC' => "1fYji-J4tWRr1sZJ_M-Hf-Euals4QzBYBxoohfvrys4s",
    'CN' => "1dxR1g7XZQZJ36s3loCLARDTLRqQMbyCdXWI8bHqDrBE",
    'CS' => "1bzNaA9KjH1ab112CiZYsN_KU8PuhqhYa3stog9GLtkE",
    'USJ' => "1jy4ayNxhOQfLvFgM8frXG4P8AqcZllJbC4fbPno4qek",
    'Kandy' => "1yYTrJsL8opQaT4dBeUXKrUNHRL5x46YZikwJa_6PDPs",
    'Ruhuna' => "14aBHDMBRTWuF9g-0qk-vBOG0CLofhb8CTlj35wV-HeE",
    'SLIIT' => "1lpMGUotVrf6p0Tsw6LImNNZrpcSayn_Pe7YZgR-q8lU"
];

// Other
$spreadsheetId = "1t7Lm1bWIuPtvWPiXUNej2RT0nrUeXcsyV7vwM4xUq6Y";

function append($values, $entity){

    global $sheet_service;
    global $spreadsheetId;
    global $spreadsheetIds;

    if (array_key_exists($entity, $spreadsheetIds)){
        $spreadsheetId = $spreadsheetIds[$entity];
    }

    $body = new Google_Service_Sheets_ValueRange([
        'values' => $values
    ]);

    $all_values = $values;
    array_splice( $all_values[0], 1, 0, [$entity] ); // splice in at position 3
    $all_body = new Google_Service_Sheets_ValueRange([
        'values' => $all_values
    ]);

    $params = [
        'valueInputOption' => 'USER_ENTERED'
    ];

    $range = 'Sign-Ups';

    //Append to all sheet
    $result = $sheet_service->spreadsheets_values->append("1K3BJk3ckc-RtmR9pQeY1Zgpfq-TMF-Q03QrptMf18yo",
        $range, $all_body, $params);

    //Append to entity sheet (or other)
    $result = $sheet_service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
    if($result->getUpdates()->getUpdatedCells() == 9) {
        return true;
    }

    return false;

}



?>
