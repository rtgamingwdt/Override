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

class BanCommand extends PluginCommand {
    
    public function __construct() {
        parent::__construct("ban", Main::getMain());  
    
        $this->setDescription("Prevents the specified player from joining this server");
        $this->setPermission("cmd.ban");
    }
    

    function execute(CommandSender $sender, string $commandLabel, array $args) {
        if($sender instanceof Player) {
            if($sender->isOp()) {
                $GUI = new CustomForm(function (Player $player, array $data) {
                    $result = $data[0];
                    $reason = $data[1];
                    
                    if($data === null) {
                        return true; // I don't know if I have to return true or false here xD. I believe I just have to return true.
                    }
                
                    if($result === null) {						
                        $sender->sendMessage(TextFormat::RED . "Who are you trying to ban?");
                        return true;
                    }
                
                    if($reason === null) {
                        $reason = "No reason specified.";
                    }
        
                    switch($result) {
                        case 0:
                            $player->getServer()->getNameBans()->addBan($result, $reason, null, $player->getName());
                            $player->sendMessage(TextFormat::GREEN . $result . " has been banned for " . $reason);

                            if(($result = $player->getServer()->getPlayerExact($result)) instanceof Player) {
                                $result->kick(TextFormat::DARK_RED . "You have been banned for " . $reason);
                            }
                        break;
                    }					
                });
      
                $GUI->setTitle(TextFormat::BOLD . TextFormat::RED . "BAN");
                $GUI->addInput(TextFormat::BLUE . "Player Name");
                $GUI->addInput(TextFormat::RED . "Reason");
                $GUI->sendToPlayer($sender);   
            }
        } else {
            if(count($args) === 0){
                $sender->sendMessage("Who are you trying to ban?");
                return true;
            }

            $name = array_shift($args);
            $reason = implode(" ", $args);
            
            if(isset($reason) === true && $reason === '') {
                $reason = "No reason specified.";
            }

            $sender->getServer()->getNameBans()->addBan($name, $reason, null, $sender->getName());
            $sender->sendMessage($name . " has been banned for " . $reason);

            if(($player = $sender->getServer()->getPlayerExact($name)) instanceof Player){
                $player->kick("You have been banned. Reason: " . $reason);
            }
        }
    }
}
