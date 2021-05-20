<?php

namespace rtgamingwdt\Override\events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\permission\BanList;
use pocketmine\permission\BanEntry;

class PlayerLoginEvent implements Listener {
  
  public function onJoin(PlayerPreLoginEvent $event) {    
    // Hope this works. If there are bugs with your server that include banning. Remove the plugin from your server. I will try to get it working eventually if I see any bugs.
    $player = $event->getPlayer();
    $name = $player->getName();
    $entry = new BanEntry($name);
    
    if($player->isBanned()) {
      $player->close("", "§8Failed to connect to the server \n§cYou are banned from this server! \n§8Reason: §f" . $entry->getReason() . " \n§8If you have any questions. Please visit our Discord Server at https://discord.gg/A624QQjgJA \n§8Ban ID: §f" . $player->getUniqueId() . "\n§8Make sure not to share your ban ID with anyone other than a offical FortressFusion staff member. Doing so may slow down the process of you getting unbanned.");
    }
  }
}
