<?php
declare(strict_types=1);

$dataFile = __DIR__ . DIRECTORY_SEPARATOR . 'complaints.json';
$organizations = ['PMC', 'PMT', 'Electricity Board', 'Water Supply', 'Other'];
$statuses = ['Pending', 'In Progress', 'Resolved'];

if (!file_exists($dataFile)) {
    file_put_contents($dataFile, json_encode([], JSON_PRETTY_PRINT));
}
