<?php

namespace rtgamingwdt\Override\commands;

use rtgamingwdt\Override\Main;

use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat;

class BanCommand extends PluginCommand {
    
  public function __construct() {
    parent::__construct("staffmode", Main::getMain());  
    
    $this->setDescription("Allows the user to ban players.");
    $this->setPermission("cmd.ban");
  }
    
  function execute(CommandSender $sender, string $commandLabel, array $args) {
    if($sender instanceof Player) {
      $GUI = new CustomForm(function (Player $player, array $data) {
        $result = $data[0];		
        
        if($result === null) {						
        //We do nothing.
        }
        
        switch($result) {							
          case 0:																
            $sender->getServer()->dispatchCommand($sender, trim(implode(" ", ["ban ".$result.""])));														
          break;																						
        }					
      });
      
      $GUI->setTitle(TextFormat::BOLD . TextFormat::RED . "BAN");
      $GUI->setContent(TextFormat::AQUA . "Please type the players name in that you wish to ban.");
      $GUI->addInput(TextFormat::BLUE . "Type Player Name Here");
      $GUI->sendToPlayer($sender);
    } else {
      $name = array_shift($args);
      
      $reason = implode(" ", $args);
      
      $sender->getServer()->getNameBans()->addBan($name, $reason, null, $sender->getName());
      
      if(($player = $sender->getServer()->getPlayerExact($name)) instanceof Player) {
        $player->kick($reason !== "" ? "You have been banned for " . $reason : "Banned by admin.");
        //NOTE I AM NOT DONE CODING YET. I HAVE TO GO TO A ZOOM MEETING...
      }
    }
  }
}
