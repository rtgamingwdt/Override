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
        
                if($data === null) {
                    return true;
                }
                
                if($result === null) {						
                    $sender->sendMessage(TextFormat::RED . "Who are you trying to ban?");
                    return true;
                }
                
                if($reason === null) {
                    $reason = "No reason specified";
                }
        
                switch($result) {
                    case 0:
                        if(preg_match("/^([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])$/", $result)){
                            $this->processIPBan($result, $player, $reason);
                            $player->sendMessage("The IP address " . $value . " has been banned for " . $reason);
                        } else {
                            if(($target = $player->getServer()->getPlayer($result)) instanceof Player) {
                                $this->processIPBan($player->getAddress(), $player, $reason);
                                $player->sendMessage("The player " . $target->getName() . " has been banned for " . $reason);
                            } else {
                                $player->sendMessage("The IP address you entered was invalid or the player that you entered is currently not online.");
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
            
            if(isset($reason) === true && $reason === '') {
                $reason = "No reason specified.";
            }
            
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

    private function processIPBan(string $ip, Player $player, string $reason) : void {
        $player->getServer()->getIPBans()->addBan($ip, $reason, null, $player->getName());
        foreach($player->getServer()->getOnlinePlayers() as $players) {
            if($players->getAddress() === $ip){
                $players->kick($reason);
            }
        }
        
        $player->getServer()->getNetwork()->blockAddress($ip, -1);
    }
}
