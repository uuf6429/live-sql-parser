<?php

require_once __DIR__ . '/../boot.php';

/** @noinspection SqlNoDataSourceInspection */
$sql = isset($_REQUEST['code']) ? $_REQUEST['code'] : <<<SQL
-- Comment for the code
-- MySQL Mode for CodeMirror2 by MySQLTools http://github.com/partydroid/MySQL-Tools
SELECT  UNIQUE `var1` as `variable`,
        MAX(`var5`) as `max`,
        MIN(`var5`) as `min`,
        STDEV(`var5`) as `dev`
FROM `table`

LEFT JOIN `table2` ON `var2` = `variable`

ORDER BY `var3` DESC
GROUP BY `groupvar`

LIMIT 0,30;

ALTER TABLE `City` ADD PRIMARY KEY (`ID`);
UPDATE `City` SET `Name` = "کندهار‎" WHERE `ID` = 2;
SQL;

$scriptDir = explode('/', ltrim($_SERVER['SCRIPT_NAME'], '/'), 2)[0];

switch (true) {
    // serve assets
    case in_array($scriptDir, ['vendor', 'assets']):
        return false;

    // serve ajax
    case isset($_REQUEST['parse']):
        $content = get_output($sql);
        header('Content-Type: application/json');
        header('Content-Length: ' . strlen($content));
        echo $content;
        break;

    // serve index
    default:
        require_once(__DIR__ . '/view.php');
        break;
}
