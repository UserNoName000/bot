<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// if (!isset($_REQUEST)) {
//     return;
// }

ini_set("log_errors", 1);
ini_set("error_log", "bot-error.log");

$confirmationToken = '76802837';
$token = 'vk1.a.xhbPrnArHoRv2kkRSQrsYGD-wVxh83qrlOsV2rAx9G8TufBdhD505Gobm97WSGaiA6FNZTNyMqvhYrzsk7t6fU0UOU0IU3w7eRQGlZKWF_OITtX4x5fnFg_Vo-m4bKcbkYXoJgZb0DGzkx5wCkMESsKYRL-hSQIbz-U2zEZGKjwl-oXr1ChZkYzTcdhO5Uc8QCiHTpKBPOQ6Ofzksgvgbg';
$secretKey = 'GTRPBOT_key';
$accessList = [250072028];

$data = json_decode(file_get_contents('php://input'));

require ($_SERVER['DOCUMENT_ROOT']."/php/config.php");
$mysql = new mysqli($config['db_hostname'], $config['db_username'], $config['db_password'], $config['db_database']);

// if(strcmp($data->secret, $secretKey) !== 0 && strcmp($data->type, 'confirmation') !== 0)
//     return;

function vk_msg_send($peer_id, $text) {
    $myCurl = curl_init();
    curl_setopt_array($myCurl, array(
        CURLOPT_URL => 'https://api.vk.com/method/messages.send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query(array(
            'access_token' => 'vk1.a.xhbPrnArHoRv2kkRSQrsYGD-wVxh83qrlOsV2rAx9G8TufBdhD505Gobm97WSGaiA6FNZTNyMqvhYrzsk7t6fU0UOU0IU3w7eRQGlZKWF_OITtX4x5fnFg_Vo-m4bKcbkYXoJgZb0DGzkx5wCkMESsKYRL-hSQIbz-U2zEZGKjwl-oXr1ChZkYzTcdhO5Uc8QCiHTpKBPOQ6Ofzksgvgbg',
            'message' => $text,
            'peer_id' => $peer_id,
            'v' => '5.199',
            'random_id' => '0',
            'disable_mentions' => '1'
        ))
    ));
    $response = curl_exec($myCurl);
    curl_close($myCurl);
}

$data = json_decode(file_get_contents('php://input'));
switch ($data->type) {
    case 'confirmation':
        echo $confirmationToken;
        break;
    
    case 'message_new':
        $message = $data->object->message;
        $message_text = $data->object->message->text;
        $chat_id = $data->object->message->peer_id;

        if ($chat_id == $message->from_id) {echo 'ok'; return;}

        $checkCommand = explode(' ', $message_text)[0];

        $isAccessAdmin = false;
        if (in_array($message->from_id, $accessList)) {
            $isAccessAdmin = true;
        }

        if ($checkCommand == '/ping') {
            vk_msg_send($chat_id, 'Pong!');
        }

        if ($chat_id == 2000000002 || $chat_id == 2000000003) {
            $isAccess = isAccess($message);

            if ($checkCommand == '/help') {
                commandHelp($chat_id, $message);
            }
            else if ($checkCommand == '/online' && $isAccess[1] >= 1) {
                commandOnline($chat_id, $message);
            }
            else if ($checkCommand == '/admins' && $isAccess[1] >= 1) {
                commandAdmins($chat_id, $message);
            }
            else if ($checkCommand == '/reg') {
                commandReg($chat_id, $message);
            }
            else if ($checkCommand == '/del' && $isAccessAdmin) {
                commandDel($chat_id, $message);
            }
            else if ($checkCommand == '/ban' && $isAccess[1] >= 1) {
                commandBan($chat_id, $message);
            }
            else if ($checkCommand == '/unban' && $isAccess[1] >= 3) {
                commandUnban($chat_id, $message);
            }
            else if ($checkCommand == '/banmac' && $isAccess[1] >= 7) {
                // vk_msg_send($chat_id, '#5227854 заблокировал MAC адреса Kirill_Fetisov.');
                commandBanmac($chat_id, $message);
            }
            else if ($checkCommand == '/check' && $isAccess[1] >= 1) {
                commandCheck($chat_id, $message);
            }
            else if ($checkCommand == '/jail' && $isAccess[1] >= 1) {
                commandJail($chat_id, $message);
            }
            else if ($checkCommand == '/mute' && $isAccess[1] >= 1) {
                commandMute($chat_id, $message);
            }
            else if ($checkCommand == '/unmute' && $isAccess[1] >= 1) {
                commandUnmute($chat_id, $message);
            }
            else if ($checkCommand == '/info' && $isAccess[1] >= 1) {
                commandInfo($chat_id, $message);
            }
            else if ($checkCommand == '/banmail' && $isAccess[1] >= 7) {
                commandBanmail($chat_id, $message);
            }
            else if ($checkCommand == '/unbanmail' && $isAccess[1] >= 7) {
                commandUnbanmail($chat_id, $message);
            }
            else if ($checkCommand == '/history' && $isAccess[1] >= 1) {
                commandHistory($chat_id, $message);
            }
            else if ($checkCommand == '/find' && $isAccess[1] >= 1) {
                commandFind($chat_id, $message);
            }
            else if ($checkCommand == '/takelic' && $isAccess[1] >= 4) {
                commandTakelic($chat_id, $message);
            }
            else if ($checkCommand == '/massban' && $isAccess[1] >= 7) {
                commandMassban($chat_id, $message);
            }
			else if ($checkCommand == '/warn' && $isAccess[1] >= 1) {
                commandWarn($chat_id, $message);
            }
			else if ($checkCommand == '/unwarn' && $isAccess[1] >= 1) {
                commandUnWarn($chat_id, $message);
            }
			else if ($checkCommand == '/makehelper' && $isAccess[1] >= 6) {
                commandAddHelper($chat_id, $message);
            }
			else if ($checkCommand == '/removehelper' && $isAccess[1] >= 6) {
                commandRemoveHelper($chat_id, $message);
            }
			else if ($checkCommand == '/onlinehelper' && $isAccess[1] >= 1) {
                commandOnlineHelper($chat_id, $message);
            }
			else if ($checkCommand == '/onlineleader' && $isAccess[1] >= 1) {
                commandOnlineLeader($chat_id, $message);
            }
			else if ($checkCommand == '/makeadmin' && $isAccess[1] >= 6) {
                commandAddAdm($chat_id, $message);
            }
			else if ($checkCommand == '/makeleader' && $isAccess[1] >= 6) {
                commandAddLeader($chat_id, $message);
            }
			else if ($checkCommand == '/tv' && $isAccess[1] >= 1) {
                commandTV($chat_id, $message);
            }
			else if ($checkCommand == '/tab' && $isAccess[1] >= 1) {
                commandOnlineTab($chat_id, $message);
            }
			
        }
        else if ($chat_id == 2000000006 || $chat_id == 2000000008) {
            if ($checkCommand == '/online') {
                commandOnline($chat_id, $message);
            }
        }
        

        echo 'ok';
        break;
}

function commandMassban($chat_id, $message) {
    global $mysql;

    $id = array($message->from_id);
    $messageNoCommand = str_replace('/massban', '', $message->text);
    $messageNoCommand = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    $messageArgs = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    $messageArgs = explode(' ', $messageArgs);

    $dataVK = file_get_contents('adminsMembersVK.json');
    $dataVK = json_decode($dataVK, true);
    
    if (count($messageArgs) < 3 ) return vk_msg_send($chat_id, 'Используйте: /massban [Nickname1,Nickname2,Nickname3] [Срок (дни, как в игре, дата)] [Причина]');

    $nickname = $messageArgs[0];
    $messageArgs = str_replace(explode(' ', $messageArgs[0]), '', $messageArgs);
    if (count($messageArgs) >= 8) {
        $timeban = strtotime(date('d.m.Y H:i', time()).' +'.$messageArgs[1].' year'.' +'.$messageArgs[2].' month'.' +'.$messageArgs[3].' day'.' +'.$messageArgs[4].' hour'.' +'.$messageArgs[5].' minute'.' +'.$messageArgs[6].' second');
        unset($messageArgs[1]); unset($messageArgs[2]); unset($messageArgs[3]); unset($messageArgs[4]); unset($messageArgs[5]); unset($messageArgs[6]);
        $reasonban = implode(' ', $messageArgs);
    }
    else if (count($messageArgs) >= 3 && is_numeric($messageArgs[1]) && (int)$messageArgs[1] >= 1) {
        $timeban = $messageArgs[1];
        $messageArgs = str_replace(explode(' ', $messageArgs[1]), '', $messageArgs);
        unset($messageArgs[1]);
        $reasonban = implode(' ', $messageArgs);

        if (count(explode('.', $timeban)) == 3) {
            $timeban = strtotime($timeban.' '.date('H:i', time()));
            if ($timeban <= time()) return vk_msg_send($chat_id, 'Неверная дата.');
        }
        else {
            $timeban = strtotime(date('d.m.Y H:i', time()).' +'.$timeban.' day');
        }
    }
    else return vk_msg_send($chat_id, 'Используйте: /massban [Nickname1,Nickname2,Nickname3] [Срок (дни, как в игре, дата)] [Причина]');

    $nicknames = explode(",", $nickname);
    foreach ($nicknames as $nickname1) {
        $player = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$nickname1."'");
        $player = $player->fetch_assoc();
        $admin = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$dataVK[$id[0]]."'");
        $admin = $admin->fetch_assoc();
        $log = $mysql->query("SELECT * FROM `Admin_Log` ORDER BY ID DESC LIMIT 1");
        $log = $log->fetch_assoc();

        if (!$player) return vk_msg_send($chat_id, $nickname1.' не найден.');
        if ($player['BanTime_SGT1'] > time()) return vk_msg_send($chat_id, $nickname1.' уже заблокирован #'.$player['BanName'].' до '.date('d.m.Y', $player['BanTime_SGT1']).' за '.$player['BanReason'].'.');
        if ($player['Online'] == 1) return vk_msg_send($chat_id, $nickname1.' в игре.');

        $mysql->query("UPDATE `players` SET `BanTime_SGT1` = '".$timeban."' WHERE `Names` = '".$nickname1."'");
        $mysql->query("UPDATE `players` SET `BanName` = '".$admin['ID']."' WHERE `Names` = '".$nickname1."'");
        $mysql->query("UPDATE `players` SET `BanReason` = '".$reasonban."' WHERE `Names` = '".$nickname1."'");

        $mysql->query("INSERT INTO `Admin_Log`(`Admin_Name`, `Player_Name`, `Type`, `Reason`, `DataTime`) VALUES('".$admin['Names']."', '".$nickname1."', 'Time Ban', '".$reasonban."', '".date('d.m.Y в H:i:s', time())."')");
    }

    vk_msg_send($chat_id, '#'.$admin['ID'].' заблокировал '.$nickname.' до '.date('d.m.Y', $timeban).' за '.$reasonban.'.');
}

function commandTakelic($chat_id, $message) {
    global $mysql;

    $id = array($message->from_id);
    $messageNoCommand = str_replace('/takelic', '', $message->text);
    $messageNoCommand = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    
    if (!$messageNoCommand) return vk_msg_send($chat_id, 'Используйте: /takelic [Nickname]');

    $admin = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$dataVK[$id[0]]."'");
    $admin = $admin->fetch_assoc();
    $player = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$messageNoCommand."'");
    $player = $player->fetch_assoc();

    if (!$player) return vk_msg_send($chat_id, $messageNoCommand.' не найден.');
    if ($player['Online'] == 1) return vk_msg_send($chat_id, $messageNoCommand.' в игре.');

    $mysql->query("UPDATE `players` SET `LicA` = '0' WHERE `Names` = '".$messageNoCommand."'");
    $mysql->query("UPDATE `players` SET `LicB` = '0' WHERE `Names` = '".$messageNoCommand."'");
    $mysql->query("UPDATE `players` SET `LicC` = '0' WHERE `Names` = '".$messageNoCommand."'");
    $mysql->query("UPDATE `players` SET `LicD` = '0' WHERE `Names` = '".$messageNoCommand."'");
    $mysql->query("UPDATE `players` SET `LoseVU` = '".($player['LoseVU']+1)."' WHERE `Names` = '".$messageNoCommand."'");

    $mysql->query("INSERT INTO `Admin_Log`(`Admin_Name`, `Player_Name`, `Type`, `Reason`, `DataTime`) VALUES('".$admin['Names']."', '".$messageNoCommand."', '/atakelic', 'Права', '".date('d.m.Y в H:i:s', time())."')");

    vk_msg_send($chat_id, $messageNoCommand.' был лишен.');
}
function commandTV($chat_id, $message) {
    global $mysql;

    $id = array($message->from_id);
    $messageNoCommand = str_replace('/tv', '', $message->text);
    $messageNoCommand = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    
    if (!$messageNoCommand) return vk_msg_send($chat_id, 'Используйте: /tv [Nickname]');

    $admin = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$dataVK[$id[0]]."'");
    $admin = $admin->fetch_assoc();
    $player = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$messageNoCommand."'");
    $player = $player->fetch_assoc();

    if (!$player) return vk_msg_send($chat_id, $messageNoCommand.' не найден.');

    vk_msg_send($chat_id, 'Ебать ты ахуел, пиздуй на сервер, там и пиши эту команду. Идиот.');
}
function commandFind($chat_id, $message) {
    global $mysql;

    $id = array($message->from_id);
    $messageNoCommand = str_replace('/find', '', $message->text);
    $messageNoCommand = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    
    if (!$messageNoCommand) return vk_msg_send($chat_id, 'Используйте: /find [Часть nickname]');

    $dataVK = file_get_contents('adminsMembersVK.json');
    $dataVK = json_decode($dataVK, true);

    
    $admin = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$dataVK[$id[0]]."'");
    $admin = $admin->fetch_assoc();

    
    $player = $mysql->query("SELECT * FROM `players` WHERE `Names` LIKE '%".$messageNoCommand."%'");
    $text = 'Найденные совпадения:';
    while($row = $player->fetch_array()) {
        if ($row) {
            $text = $text."\n".$row['Names'];
        }
    }
    
    vk_msg_send($chat_id, $text);
}

function commandHistory($chat_id, $message) {
    global $mysql;

    $id = array($message->from_id);
    $messageNoCommand = str_replace('/history', '', $message->text);
    $messageNoCommand = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    
    if (!$messageNoCommand) return vk_msg_send($chat_id, 'Используйте: /history [Nickname]');

    $typeHistoryList = array(
        "Time Ban" => array(true, "Заблокирован"),
        "TimeBan" => array(true, "Заблокирован"),
        "Unban" => array(false, "Разблокирован"),
        "Kick" => array(true, "Кикнут"),
        "Jail" => array(true, "Деморган"),
        "OffJail" => array(true, "Деморган"),
        "/atakelic" => array(false, "Лишен прав"),
        "UnMute" => array(false, "Размучен"),
        "Ban mail" => array(false, "Заблокирована почта"),
        "Warn" => array(true, "Варн")
    );

    $text = 'История наказаний '.$messageNoCommand.':';

    $countHistory = 0;
    $player = $mysql->query("SELECT * FROM `Admin_Log` WHERE `Player_Name` = '".$messageNoCommand."' ORDER BY `ID` DESC");
    while($row = $player->fetch_array()) {
        if (isset($typeHistoryList[$row['Type']]) && $countHistory < 50) {
            $countHistory++;
            $historyReason = "";
            if ($typeHistoryList[$row['Type']][0]) {
                $historyReason = "(".$row['Reason'].")";
            }
            $text = $text."\n".$row['DataTime'].": ".$typeHistoryList[$row['Type']][1].' '.$historyReason.' - '.$row['Admin_Name'];
        }
    }

    vk_msg_send($chat_id, $text);
}

function commandBanmail($chat_id, $message) {
    global $mysql;

    $id = array($message->from_id);
    $messageNoCommand = str_replace('/banmail', '', $message->text);
    $messageNoCommand = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    $messageArgs = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    $messageArgs = explode(' ', $messageArgs);

    $dataVK = file_get_contents('adminsMembersVK.json');
    $dataVK = json_decode($dataVK, true);
    
    if (count($messageArgs) < 2 ) return vk_msg_send($chat_id, 'Используйте: /banmail [Nickname] [Дни]');

    $nickname = $messageArgs[0];
    $messageArgs = str_replace(explode(' ', $messageArgs[0]), '', $messageArgs);
    if (count($messageArgs) >= 8) {
        $timeban = strtotime(date('d.m.Y H:i', time()).' +'.$messageArgs[1].' year'.' +'.$messageArgs[2].' month'.' +'.$messageArgs[3].' day'.' +'.$messageArgs[4].' hour'.' +'.$messageArgs[5].' minute'.' +'.$messageArgs[6].' second');
        unset($messageArgs[1]); unset($messageArgs[2]); unset($messageArgs[3]); unset($messageArgs[4]); unset($messageArgs[5]); unset($messageArgs[6]);
        $reasonban = implode(' ', $messageArgs);
    }
    else if (count($messageArgs) >= 2 && is_numeric($messageArgs[1]) && (int)$messageArgs[1] >= 1) {
        $timeban = $messageArgs[1];
        $messageArgs = str_replace(explode(' ', $messageArgs[1]), '', $messageArgs);
        unset($messageArgs[1]);

        $timeban = strtotime(date('d.m.Y H:i', time()).' +'.$timeban.' day');
    }
    else return vk_msg_send($chat_id, 'Используйте: /banmail [Nickname] [Дни]');

    $player = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$nickname."'");
    $player = $player->fetch_assoc();
    $admin = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$dataVK[$id[0]]."'");
    $admin = $admin->fetch_assoc();
    $ban = $mysql->query("SELECT * FROM `mailBan` WHERE `Nickname` = '".$nickname."'");
    $ban = $ban->fetch_assoc();

    if (!$player) return vk_msg_send($chat_id, $nickname.' не найден.');
    if (isset($ban['Nickname']) && $ban['Datetime'] > time()) return vk_msg_send($chat_id, 'Почта '.$nickname.' уже заблокирована до '.date('d.m.Y', $ban['Datetime']).'.');

    if (isset($ban['Nickname'])) {
        $mysql->query("UPDATE `mailBan` SET `Datetime` = '".$timeban."' WHERE `Nickname` = '".$nickname."'");
    }
    else {
        $mysql->query("INSERT INTO `mailBan`(`Nickname`, `Datetime`) VALUES('".$nickname."', '".$timeban."')");
    }
    $mysql->query("INSERT INTO `Admin_Log`(`Admin_Name`, `Player_Name`, `Type`, `Reason`, `DataTime`) VALUES('".$admin['Names']."', '".$nickname."', 'Ban mail', '".$reasonban."', '".date('d.m.Y в H:i:s', time())."')");

    vk_msg_send($chat_id, '#'.$admin['ID'].' заблокировал почту '.$nickname.' до '.date('d.m.Y', $timeban).'.');
}

function commandUnbanmail($chat_id, $message) {
    global $mysql;

    $id = array($message->from_id);
    $messageNoCommand = str_replace('/unbanmail', '', $message->text);
    $messageNoCommand = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    
    if (!$messageNoCommand) return vk_msg_send($chat_id, 'Используйте: /unbanmail [Nickname]');

    $dataVK = file_get_contents('adminsMembersVK.json');
    $dataVK = json_decode($dataVK, true);

    $player = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$messageNoCommand."'");
    $player = $player->fetch_assoc();
    $admin = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$dataVK[$id[0]]."'");
    $admin = $admin->fetch_assoc();
    $ban = $mysql->query("SELECT * FROM `mailBan` WHERE `Nickname` = '".$messageNoCommand."'");
    $ban = $ban->fetch_assoc();

    if (!$player) return vk_msg_send($chat_id, $messageNoCommand.' не найден.');
    if (!$ban || (isset($ban['Datetime']) && $ban['Datetime'] < time())) return vk_msg_send($chat_id, 'Почта '.$messageNoCommand.' не заблокирована.');

    $mysql->query("UPDATE `mailBan` SET `Datetime` = '0' WHERE `Nickname` = '".$messageNoCommand."'");
    $mysql->query("INSERT INTO `Admin_Log`(`Admin_Name`, `Player_Name`, `Type`, `Reason`, `DataTime`) VALUES('".$admin['Names']."', '".$messageNoCommand."', 'Unbanmail', '', '".date('d.m.Y в H:i:s', time())."')");

    vk_msg_send($chat_id, '#'.$admin['ID'].' разблокировал почту '.$messageNoCommand.'.');
}

function commandInfo($chat_id, $message) {
    global $mysql;

    $id = array($message->from_id);
    $messageNoCommand = str_replace('/info', '', $message->text);
    $messageNoCommand = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    
    if (!$messageNoCommand) return vk_msg_send($chat_id, 'Используйте: /info [Nickname]');

    $dataVK = file_get_contents('adminsMembersVK.json');
    $dataVK = json_decode($dataVK, true);

    $player = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$messageNoCommand."'");
    $player = $player->fetch_assoc();

    if (!$player) return vk_msg_send($chat_id, $messageNoCommand.' не найден.');

    $playerEmail = $mysql->query("SELECT * FROM `players` WHERE `Email_SGT1` = '".$player['Email_SGT1']."'");
    $infoEmail = '';
    $arrayAccounts = array();
    while($row = $playerEmail->fetch_array()) {
        $infoEmail = $infoEmail."\n".$row['Names'];
        if (!in_array($row['Names'], $arrayAccounts)) array_push($arrayAccounts, $row['Names']);
    }
    if ($player['MAC'] && $player['MAC'] != 'Fail' && $player['MAC'] != 'FAIL2') {
        $playerMac = $mysql->query("SELECT * FROM `players` WHERE `MAC` = '".$player['MAC']."'");
        $infoMAC = '';
        while($row = $playerMac->fetch_array()) {
            $infoMAC = $infoMAC."\n".$row['Names'];
            if (!in_array($row['Names'], $arrayAccounts)) array_push($arrayAccounts, $row['Names']);
        }
    }
    if ($player['MACRouter'] && $player['MACRouter'] != '00:00:00:00:00:00') {
        $playerMacrouter = $mysql->query("SELECT * FROM `players` WHERE `MACRouter` = '".$player['MACRouter']."'");
        $infoMACRouter = '';
        while($row = $playerMacrouter->fetch_array()) {
            $infoMACRouter = $infoMACRouter."\n".$row['Names'];
            if (!in_array($row['Names'], $arrayAccounts)) array_push($arrayAccounts, $row['Names']);
        }
    }
    if ($player['hwid']) {
        $playerHwid = $mysql->query("SELECT * FROM `players` WHERE `hwid` = '".$player['hwid']."'");
        $infoHwid = '';
        while($row = $playerHwid->fetch_array()) {
            $infoHwid = $infoHwid."\n".$row['Names'];
            if (!in_array($row['Names'], $arrayAccounts)) array_push($arrayAccounts, $row['Names']);
        }
    }
    if ($player['NewBan']) {
        $playerNewban = $mysql->query("SELECT * FROM `players` WHERE `NewBan` = '".$player['NewBan']."'");
        $infoNewban = '';
        while($row = $playerNewban->fetch_array()) {
            $infoNewban = $infoNewban."\n".$row['Names'];
            if (!in_array($row['Names'], $arrayAccounts)) array_push($arrayAccounts, $row['Names']);
        }
    }
    if ($player['LastIP']) {
        $playerLastip = $mysql->query("SELECT * FROM `players` WHERE `LastIP` = '".$player['LastIP']."'");
        $infoLastip = '';
        while($row = $playerLastip->fetch_array()) {
            $infoLastip = $infoLastip."\n".$row['Names'];
            if (!in_array($row['Names'], $arrayAccounts)) array_push($arrayAccounts, $row['Names']);
        }
    }

    $infoText = 'Информация по аккаунтам '.$messageNoCommand.':';
    $infoText = $infoText."\nСовпадения по Email:".$infoEmail."\n";
    if (isset($infoMAC) && $infoMAC) $infoText = $infoText."\nСовпадения по MAC:".$infoMAC."\n";
    if (isset($infoMACRouter) && $infoMACRouter) $infoText = $infoText."\nСовпадения по MACRouter:".$infoMACRouter."\n";
    if (isset($infoHwid) && $infoHwid) $infoText = $infoText."\nСовпадения по HWID:".$infoHwid."\n";
    if (isset($infoNewban) && $infoNewban) $infoText = $infoText."\nСовпадения по NewBan:".$infoNewban."\n";
    if (isset($infoLastip) && $infoLastip) $infoText = $infoText."\nСовпадения по LastIP:".$infoLastip;

    $infoText = $infoText."\n\n___________\n\n";

    foreach ($arrayAccounts as $value) {
        $playerOne = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$value."'");
        $playerOne = $playerOne->fetch_assoc();
        $infoText = $infoText."\n".$value.":\nПоследний вход: ".$playerOne['DataTimes']."\nLVL: ".$playerOne['Level_SGT1'];
        if ($playerOne['Online'] == 1) {
            $infoText = $infoText."\nСтатус: В игре";
        }
        if ($playerOne['Member_SGT1'] != 0) {
            $org = $mysql->query("SELECT * FROM `Org` WHERE `IDOrg` = '".$playerOne['Member_SGT1']."'");
            $org = $org->fetch_assoc();
            $infoText = $infoText."\nОрганизация: ".$org['NameOrg'];
        }
        if ($playerOne['Warn_SGT1'] != 0) {
            $infoText = $infoText."\nВарны: ".$playerOne['Warn_SGT1']."/3";
        }
        if ($playerOne['MuteTime_SGT1'] != 0) {
            $infoText = $infoText."\nМут: ".($playerOne['MuteTime_SGT1']/60)." мин.";
        }
        if ($playerOne['JailTime_SGT1'] != 0) {
            $infoText = $infoText."\nТюрьма: ".($playerOne['JailTime_SGT1']/60)." мин.";
        }
        if ($playerOne['BanTime_SGT1'] > time()) {
            $infoText = $infoText."\nЗаблокирован:\nДо ".date('d.m.Y H:i', $playerOne['BanTime_SGT1'])."\n#".$playerOne['BanName']."\n".$playerOne['BanReason'];
        }

        $infoText = $infoText."\n\n";
    }

    vk_msg_send($chat_id, $infoText);
}

function commandWarn($chat_id, $message) {
    global $mysql;

    $id = array($message->from_id);
    $messageNoCommand = str_replace('/warn', '', $message->text);
    $messageNoCommand = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    $messageArgs = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    $messageArgs = explode(' ', $messageArgs);

    $dataVK = file_get_contents('adminsMembersVK.json');
    $dataVK = json_decode($dataVK, true);
    
    if (count($messageArgs) < 2 ) return vk_msg_send($chat_id, 'Используйте: /warn [Nickname] [Причина]');

    $nickname = $messageArgs[0];
    $reasonwarn = $messageArgs[1];

    $player = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$nickname."'");
    $player = $player->fetch_assoc();
    $admin = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$dataVK[$id[0]]."'");
    $admin = $admin->fetch_assoc();
    $log = $mysql->query("SELECT * FROM `Admin_Log` ORDER BY ID DESC LIMIT 1");
    $log = $log->fetch_assoc();

    if (!$player) return vk_msg_send($chat_id, $nickname.' не найден.');
    //if ($player['MuteTime_SGT1'] > 0) return vk_msg_send($chat_id, $nickname.' уже замучен на '.($user['MuteTime_SGT1']/60).' мин.');
    if ($player['Online'] == 1) return vk_msg_send($chat_id, $nickname.' в игре.');
	
	if ($player['Warn_SGT1'] > 1)
	{
		$timeban = 14;
        $timeban = strtotime(date('d.m.Y H:i', time()).' +'.$timeban.' day');
		$player = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$nickname."'");
		$player = $player->fetch_assoc();
		$admin = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$dataVK[$id[0]]."'");
		$admin = $admin->fetch_assoc();
		$log = $mysql->query("SELECT * FROM `Admin_Log` ORDER BY ID DESC LIMIT 1");
		$log = $log->fetch_assoc();

		if (!$player) return vk_msg_send($chat_id, $nickname.' не найден.');
		if ($player['BanTime_SGT1'] > time()) return vk_msg_send($chat_id, $nickname.' уже заблокирован #'.$player['BanName'].' до '.date('d.m.Y', $player['BanTime_SGT1']).' за '.$player['BanReason'].'.');
		if ($player['Online'] == 1) return vk_msg_send($chat_id, $nickname.' в игре.');

		$mysql->query("UPDATE `players` SET `BanTime_SGT1` = '".$timeban."' WHERE `Names` = '".$nickname."'");
		$mysql->query("UPDATE `players` SET `BanName` = '".$admin['ID']."' WHERE `Names` = '".$nickname."'");
		$mysql->query("UPDATE `players` SET `BanReason` = '".$reasonwarn."' WHERE `Names` = '".$nickname."'");
		$mysql->query("UPDATE `players` SET `Warn_SGT1` = '0' WHERE `Names` = '".$nickname."'");

		$mysql->query("INSERT INTO `Admin_Log`(`Admin_Name`, `Player_Name`, `Type`, `Reason`, `DataTime`) VALUES('".$admin['Names']."', '".$nickname."', 'Time Ban', '".$reasonwarn."', '".date('d.m.Y в H:i:s', time())."')");

		vk_msg_send($chat_id, '#'.$admin['ID'].' заблокировал '.$nickname.' до '.date('d.m.Y', $timeban).' (3 warn) причина: '.$reasonwarn.'.');
	}
	else
	{		
		$mysql->query("UPDATE `players` SET `Warn_SGT1` = `Warn_SGT1` + '1' WHERE `Names` = '".$nickname."'");

		$mysql->query("INSERT INTO `Admin_Log`(`Admin_Name`, `Player_Name`, `Type`, `Reason`, `DataTime`) VALUES('".$admin['Names']."', '".$nickname."', 'Warn', '".$reasonwarn."', '".date('d.m.Y в H:i:s', time())."')");

		vk_msg_send($chat_id, '#'.$admin['ID'].' выдал Warn '.$nickname.' с причиной: '.$reasonwarn.'.');
	}	
}

function commandUnWarn($chat_id, $message) {
    global $mysql;

    $id = array($message->from_id);
    $messageNoCommand = str_replace('/unwarn', '', $message->text);
    $messageNoCommand = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    $messageArgs = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    $messageArgs = explode(' ', $messageArgs);

    $dataVK = file_get_contents('adminsMembersVK.json');
    $dataVK = json_decode($dataVK, true);
    
    if (count($messageArgs) < 1 ) return vk_msg_send($chat_id, 'Используйте: /unwarn [Nickname]');

    $nickname = $messageArgs[0];

    $player = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$nickname."'");
    $player = $player->fetch_assoc();
    $admin = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$dataVK[$id[0]]."'");
    $admin = $admin->fetch_assoc();
    $log = $mysql->query("SELECT * FROM `Admin_Log` ORDER BY ID DESC LIMIT 1");
    $log = $log->fetch_assoc();

    if (!$player) return vk_msg_send($chat_id, $nickname.' не найден.');
    if ($player['Warn_SGT1'] == 0) return vk_msg_send($chat_id, $nickname.' не имеет Warn(ов) на аккаунте');
    if ($player['Online'] == 1) return vk_msg_send($chat_id, $nickname.' в игре.');

    $mysql->query("UPDATE `players` SET `Warn_SGT1` = '0' WHERE `Names` = '".$nickname."'");

    $mysql->query("INSERT INTO `Admin_Log`(`Admin_Name`, `Player_Name`, `Type`, `Reason`, `DataTime`) VALUES('".$admin['Names']."', '".$nickname."', 'UnWarn', 'Снял Warn(ы)', '".date('d.m.Y в H:i:s', time())."')");

    vk_msg_send($chat_id, '#'.$admin['ID'].' снял все Warn(ы) с игрока '.$nickname.'.');
}

function commandAddHelper($chat_id, $message) {
    global $mysql;

    $id = array($message->from_id);
    $messageNoCommand = str_replace('/makehelper', '', $message->text);
    $messageNoCommand = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    $messageArgs = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    $messageArgs = explode(' ', $messageArgs);

    $dataVK = file_get_contents('adminsMembersVK.json');
    $dataVK = json_decode($dataVK, true);
    
    if (count($messageArgs) < 1 ) return vk_msg_send($chat_id, 'Используйте: /makehelper [Nickname]');

    $nickname = $messageArgs[0];

    $player = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$nickname."'");
    $player = $player->fetch_assoc();
    $admin = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$dataVK[$id[0]]."'");
    $admin = $admin->fetch_assoc();
    $log = $mysql->query("SELECT * FROM `Admin_Log` ORDER BY ID DESC LIMIT 1");
    $log = $log->fetch_assoc();

    if (!$player) return vk_msg_send($chat_id, $nickname.' не найден.');
    if ($player['Helper_SGT1'] == 1) return vk_msg_send($chat_id, $nickname.' имеет статус помощника сервера!');
    if ($player['Online'] == 1) return vk_msg_send($chat_id, $nickname.' в игре.');

    $mysql->query("UPDATE `players` SET `Helper_SGT1` = '1' WHERE `Names` = '".$nickname."'");

    $mysql->query("INSERT INTO `Admin_Log`(`Admin_Name`, `Player_Name`, `Type`, `Reason`, `DataTime`) VALUES('".$admin['Names']."', '".$nickname."', 'MekeHelper', 'Выдал статус помощника', '".date('d.m.Y в H:i:s', time())."')");

    vk_msg_send($chat_id, '#'.$admin['ID'].' выдал статус помощника сервера игроку '.$nickname.'.');
}
function commandRemoveHelper($chat_id, $message) {
    global $mysql;

    $id = array($message->from_id);
    $messageNoCommand = str_replace('/removehelper', '', $message->text);
    $messageNoCommand = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    $messageArgs = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    $messageArgs = explode(' ', $messageArgs);

    $dataVK = file_get_contents('adminsMembersVK.json');
    $dataVK = json_decode($dataVK, true);
    
    if (count($messageArgs) < 1 ) return vk_msg_send($chat_id, 'Используйте: /removehelper [Nickname]');

    $nickname = $messageArgs[0];

    $player = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$nickname."'");
    $player = $player->fetch_assoc();
    $admin = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$dataVK[$id[0]]."'");
    $admin = $admin->fetch_assoc();
    $log = $mysql->query("SELECT * FROM `Admin_Log` ORDER BY ID DESC LIMIT 1");
    $log = $log->fetch_assoc();

    if (!$player) return vk_msg_send($chat_id, $nickname.' не найден.');
    if ($player['Helper_SGT1'] == 0) return vk_msg_send($chat_id, $nickname.' не является помощником сервера!');
    if ($player['Online'] == 1) return vk_msg_send($chat_id, $nickname.' в игре.');

    $mysql->query("UPDATE `players` SET `Helper_SGT1` = '0' WHERE `Names` = '".$nickname."'");

    $mysql->query("INSERT INTO `Admin_Log`(`Admin_Name`, `Player_Name`, `Type`, `Reason`, `DataTime`) VALUES('".$admin['Names']."', '".$nickname."', 'RemoveHelper', 'Снял статус помощника', '".date('d.m.Y в H:i:s', time())."')");

    vk_msg_send($chat_id, '#'.$admin['ID'].' снял статус помощника сервера игроку '.$nickname.'.');
}
function commandMute($chat_id, $message) {
    global $mysql;

    $id = array($message->from_id);
    $messageNoCommand = str_replace('/mute', '', $message->text);
    $messageNoCommand = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    $messageArgs = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    $messageArgs = explode(' ', $messageArgs);

    $dataVK = file_get_contents('adminsMembersVK.json');
    $dataVK = json_decode($dataVK, true);
    
    if (count($messageArgs) < 3 ) return vk_msg_send($chat_id, 'Используйте: /mute [Nickname] [Срок] [Причина]');

    $nickname = $messageArgs[0];
    $timemute = $messageArgs[1]*60;
    $reasonmute = $messageArgs[2];

    $player = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$nickname."'");
    $player = $player->fetch_assoc();
    $admin = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$dataVK[$id[0]]."'");
    $admin = $admin->fetch_assoc();
    $log = $mysql->query("SELECT * FROM `Admin_Log` ORDER BY ID DESC LIMIT 1");
    $log = $log->fetch_assoc();

    if (!$player) return vk_msg_send($chat_id, $nickname.' не найден.');
    if ($player['MuteTime_SGT1'] > 0) return vk_msg_send($chat_id, $nickname.' уже замучен на '.($user['MuteTime_SGT1']/60).' мин.');
    if ($player['Online'] == 1) return vk_msg_send($chat_id, $nickname.' в игре.');

    $mysql->query("UPDATE `players` SET `MuteTime_SGT1` = '".$timemute."' WHERE `Names` = '".$nickname."'");

    $mysql->query("INSERT INTO `Admin_Log`(`Admin_Name`, `Player_Name`, `Type`, `Reason`, `DataTime`) VALUES('".$admin['Names']."', '".$nickname."', 'Mute', '".$reasonmute."', '".date('d.m.Y в H:i:s', time())."')");

    vk_msg_send($chat_id, '#'.$admin['ID'].' замутил '.$nickname.' на '.($timemute/60).' мин. за '.$reasonmute.'.');
}

function commandUnmute($chat_id, $message) {
    global $mysql;

    $id = array($message->from_id);
    $messageNoCommand = str_replace('/unmute', '', $message->text);
    $messageNoCommand = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    
    if (!$messageNoCommand) return vk_msg_send($chat_id, 'Используйте: /unmute [Nickname]');

    $dataVK = file_get_contents('adminsMembersVK.json');
    $dataVK = json_decode($dataVK, true);

    $player = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$messageNoCommand."'");
    $player = $player->fetch_assoc();
    $admin = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$dataVK[$id[0]]."'");
    $admin = $admin->fetch_assoc();

    if (!$player) return vk_msg_send($chat_id, $nickname.' не найден.');
    if ($player['MuteTime_SGT1'] == 0) return vk_msg_send($chat_id, $messageNoCommand.' не замучен.');

    $mysql->query("UPDATE `players` SET `MuteTime_SGT1` = '0' WHERE `Names` = '".$messageNoCommand."'");

    $mysql->query("INSERT INTO `Admin_Log`(`Admin_Name`, `Player_Name`, `Type`, `Reason`, `DataTime`) VALUES('".$admin['Names']."', '".$messageNoCommand."', 'Unmute', '', '".date('d.m.Y в H:i:s', time())."')");

    vk_msg_send($chat_id, '#'.$admin['ID'].' размутил '.$messageNoCommand.'.');
}

function commandJail($chat_id, $message) {
    global $mysql;

    $id = array($message->from_id);
    $messageNoCommand = str_replace('/jail', '', $message->text);
    $messageNoCommand = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    $messageArgs = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    $messageArgs = explode(' ', $messageArgs);

    $dataVK = file_get_contents('adminsMembersVK.json');
    $dataVK = json_decode($dataVK, true);
    
    if (count($messageArgs) < 3 ) return vk_msg_send($chat_id, 'Используйте: /jail [Nickname] [Срок] [Причина]');

    $nickname = $messageArgs[0];
    $timejail = $messageArgs[1]*60;
    $reasonjail = $messageArgs[2];

    $player = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$nickname."'");
    $player = $player->fetch_assoc();
    $admin = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$dataVK[$id[0]]."'");
    $admin = $admin->fetch_assoc();
    $log = $mysql->query("SELECT * FROM `Admin_Log` ORDER BY ID DESC LIMIT 1");
    $log = $log->fetch_assoc();

    if (!$player) return vk_msg_send($chat_id, $nickname.' не найден.');
    if ($player['JailTime_SGT1'] > 0) return vk_msg_send($chat_id, $nickname.' уже в тюрьме на '.($user['JailTime_SGT1']/60).' мин.');
    if ($player['Online'] == 1) return vk_msg_send($chat_id, $nickname.' в игре.');

    $mysql->query("UPDATE `players` SET `JailTime_SGT1` = '".$timejail."' WHERE `Names` = '".$nickname."'");

    $mysql->query("INSERT INTO `Admin_Log`(`Admin_Name`, `Player_Name`, `Type`, `Reason`, `DataTime`) VALUES('".$admin['Names']."', '".$nickname."', 'Jail', '".$reasonjail."', '".date('d.m.Y в H:i:s', time())."')");

    vk_msg_send($chat_id, '#'.$admin['ID'].' посадил в тюрьму '.$nickname.' на '.($timejail/60).' мин. за '.$reasonjail.'.');
}

function commandAddLeader($chat_id, $message) {
    global $mysql;

    $id = array($message->from_id);
    $messageNoCommand = str_replace('/makeleader', '', $message->text);
    $messageNoCommand = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    $messageArgs = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    $messageArgs = explode(' ', $messageArgs);

    $dataVK = file_get_contents('adminsMembersVK.json');
    $dataVK = json_decode($dataVK, true);
    
    if (count($messageArgs) < 2 ) return vk_msg_send($chat_id, 'Используйте: /makeleader [Nickname] [ID организации]');

    $nickname = $messageArgs[0];
    $idleaders = $messageArgs[1];

    $player = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$nickname."'");
    $player = $player->fetch_assoc();
    $admin = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$dataVK[$id[0]]."'");
    $admin = $admin->fetch_assoc();
    $log = $mysql->query("SELECT * FROM `Admin_Log` ORDER BY ID DESC LIMIT 1");
    $log = $log->fetch_assoc();

    if (!$player) return vk_msg_send($chat_id, $nickname.' не найден.');
    if ($player['Online'] == 1) return vk_msg_send($chat_id, $nickname.' в игре.');

    $mysql->query("UPDATE `players` SET `Leader_SGT1` = '".$idleaders."' WHERE `Names` = '".$nickname."'");
	$mysql->query("UPDATE `players` SET `Member_SGT1` = '".$idleaders."' WHERE `Names` = '".$nickname."'");

    $mysql->query("INSERT INTO `Admin_Log`(`Admin_Name`, `Player_Name`, `Type`, `Reason`, `DataTime`) VALUES('".$admin['Names']."', '".$nickname."', 'MakeLeader', 'Выдал лидерку', '".date('d.m.Y в H:i:s', time())."')");
	$org = $mysql->query("SELECT * FROM `Org` WHERE `IDOrg` = '".$idleaders."'");
    $org = $org->fetch_assoc();
	vk_msg_send($chat_id, '#'.$admin['ID'].' выдал лидерство игроку '.$nickname.' организация: '.$org['NameOrg'].'.');
}

function commandAddAdm($chat_id, $message) {
    global $mysql;

    $id = array($message->from_id);
    $messageNoCommand = str_replace('/makeadmin', '', $message->text);
    $messageNoCommand = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    $messageArgs = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    $messageArgs = explode(' ', $messageArgs);

    $dataVK = file_get_contents('adminsMembersVK.json');
    $dataVK = json_decode($dataVK, true);
    
    if (count($messageArgs) < 2 ) return vk_msg_send($chat_id, 'Используйте: /makeadmin [Nickname] [Ранг]');

    $nickname = $messageArgs[0];
    $rankadm = $messageArgs[1];

    $player = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$nickname."'");
    $player = $player->fetch_assoc();
    $admin = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$dataVK[$id[0]]."'");
    $admin = $admin->fetch_assoc();
    $log = $mysql->query("SELECT * FROM `Admin_Log` ORDER BY ID DESC LIMIT 1");
    $log = $log->fetch_assoc();

    if (!$player) return vk_msg_send($chat_id, $nickname.' не найден.');
    if ($player['Online'] == 1) return vk_msg_send($chat_id, $nickname.' в игре.');

    $mysql->query("UPDATE `players` SET `Admin_SGT1` = '".$rankadm."' WHERE `Names` = '".$nickname."'");

    $mysql->query("INSERT INTO `Admin_Log`(`Admin_Name`, `Player_Name`, `Type`, `Reason`, `DataTime`) VALUES('".$admin['Names']."', '".$nickname."', 'MakeAdmin', 'Выдал админку', '".date('d.m.Y в H:i:s', time())."')");

    vk_msg_send($chat_id, '#'.$admin['ID'].' выдал статус администратора '.$nickname.' ранг администратора '.$rankadm.'.');
}

function commandCheck($chat_id, $message) {
    global $mysql;

    $id = array($message->from_id);
    $messageNoCommand = str_replace('/check', '', $message->text);
    $messageNoCommand = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    
    if (!$messageNoCommand) return vk_msg_send($chat_id, 'Используйте: /check [Nickname]');

    $dataVK = file_get_contents('adminsMembersVK.json');
    $dataVK = json_decode($dataVK, true);

    $player = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$messageNoCommand."'");
    $player = $player->fetch_assoc();
    $admin = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$dataVK[$id[0]]."'");
    $admin = $admin->fetch_assoc();

    if (!$player) return vk_msg_send($chat_id, $messageNoCommand.' не найден.');

    $text = "Статистика ".$messageNoCommand.":\nНомер аккаунта: ".$player['ID']."\nEmail: ".$player['Email_SGT1']."\nПоследний вход: ".$player['DataTimes']."\nLVL: ".$player['Level_SGT1']."\nНаличка: ".$player['Money_SGT1']."\nБанк: ".$player['Bank_SGT1'];
    if ($player['Online'] == 1) {
        $text = $text."\nСтатус: В игре";
    }
    if ($player['Member_SGT1'] != 0) {
        $org = $mysql->query("SELECT * FROM `Org` WHERE `IDOrg` = '".$player['Member_SGT1']."'");
        $org = $org->fetch_assoc();
        $text = $text."\nОрганизация: ".$org['NameOrg'];
    }
    if ($player['Warn_SGT1'] != 0) {
        $text = $text."\nВарны: ".$player['Warn_SGT1']."/3";
    }
    if ($player['MuteTime_SGT1'] != 0) {
        $text = $text."\nМут: ".($player['MuteTime_SGT1']/60)." мин.";
    }
    if ($player['JailTime_SGT1'] != 0) {
        $text = $text."\nТюрьма: ".($player['JailTime_SGT1']/60)." мин.";
    }
    if ($player['BanTime_SGT1'] > time()) {
        $text = $text."\n\nЗаблокирован:\nДо ".date('d.m.Y H:i', $player['BanTime_SGT1'])."\n#".$player['BanName']."\n".$player['BanReason'];
    }

    vk_msg_send($chat_id, $text);
}

function commandBanmac($chat_id, $message) {
    global $mysql;

    $id = array($message->from_id);
    $messageNoCommand = str_replace('/banmac', '', $message->text);
    $messageNoCommand = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    
    if (!$messageNoCommand) return vk_msg_send($chat_id, 'Используйте: /banmac [Nickname]');

    $dataVK = file_get_contents('adminsMembersVK.json');
    $dataVK = json_decode($dataVK, true);

    $player = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$messageNoCommand."'");
    $player = $player->fetch_assoc();
    $admin = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$dataVK[$id[0]]."'");
    $admin = $admin->fetch_assoc();

    if (!$player) return vk_msg_send($chat_id, $nickname.' не найден.');

    if ($player['MAC'] != 'Fail') $mysql->query("INSERT INTO `BanMAC`(`mac_id`) VALUES('".$player['MAC']."')");
    if ($player['MACRouter'] != '00:00:00:00:00:00') $mysql->query("INSERT INTO `BanMACRouter`(`mac_id`) VALUES('".$player['MACRouter']."')");
    if ($player['NewBan']) $mysql->query("INSERT INTO `NewBan`(`unq_id`) VALUES('".$player['NewBan']."')");

    vk_msg_send($chat_id, '#'.$admin['ID'].' заблокировал MAC адреса '.$messageNoCommand.'.');
}

function commandUnban($chat_id, $message) {
    global $mysql;

    $id = array($message->from_id);
    $messageNoCommand = str_replace('/unban', '', $message->text);
    $messageNoCommand = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    
    if (!$messageNoCommand) return vk_msg_send($chat_id, 'Используйте: /unban [Nickname]');

    $dataVK = file_get_contents('adminsMembersVK.json');
    $dataVK = json_decode($dataVK, true);

    $player = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$messageNoCommand."'");
    $player = $player->fetch_assoc();
    $admin = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$dataVK[$id[0]]."'");
    $admin = $admin->fetch_assoc();

    if (!$player) return vk_msg_send($chat_id, $nickname.' не найден.');
    if ($player['BanTime_SGT1'] == 0) return vk_msg_send($chat_id, $messageNoCommand.' не заблокирован.');

    $mysql->query("UPDATE `players` SET `BanTime_SGT1` = '0' WHERE `Names` = '".$messageNoCommand."'");
    $mysql->query("UPDATE `players` SET `BanName` = 'No' WHERE `Names` = '".$messageNoCommand."'");
    $mysql->query("UPDATE `players` SET `BanReason` = 'No' WHERE `Names` = '".$messageNoCommand."'");

    $mysql->query("INSERT INTO `Admin_Log`(`Admin_Name`, `Player_Name`, `Type`, `Reason`, `DataTime`) VALUES('".$admin['Names']."', '".$messageNoCommand."', 'Unban', '', '".date('d.m.Y в H:i:s', time())."')");

    vk_msg_send($chat_id, '#'.$admin['ID'].' разблокировал '.$messageNoCommand.'.');
}

function commandBan($chat_id, $message) {
    global $mysql;

    $id = array($message->from_id);
    $messageNoCommand = str_replace('/ban', '', $message->text);
    $messageNoCommand = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    $messageArgs = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    $messageArgs = explode(' ', $messageArgs);

    $dataVK = file_get_contents('adminsMembersVK.json');
    $dataVK = json_decode($dataVK, true);
    
    if (count($messageArgs) < 3 ) return vk_msg_send($chat_id, 'Используйте: /ban [Nickname] [Срок (дни, как в игре, дата)] [Причина]');

    $nickname = $messageArgs[0];
    $messageArgs = str_replace(explode(' ', $messageArgs[0]), '', $messageArgs);
    if (count($messageArgs) >= 8) {
        $timeban = strtotime(date('d.m.Y H:i', time()).' +'.$messageArgs[1].' year'.' +'.$messageArgs[2].' month'.' +'.$messageArgs[3].' day'.' +'.$messageArgs[4].' hour'.' +'.$messageArgs[5].' minute'.' +'.$messageArgs[6].' second');
        unset($messageArgs[1]); unset($messageArgs[2]); unset($messageArgs[3]); unset($messageArgs[4]); unset($messageArgs[5]); unset($messageArgs[6]);
        $reasonban = implode(' ', $messageArgs);
    }
    else if (count($messageArgs) >= 3 && is_numeric($messageArgs[1]) && (int)$messageArgs[1] >= 1) {
        $timeban = $messageArgs[1];
        $messageArgs = str_replace(explode(' ', $messageArgs[1]), '', $messageArgs);
        unset($messageArgs[1]);
        $reasonban = implode(' ', $messageArgs);

        if (count(explode('.', $timeban)) == 3) {
            $timeban = strtotime($timeban.' '.date('H:i', time()));
            if ($timeban <= time()) return vk_msg_send($chat_id, 'Неверная дата.');
        }
        else {
            $timeban = strtotime(date('d.m.Y H:i', time()).' +'.$timeban.' day');
        }
    }
    else return vk_msg_send($chat_id, 'Используйте: /ban [Nickname] [Срок (дни, как в игре, дата)] [Причина]');

    $player = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$nickname."'");
    $player = $player->fetch_assoc();
    $admin = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$dataVK[$id[0]]."'");
    $admin = $admin->fetch_assoc();
    $log = $mysql->query("SELECT * FROM `Admin_Log` ORDER BY ID DESC LIMIT 1");
    $log = $log->fetch_assoc();

    if (!$player) return vk_msg_send($chat_id, $nickname.' не найден.');
    if ($player['BanTime_SGT1'] > time()) return vk_msg_send($chat_id, $nickname.' уже заблокирован #'.$player['BanName'].' до '.date('d.m.Y', $player['BanTime_SGT1']).' за '.$player['BanReason'].'.');
    if ($player['Online'] == 1) return vk_msg_send($chat_id, $nickname.' в игре.');

    $mysql->query("UPDATE `players` SET `BanTime_SGT1` = '".$timeban."' WHERE `Names` = '".$nickname."'");
    $mysql->query("UPDATE `players` SET `BanName` = '".$admin['ID']."' WHERE `Names` = '".$nickname."'");
    $mysql->query("UPDATE `players` SET `BanReason` = '".$reasonban."' WHERE `Names` = '".$nickname."'");

    $mysql->query("INSERT INTO `Admin_Log`(`Admin_Name`, `Player_Name`, `Type`, `Reason`, `DataTime`) VALUES('".$admin['Names']."', '".$nickname."', 'Time Ban', '".$reasonban."', '".date('d.m.Y в H:i:s', time())."')");

    vk_msg_send($chat_id, '#'.$admin['ID'].' заблокировал '.$nickname.' до '.date('d.m.Y', $timeban).' за '.$reasonban.'.');
}

function commandDel($chat_id, $message) {
    $id = getID($message);
    if (!$id[0]) {
        return vk_msg_send($chat_id, 'Используйте: /del [Пользователь]');
    }
    
    $dataVK = file_get_contents('adminsMembersVK.json');
    $dataVK = json_decode($dataVK, true);
    if (!$dataVK[$id[0]]) {
        return vk_msg_send($chat_id, '@id'.$id[0].'(Пользователь) не найден в базе данных ВК.');
    }

    unset($dataVK[$id[0]]);
    $dataVKNew = json_encode($dataVK);
    file_put_contents('adminsMembersVK.json', $dataVKNew);

    vk_msg_send($chat_id, '@id'.$id[0].'(Пользователь) удален из базы данных.');
}

function commandReg($chat_id, $message) {
    $id = array($message->from_id);
    $messageNoCommand = str_replace('/reg', '', $message->text);
    $messageNoCommand = join("\n", array_map("trim", explode("\n", $messageNoCommand)));
    
    if (!$messageNoCommand) return vk_msg_send($chat_id, 'Используйте: /reg [Nickname]');

    $dataVK = file_get_contents('adminsMembersVK.json');
    $dataVK = json_decode($dataVK, true);

    if ($dataVK[$id[0]]) return vk_msg_send($chat_id, '@id'.$id[0].'('.$dataVK[$id[0]].') уже зарегистрирован.');

    $dataVK[$id[0]] = $messageNoCommand;
    $dataVKNew = json_encode($dataVK);
    file_put_contents('adminsMembersVK.json', $dataVKNew);
    vk_msg_send($chat_id, '@id'.$id[0].'('.$messageNoCommand.') добавлен в базу данных ВК.');
}

function commandAdmins($chat_id, $message) {
    global $idORG;
    $dataVK = file_get_contents('adminsMembersVK.json');
    $dataVK = json_decode($dataVK, true);

    $membersText = '';
    foreach ($dataVK as $id => $member) {
        $membersText = $membersText."\n".'@id'.$id.'('.$member.')';
    }

    vk_msg_send($chat_id, $membersText);
}

function commandOnline($chat_id, $message) {
    global $mysql;
    require_once($_SERVER['DOCUMENT_ROOT']."/php/get_online.php"); 
    $query = new SampQueryAPI('51.91.91.105', '7777'); 
    $serverInfo = $query->getInfo(); 
    $onlineServer = $serverInfo['players'];

    if ($chat_id == 2000000002) {
        $online = $mysql->query("SELECT * FROM `players` WHERE `Admin_SGT1` > 0 AND `Online` = 1");
        $iAdmins = '';
        while($row = $online->fetch_array()) {
            $iAdmins = $iAdmins."\n#".$row['ID']." ".$row['Names']." (".$row['Admin_SGT1'].")";
        }
        vk_msg_send($chat_id, "Онлайн на сервере: ". $onlineServer."\n\nАдминистрация:".$iAdmins);
    }
    else if ($chat_id == 2000000006) {
        $online = $mysql->query("SELECT * FROM `players` WHERE `Member_SGT1` = 17 AND `Online` = 1");
        $iProcustom = '';
        while($row = $online->fetch_array()) {
            $iProcustom = $iProcustom."\n".$row['Names'];
        }
        vk_msg_send($chat_id, "Онлайн на сервере: ". $onlineServer."\n\n".$iProcustom);
    }
    else if ($chat_id == 2000000008) {
        $online = $mysql->query("SELECT * FROM `players` WHERE `Member_SGT1` = 11 AND `Online` = 1");
        $iProcustom = '';
        while($row = $online->fetch_array()) {
            $iProcustom = $iProcustom."\n".$row['Names'];
        }
        vk_msg_send($chat_id, "Онлайн на сервере: ". $onlineServer."\n\n".$iProcustom);
    }
   
}
function commandOnlineHelper($chat_id, $message) {
    global $mysql;
    require_once($_SERVER['DOCUMENT_ROOT']."/php/get_online.php"); 
    $query = new SampQueryAPI('51.91.91.105', '7777'); 
    $serverInfo = $query->getInfo(); 
    $onlineServer = $serverInfo['players'];
    $online = $mysql->query("SELECT * FROM `players` WHERE `Helper_SGT1` > 0 AND `Online` = 1");
	$iHelper = '';
	while($row = $online->fetch_array()) {
		$iHelper = $iHelper."\n".$row['Names'].".";
	}
	vk_msg_send($chat_id, "Онлайн на сервере: ". $onlineServer."\n\n");
	vk_msg_send($chat_id, "Помощники онлайн:".$iHelper);
}
function commandOnlineLeader($chat_id, $message) {
    global $mysql;
    require_once($_SERVER['DOCUMENT_ROOT']."/php/get_online.php"); 
    $query = new SampQueryAPI('51.91.91.105', '7777'); 
    $serverInfo = $query->getInfo(); 
    $onlineServer = $serverInfo['players'];
    $online = $mysql->query("SELECT * FROM `players` WHERE `Leader_SGT1` > 0 AND `Online` = 1");
	$iLeader = '';
	while($row = $online->fetch_array()) {
		$org = $mysql->query("SELECT * FROM `Org` WHERE `IDOrg` = '".$row['Member_SGT1']."'");
        $org = $org->fetch_assoc();
		$iLeader = $iLeader."\n".$row['Names']." (".$org['NameOrg'].")";
	}
	vk_msg_send($chat_id, "Онлайн на сервере: ". $onlineServer."\n\n");
	vk_msg_send($chat_id, "Лидеры онлайн:".$iLeader);
}
function commandOnlineTab($chat_id, $message) {
    require_once($_SERVER['DOCUMENT_ROOT']."/php/get_online.php"); 
    try {
        $query = new SampQueryAPI('51.91.91.105', 7777);
        $info = $query->getInfo(); 
        $playersInfo = $query->getDetailedPlayers();
    } catch (Exception $e) {
        vk_msg_send($chat_id, "Ошибка подключения к серверу: " . $e->getMessage());
        return;
    }

    if (empty($playersInfo) || !is_array($playersInfo)) {
        vk_msg_send($chat_id, "Сейчас на сервере нет игроков.");
        return;
    }

    $onlinePlayers = isset($info['players']) ? $info['players'] : '?';
    $maxPlayers = isset($info['maxplayers']) ? $info['maxplayers'] : '?';

    $responseText = "Онлайн сервера: {$onlinePlayers} / {$maxPlayers}\n";
    $responseText .= "Игроки онлайн:\n";
    foreach ($playersInfo as $player) {
        $id = $player['playerid'] ?? '-';
        $nickname = $player['nickname'] ?? 'Неизвестно';
        $score = $player['score'] ?? '-';
        $ping = $player['ping'] ?? '-';
        $responseText .= "🆔 $id | 👤 $nickname | Уровень: $score | 📶 $ping\n";
    }

    vk_msg_send($chat_id, $responseText);
}
function commandHelp($chat_id, $message) {
    $text = "Список доступных команд:
        /help - просмотр всех команд
        /ping - проверка работоспособности бота
        /ban - заблокировать на время
        /banmac - заблокировать по железу
        /unban - разблокировать
        /jail - посадить в тюрьму
        /mute - замутить
        /check - статистика игрока
        /online - онлайн (в т.ч. администрации)
        /info - информация по аккаунтам
        /banmail - заблокировать почту
        /unbanmail - разблокировать почту
        /history - история наказаний
        /find - поиск по части ника
        /warn - выдать Warn
        /unwarn - снять Warn(ы)
        /makehelper - поставить помощника (6 лвл)
        /removehelper - снять помощника (6 лвл)
        /makeleader - выдать Лидерство организации (6 лвл)
        /makeadmin - выдать статус администратора (6 лвл)
        /onlinehelper - помощники онлайн
        /onlineleader - лидеры онлайн
        /tv - следить за игроком";

    vk_msg_send($chat_id, $text);
}

function getID($message) {
    if (isset($message->reply_message)) {
        return array($message->reply_message->from_id, 1);
    }
    else {
        if (strpos(explode(' ', $message->text)[1], '[id') !== false) {
            return array(str_replace('[id', '', explode('|', explode(' ', $message->text)[1])[0]), 2);
        }
        else {
            return false;
        }
    }
}

function isAccess($message) {
    global $mysql;

    $id = array($message->from_id);
    $dataVK = file_get_contents('adminsMembersVK.json');
    $dataVK = json_decode($dataVK, true);
    if (!$dataVK[$id[0]]) return array(false, 0);

    $admin = $mysql->query("SELECT * FROM `players` WHERE `Names` = '".$dataVK[$id[0]]."'");
    $row = $admin->fetch_assoc();
    return array(true, $row['Admin_SGT1']);
}

if (isset($_GET['id']) && isset($_GET['text'])) {
    vk_msg_send($_GET['id'], $_GET['text']);
}
?>
