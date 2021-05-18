<?php

namespace rtgamingwdt\Override\commands;

use rtgamingwdt\Override\Main;

use pocketmine\command\CommandSender;
use pocketmine\permission\BanEntry;
use pocketmine\utils\TextFormat;
use function array_map;
use function count;
use function implode;
use function sort;
use function strtolower;
use const SORT_STRING;

class BanListCommand extends PluginCommand {
  
  public function __construct() {
    parent::__construct("banlist", Main::getMain());
    
    $this->setDescription("View all players that are banned from this server");
    $this->setPermission("cmd.banlist");
  }
  
  function execute(CommandSender $sender, string $commandLabel, array $args) {
    if(isset($args[0])){
      $args[0] = strtolower($args[0]);
      if($args[0] === "ips"){
        $list = $sender->getServer()->getIPBans();
      }elseif($args[0] === "players"){
        $list = $sender->getServer()->getNameBans();
      }else{
				$sender->sendMessage(TextFormat::RED . "Please use /banlist ips or /banlist players");
			}
		}else{
			$list = $sender->getServer()->getNameBans();
			$args[0] = "players";
		}

    $list = array_map(function(BanEntry $entry) : string{
      return $entry->getName();
    }, $list->getEntries());
    sort($list, SORT_STRING);
    $message = implode(", ", $list);

    if($args[0] === "ips"){
			$sender->sendMessage(TextFormat::YELLOW . "There are currently " . count($list) . " IP addresses banned.");
		}else{
			$sender->sendMessage(TextFormat::YELLOW . "There are currently " . count($list) . " Players banned");
		}

		$sender->sendMessage(TextFormat::GREEN . $message);

		return true;
	}
}
