<?php

declare(strict_types=1);

namespace afterglow;

use afterglow\task\ctask;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class sca extends PluginBase implements Listener{

	private static $instance;

	public $players = [];
  public $colors;

	public static function getInstance(){
		return self::$instance;
	}

	public function onLoad(){
		self::$instance = $this;
	}

	public function onEnable(){
	   $this->colors = json_decode(file_get_contents($this->getFile()."colors.json"), true);	$this->getServer()->getPluginManager()->registerEvents($this, $this);
	$this->getScheduler()->scheduleRepeatingTask(new ctask($this), 1);
	}

	public function onDisable(){
		foreach($this->players as $player){
     if($player !== null){
			$player->getArmorInventory()->clearAll();
    }
		}
	}

public function onCommand(CommandSender $p, Command $command, string $label, array $args) : bool{
if($command->getName() == "sca") {
 if($p instanceof Player){
if(!isset($this->players[$p->getName()])){
 $this->setArmor($p);
$p->sendMessage("§aColorfulArmor on");
} else {
 $this->removeArmor($p);
$p->sendMessage("§cColorfulArmor off");
}
} else $p->sendMessage("§cRun this command in-game");
}
return true;
}

	public function removeArmor(Player $player) : bool{
		$name = $player->getName();
			unset($this->players[$name]);
			$player->getArmorInventory()->clearAll();
		return true;
	}

	public function setArmor(Player $player) : bool{
		$name = $player->getName();
   if(!isset($this->players[$name])){ 
			$this->players[$name] = $player;
			return true;
		}

		return false;
	}

	public function onQuit(PlayerQuitEvent $event){
		$this->removeArmor($event->getPlayer(), false);
	}

	public function onDeath(PlayerDeathEvent $event){
		$player = $event->getPlayer();
		if(isset($this->players[$player->getName()])){
			$event->setDrops($player->getDrops());
		}
	}

}