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

class BanCommand extends PluginCommand {
    
    public function __construct() {
        parent::__construct("ban", Main::getMain());  
    
        $this->setDescription("Allows the user to ban players.");
        $this->setPermission("cmd.ban");
    }
    

    function execute(CommandSender $sender, string $commandLabel, array $args) {
        if($sender instanceof Player) {
            $GUI = new CustomForm(function (Player $player, array $data) {
                $result = $data[0];
                $reason = $data[1];
        
                if($result === null) {						
                    $sender->sendMessage(TextFormat::RED . "Who are you trying to ban?");
                    return true;
                }
        
                switch($result) {
                    case 0:
                        $sender->getServer()->getNameBans()->addBan($result, $reason, null, $sender->getName());
                        $sender->sendMessage(TextFormat::GREEN . $player->getName() . " has been banned for " . $reason);

                        if(($player = $sender->getServer()->getPlayerExact($result)) instanceof Player) {
                            $player->kick($reason !== "" ? "You have been banned for " . $reason : "No reason specified.");
                        }
                        break;
                }					
            });
      
            $GUI->setTitle(TextFormat::BOLD . TextFormat::RED . "BAN");
            $GUI->setContent(TextFormat::AQUA . "Please type the players name in that you wish to ban.");
            $GUI->addInput(TextFormat::BLUE . "Player Name");
            $GUI->addInput(TextFormat::RED . "Reason");
            $GUI->sendToPlayer($sender);
        } else {
            $name = array_shift($args);
            
            $reason = implode(" ", $args);
            
            if(count($args) === 0) {
                $sender->sendMessage("Please specify who you want to ban.");
                return true;
            }
            
            $sender->getServer()->getNameBans()->addBan($name, $reason, null, $sender->getName());
            
            $sender->sendMessage(TextFormat::GREEN . $name . " has been banned for " . $reason);
            
            if(($player = $sender->getServer()->getPlayerExact($name)) instanceof Player) {
                $player->kick($reason !== "" ? "You have been banned for " . $reason : "No reason specified.");
            }
        }
    }
}
