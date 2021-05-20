<?php

namespace rtgamingwdt\Override\commands;

use rtgamingwdt\Override\Main;

use jojoe77777\FormAPI\CustomForm;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use function count;
use function implode;

class DefaultGamemodeCommand extends PluginCommand {
  // Might update this command in the future.
	public function __construct() {
		parent::__construct("defaultgamemode", Main::getMain());  
		$this->setDescription("Set the default gamemode");
		$this->setPermission("cmd.defaultgamemode");
	}
    
	function execute(CommandSender $sender, string $commandLabel, array $args) {
		if($sender->isOp()) {
			if(count($args) === 0){
				$sender->sendMessage(TextFormat::RED . "Are you serious? You cannot set a empty gamemode as the default gamemode");
				return true;
			}
      
			$gameMode = $sender->getServer()->getGamemodeFromString($args[0]);
		
			if($gameMode !== -1){
				$sender->getServer()->setConfigInt("gamemode", $gameMode);
				$sender->sendMessage(TextFormat::GREEN . "The default gamemode has been set to " . $sender->getServer()->getGamemodeString($gameMode));
			}else{
				$sender->sendMessage(TextFormat::RED . "Could not find that gamemode. Are you sure it exists?");
			}
		}
	}
}
