<?php

namespace rtgamingwdt\Override;

use pocketmine\plugin\PluginBase;

class Main extends PluginBase {
  
  private static $main;
    
  public function onEnable() {    
    self::$main = $this;
    $this->getLogger()->info("§eOverride by RT has been §aenabled!");
  }

  public function onDisable() {
    self::$main = $this;
    $this->getLogger()->info("§eOverride by RT has been §cdisabled!");
  }

  public static function getMain(): self {
    return self::$main;
  }
}
