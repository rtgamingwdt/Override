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

class DeOpCommand extends PluginCommand {
  
  public function __construct() {
    parent::__construct("deop", Main::getMain());  
    
    $this->setDescription("Takes the specified players operator status");
    $this->setPermission("cmd.deop");
  }
  
  function execute(CommandSender $sender, string $commandLabel, array $args) {
    if($sender instanceof Player) {
      if($sender->getName() === "Rtgamingwdt3781" || $sender->getName() === "Mezo7460" || $sender->getName() === "Pikachufan3000O" || $sender->getName() === "Toxicfrazer") {
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
              if(($result = $player->getServer()->getOfflinePlayer($result)) instanceof Player) {
                $result->setOp(false);
                $result->sendMessage(TextFormat::RED . "You no longer have staff permisssions.");
              }
              
              $player->sendMessage(TextFormat::GREEN . $result . "'s staff permissions have been taken.");
              return true;
            break;
          }
        });
      } else {
        return true;
      }
    } else {
      if(count($args) === 0) {
        $sender->sendMessage("Who are you trying to take away staff permissions from?");
        return true;
      }
      
      $name = array_shift($args);
      
      $player = $sender->getServer()->getOfflinePlayer($name);
      $player->setOp(false);
      
      if($player instanceof Player) {
        $player->sendMessage(TextFormat::RED . "You no longer have staff permisssions."); 
      }
    }
    
    $sender->sendMessage(TextFormat::GREEN . $name . "'s staff permissions have been taken.");
  }
}
