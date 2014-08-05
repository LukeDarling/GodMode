<?php

namespace LDX\GodMode;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\event\entity\EntityDamageEvent;

class Main extends PluginBase implements Listener {
  public function onLoad() {
    $this->getLogger()->info(TextFormat::YELLOW . "Loading GodMode v2.1 by LDX...");
  }
  public function onEnable() {
    $this->enabled = array();
    $this->getLogger()->info(TextFormat::YELLOW . "Enabling GodMode...");
    $this->getServer()->getPluginManager()->registerEvents($this,$this);
  }
  public function onCommand(CommandSender $issuer,Command $cmd,$label,array $args) {
    if((strtolower($cmd->getName()) == "god") && !(isset($args[0])) && ($issuer instanceof Player) && ($issuer->hasPermission("godmode.toggle") || $issuer->hasPermission("godmode.toggle.self"))) {
      $this->enabled[$issuer->getName()] = !$this->enabled[$issuer->getName()];
      if($this->enabled[$issuer->getName()]) {
        $issuer->sendMessage("God mode enabled!");
      } else {
        $issuer->sendMessage("God mode disabled!");
      }
      return true;
    } else if((strtolower($cmd->getName()) == "god") && isset($args[0]) && ($issuer->hasPermission("godmode.toggle") || $issuer->hasPermission("godmode.toggle.others"))) {
      if($this->getServer()->getPlayer($args[0]) instanceof Player) {
        if(isset($this->enabled[$this->getServer()->getPlayer($args[0])->getName()])) {
          $this->enabled[$this->getServer()->getPlayer($args[0])->getName()] = !$this->enabled[$this->getServer()->getPlayer($args[0])->getName()];
          if($this->enabled[$this->getServer()->getPlayer($args[0])->getName()]) {
            $this->getServer()->getPlayer($args[0])->sendMessage("God mode enabled!");
            $issuer->sendMessage("God mode enabled for " . $this->getServer()->getPlayer($args[0])->getName() . "!");
          } else {
            $this->getServer()->getPlayer($args[0])->sendMessage("God mode disabled!");
            $issuer->sendMessage("God mode disabled for " . $this->getServer()->getPlayer($args[0])->getName() . "!");
          }
        } else {
          $this->enabled[$this->getServer()->getPlayer($args[0])->getName()] = true;
          $this->getServer()->getPlayer($args[0])->sendMessage("God mode enabled!");
          $issuer->sendMessage("God mode enabled for " . $this->getServer()->getPlayer($args[0])->getName() . "!");
        }
      } else {
        $issuer->sendMessage("Player not connected.");
      }
      return true;
    } else {
      return false;
    }
  }
  /**
  * @param EntityDamageEvent $event
  *
  * @priority HIGHEST
  * @ignoreCancelled false
  */
  public function onHurt(EntityDamageEvent $event) {
    $entity = $event->getEntity();
    if($entity instanceof Player && isset($this->enabled[$entity->getName()])) {
      if($this->enabled[$entity->getName()]) {
        $event->setCancelled();
      }
    }
  }
  public function onDisable() {
    $this->getLogger()->info(TextFormat::YELLOW . "Disabling GodMode...");
  }
}
?>
