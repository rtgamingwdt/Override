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

class SayCommand extends PluginCommand {
    
  public function __construct() {
    parent::__construct("say", Main::getMain());  
    $this->setDescription("Broadcasts the given message.");
    $this->setPermission("cmd.say");
    }
    

    function execute(CommandSender $sender, string $commandLabel, array $args) {
      if($sender instanceof Player) {
        $GUI = new CustomForm(function (Player $player, array $data) {
          $result = $data[0];
          
          if($result === null) {						
            $player->sendMessage(TextFormat::RED . "You cannot broadcast a blank message.");
            return true;
          }
        
          switch($result) {
            case 0:
              $player->getServer()->broadcastMessage(implode(" ", $args));
            break;
          }					
        });
      
        $GUI->setTitle(TextFormat::BOLD . TextFormat::RED . "BAN");
        $GUI->setContent(TextFormat::AQUA . "Please type the message you wish to broadcast to the whole server.");
        $GUI->addInput(TextFormat::BLUE . "Message Here");
        $GUI->sendToPlayer($sender);
      } else {
        
        if(count($args) === 0){
          $sender->sendMessage("You cannot broadcast a blank message.");
          return true;
        }
        
        $sender->getServer()->broadcastMessage(implode(" ", $args));
      }
    }
}
