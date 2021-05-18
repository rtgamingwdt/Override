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
use function array_shift;
use function count;
use function implode;
use function preg_match;

class BanIpCommand extends PluginCommand {
    
    public function __construct() {
        parent::__construct("ban-ip", Main::getMain());  
        $this->setDescription("Prevents the specified IP address from using this server");
        $this->setPermission("cmd.banip");
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
                        if(preg_match("/^([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])$/", $result)){
                            $this->processIPBan($result, $sender, $reason);
                            $sender->sendMessage("The IP address " . $value . " has been banned for " . $reason);
                        } else {
                            if(($player = $sender->getServer()->getPlayer($result)) instanceof Player) {
                                $this->processIPBan($player->getAddress(), $sender, $reason);
                                $sender->sendMessage("The player " . $player->getName() . " has been banned for " . $reason);
                            } else {
                                $sender->sendMessage("The IP address you entered was invalid or the player that you entered is currently not online.");
                                return false;
                            }
                        }        
                        return true;
                    break;
                }					
            });
            
            $GUI->setTitle(TextFormat::BOLD . TextFormat::RED . "BAN");
            $GUI->setContent(TextFormat::AQUA . "Please type the players IP address in that you wish to ban.");
            $GUI->addInput(TextFormat::BLUE . "Player IP");
            $GUI->addInput(TextFormat::RED . "Reason");
            $GUI->sendToPlayer($sender);
        } else {
            if(count($args) === 0){
                $sender->sendMessage("Who are you trying to ban?");
                return true;
            }
          
            $value = array_shift($args);
            $reason = implode(" ", $args);
          
            if(preg_match("/^([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])$/", $value)){
                $this->processIPBan($value, $sender, $reason);
                $sender->sendMessage("The IP address " . $value . " has been banned for " . $reason);
            } else {
                if(($player = $sender->getServer()->getPlayer($value)) instanceof Player) {
                    $this->processIPBan($player->getAddress(), $sender, $reason);
                    $sender->sendMessage("The player " . $player->getName() . " has been banned for " . $reason);
                } else {
                    $sender->sendMessage("The IP address you entered was invalid or the player that you entered is currently not online.");
                    return false;
                }
            }        
            return true;
        }
    }

    private function processIPBan(string $ip, CommandSender $sender, string $reason) : void {
        $sender->getServer()->getIPBans()->addBan($ip, $reason, null, $sender->getName());
        foreach($sender->getServer()->getOnlinePlayers() as $player) {
            if($player->getAddress() === $ip){
                $player->kick($reason !== "" ? $reason : "Reason unspecified.");
            }
        }
        
        $sender->getServer()->getNetwork()->blockAddress($ip, -1);
    }
}
