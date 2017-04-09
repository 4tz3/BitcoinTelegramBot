<?php

define('BOT_TOKEN', '################################');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');


$content = file_get_contents("php://input");
$update = json_decode($content, true);
if(isset($update["message"]) && $update["message"] != NULL) {
	prepareMessage($update["message"]);
}

		
function prepareMessage($message){
	// Database
	$chatID = $message["chat"]["id"];
	$userID = $message["from"]["id"];
	$command = explode("@", $message["text"])[0];
	$command = explode(" ", $command);

	if(isset($message["new_chat_member"]) && $message["new_chat_member"] != NULL) {
		sendResponse($chatID, urlencode("Willkommen im deutschen Telegram Chat für Kryptowährungen.\n\n\n=== Regeln ===\n\n\n1. Wir behandeln einander mit Respekt und Anstand, auch bei hitzigen Diskussionen ist immer ein normaler Ton zu wahren.\n\n2. Spammer werden ohne Vorwarnung aus der Gruppe ausgeschlossen.\n\n3. Referral-Links zu Multilevel-Marketing-Vertrieben, Strukturvertrieben, Network Marketing und ähnliche sind nicht erlaubt. Auf Nachfrage kann so ein Link natürlich im Privatchat versendet werden, in der Gruppe werden die Links ohne Vorwarnung gelöscht.\n\n4. Es herrscht Themenfreiheit, solange die Thematik Kryptowährungen behandelt wird. OffTopic ist nur in kleinem Maße toleriert.\n\n5. Links zu Cloudmining-Programmen bitte nur auf Nachfrage posten.\n\n6. Es gab in dieser Gruppe immer wieder Betrugsversuche, daher wie immer möglichst vorsichtig an Angebote von Mitgliedern herangehen! Bei direktem Verdacht sofort bei einem der Admins melden, wir versuchen dann, die Sache zu klären.\n\n\nAdministratoren: @cornlinger und @DarthWed"));
	}

	switch(strtolower($command[0])) {
		case "/kurs": {
			if(sizeof($command) == 1) {
				$entry = getAltcoin("btc");
				sendResponse($chatID, urlencode("Du musst eine Währung angeben, deren Kurs du erfahren willst.\nZum Beispiel: /kurs btc\n\n" . "======== " . $entry->name . " ========\n  Preis in €:       " . number_format($entry->price_eur, 5, ",", ".")."\n  Preis in $:       " . number_format($entry->price_usd, 5, ",", ".")."\n  Preis in BTC:  " . number_format($entry->price_btc, 8, ",", ".")."\n\n  Volumen (24h) in ".$entry->symbol.":  " . number_format(($entry->volume_eur/$entry->price_eur), 2, ",", "."). "\n  1h:  " . number_format($entry->percent_change_1h, 2, ",", ".") . "%"." | 24h:  " . number_format($entry->percent_change_24h, 2, ",", ".") . "%"." | 7d:  " . number_format($entry->percent_change_7d, 2, ",", ".") . "%"));
			} else {
				$entry = getAltcoin($command[1]);
				if($entry == NULL) {
				$entry = getAltcoin("btc");
				sendResponse($chatID, urlencode("Kryptocoin \"" . $command[1] . "\" wurde leider nicht gefunden.\n\n" . "======== " . $entry->name . " ========\n  Preis in €:       " . number_format($entry->price_eur, 5, ",", ".")."\n  Preis in $:       " . number_format($entry->price_usd, 5, ",", ".")."\n  Preis in BTC:  " . number_format($entry->price_btc, 8, ",", ".")."\n\n  Volumen (24h) in ".$entry->symbol.":  " . number_format(($entry->volume_eur/$entry->price_eur), 2, ",", "."). "\n  1h:  " . number_format($entry->percent_change_1h, 2, ",", ".") . "%"." | 24h:  " . number_format($entry->percent_change_24h, 2, ",", ".") . "%"." | 7d:  " . number_format($entry->percent_change_7d, 2, ",", ".") . "%"));
				} else {
					sendResponse($chatID, urlencode("======== " . $entry->name . " ========\n  Preis in €:       " . number_format($entry->price_eur, 5, ",", ".")."\n  Preis in $:       " . number_format($entry->price_usd, 5, ",", ".")."\n  Preis in BTC:  " . number_format($entry->price_btc, 8, ",", ".")."\n\n  Volumen (24h) in ".$entry->symbol.":  " . number_format(($entry->volume_eur/$entry->price_eur), 2, ",", "."). "\n  1h:  " . number_format($entry->percent_change_1h, 2, ",", ".") . "%"." | 24h:  " . number_format($entry->percent_change_24h, 2, ",", ".") . "%"." | 7d:  " . number_format($entry->percent_change_7d, 2, ",", ".") . "%"));
				}
			}
			break;	
		}
		case "/regeln":
			sendResponse($chatID, 
			urlencode("Willkommen im deutschen Telegram Chat für Kryptowährungen.\n\n\n=== Regeln ===\n\n\n1. Wir behandeln einander mit Respekt und Anstand, auch bei hitzigen Diskussionen ist immer ein normaler Ton zu wahren.\n\n2. Spammer werden ohne Vorwarnung aus der Gruppe ausgeschlossen.\n\n3. Referral-Links zu Multilevel-Marketing-Vertrieben, Strukturvertrieben, Network Marketing und ähnliche sind nicht erlaubt. Auf Nachfrage kann so ein Link natürlich im Privatchat versendet werden, in der Gruppe werden die Links ohne Vorwarnung gelöscht.\n\n4. Es herrscht Themenfreiheit, solange die Thematik Kryptowährungen behandelt wird. OffTopic ist nur in kleinem Maße toleriert.\n\n5. Links zu Cloudmining-Programmen bitte nur auf Nachfrage posten.\n\n6. Es gab in dieser Gruppe immer wieder Betrugsversuche, daher wie immer möglichst vorsichtig an Angebote von Mitgliedern herangehen! Bei direktem Verdacht sofort bei einem der Admins melden, wir versuchen dann, die Sache zu klären.\n\n\nAdministratoren: @cornlinger und @DarthWed"));
			break;
		case "/hilfe":
			sendResponse($chatID, 
				urlencode("=== Hilfe ===\n\n/kurs <kürzel> - Zeigt den aktuellen Bitcoin-Kurs an. Zum Beispiel: /kurs btc\n\n/regeln - Zeigt die Chatregeln an. Diese sind zu befolgen.\n\n/hilfe - Zeigt diese Hilfe an\n\nKritik, Änderungs- und Featurewünsche bitte an @DarthWed"));
			break;
		case "/admin_chatid": {
			if($userID == 14430665) {
				sendResponse($chatID, "ChatID: ".$chatID);
			}
			break;
		}
		default:
			if(str_replace("/", "", $command[0]) != "") {
				sendResponse($chatID, "Befehl \"" . str_replace("/", "", $command[0]) . "\" wurde nicht gefunden.");
			}
		break;
	}
	
}

function sendResponse($chatID, $reply) {
	file_get_contents(API_URL."sendmessage?chat_id=".$chatID."&text=".$reply);
}

function getAltcoin($altcoin) {
   return json_decode(file_get_contents("https://api.turcan.de/cryptocoins/index.php?coinmarketcap&cryptocoin=".$altcoin)); 
}
?>
