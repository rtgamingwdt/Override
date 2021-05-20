<?php

namespace rtgamingwdt\Override\commands;

use jojoe77777\FormAPI\CustomForm;

use rtgamingwdt\Override\Main;

use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\lang\TranslationContainer;
use pocketmine\level\Level;
use pocketmine\utils\TextFormat;
use function count;
use function implode;

class DifficultyCommand extends PluginCommand {
  
  public function __construct() {
    parent::__construct("difficulty", Main::getMain());
    $this->setDescription("Sets the game difficulty");
    $this->setPermission("cmd.difficulty");
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
            $player->sendMessage(TextFormat::RED . "Difficulty can not be empty");
            return true;
          }
          
          switch($result) {
            case 0:
              $difficulty = Level::getDifficultyFromString($result);
              
              if($difficulty !== -1){
                $player->getServer()->setConfigInt("difficulty", $difficulty);
                
                foreach($player->getServer()->getLevels() as $level){
                  $level->setDifficulty($difficulty);
                }
                
                $player->sendMessage(TextFormat::GREEN . "Difficulty has been set to " . TextFormat::YELLOW . $difficulty);
                return true;
              }else{
                $player->sendMessage(TextFormat::RED . "An error has occured. Make sure you typed in the difficulty correctly. Any typos could result into this error.");
                return true;
              }
              break;
          }
        });
      } else {
        return true;
      }
    } else {
      $result = implode(" ", $args);
      $difficulty = Level::getDifficultyFromString($result);
      
      if($difficulty !== -1) {
        $sender->getServer()->setConfigInt("difficulty", $difficulty);
        
        foreach($sender->getServer()->getLevels() as $level) {
          $level->setDifficulty($difficulty);
        }
        
        $sender->sendMessage(TextFormat::GREEN . "Difficulty has been set to " . TextFormat::YELLOW . $difficulty);
        return true;
      } else {
        $sender->sendMessage(TextFormat::RED . "An error has occured. Make sure you typed in the difficulty correctly. Any typos could result into this error.");
        return true;
      }
    }
  }
}
