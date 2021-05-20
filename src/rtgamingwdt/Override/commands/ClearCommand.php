<?php

namespace rtgamingwdt\Override\commands;

use rtgamingwdt\Override\Main;

use jojoe77777\FormAPI\CustomForm;

use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use function count;
use function implode;

class ClearCommand extends PluginCommand {
  
  public function __construct() {
    parent::__construct("clear", Main::getMain());  
    
    $this->setDescription("Clears a players inventory.");
    $this->setPermission("cmd.clear");
  }
  
  function execute(CommandSender $sender, string $commandLabel, array $args) {
    if($sender instanceof Player) {
      if($sender->isOp()) {
        $GUI = new CustomForm(function (Player $player, array $data) {
          $result = $data[0];
          
          if($data === null) {
            return true;
          }
          
          if($result === null) {
            $result = $player->getName();
          }
          
          switch($result) {
            case 0:
              if(($result = $player->getServer()->getPlayerExact($result)) instanceof Player) {
                $result->getInventory()->clearAll();
                return true;
              }
          }
        });
      }
    } else {
      if(count($args) === 0) {
        $sender->sendMessage("Who's inventory are you trying to clear?");
        return true;
      }
      
      $name = array_shift($args);
      
      if(($name = $player->getServer()->getPlayerExact($name)) instanceof Player) {
        $name->getInventory()->clearAll();
        return true;
      } else {
        $sender->sendMessage("Could not find player " . $name . ". Are you sure they are online?");
        return true;
      }
    }
  }
}
