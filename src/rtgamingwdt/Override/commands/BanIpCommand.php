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

class BanIpCommand extends PluginCommand {
    
  public function __construct() {
    parent::__construct("ban-ip", Main::getMain());  
    
    $this->setDescription("Prevents the specified IP address from joining the server.");
    $this->setPermission("cmd.banip");
  }
    

  function execute(CommandSender $sender, string $commandLabel, array $args) {
    if($sender instanceof Player) {
      $GUI = new CustomForm(function (Player $player, array $data) {
        $result = $data[0];
        $reason = $data[1];
        
        if($result === null) {						
          $sender->sendMessage(TextFormat::RED . "What IP are you trying to ban?");
          return true;
        }
        
        switch($result) {
          case 0:
            $sender->getServer()->getIPBans()->addBan($result, $reason, null, $sender->getName());
            $sender->sendMessage(TextFormat::GREEN . "The IP " . $result . " has been banned for " . $reason);
            
            foreach($sender->getServer()->getOnlinePlayers() as $player){
              if($player->getAddress() === $result) {
                $player->kick($reason !== "" ? "You have been banned for " . $reason : "No reason specified.");
              }
            }
            $sender->getServer()->getNetwork()->blockAddress($result, -1);
          break;
        }					
      });
      
      $GUI->setTitle(TextFormat::BOLD . TextFormat::RED . "BAN");
      $GUI->setContent(TextFormat::AQUA . "Please type the players ip in that you wish to ban.");
      $GUI->addInput(TextFormat::BLUE . "Player IP");
      $GUI->addInput(TextFormat::RED . "Reason");
      $GUI->sendToPlayer($sender);
    } else {
      $ip = array_shift($args);
      
      $reason = implode(" ", $args);
      
      if(count($args) === 0) {
        $sender->sendMessage(TextFormat::RED . "Please specify the IP you want to ban.");
        return true;
      }
            
      $sender->getServer()->getIPBans()->addBan($ip, $reason, null, $sender->getName());
      
      $sender->sendMessage(TextFormat::GREEN . "The IP " . $ip . " has been banned for " . $reason);
            
      foreach($sender->getServer()->getOnlinePlayers() as $player) {
        if($player->getAddress() === $ip){
          $player->kick($reason !== "" ? "You have been banned for " . $reason : "No reason specified.");
        }
      }
      
      $sender->getServer()->getNetwork()->blockAddress($ip, -1);
    }
  }
}
