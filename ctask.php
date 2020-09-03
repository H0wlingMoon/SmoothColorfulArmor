<?php

declare(strict_types=1);

namespace afterglow\task;

use afterglow\sca;
use pocketmine\item\Armor;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\utils\Color;

class ctask extends Task{

	private $sca;

	private $armors;
	protected $int = 0;

	public function __construct($scainstance){
		$this->sca = $scainstance;
		$this->armors = [
			ItemFactory::get(Item::LEATHER_CAP),
			ItemFactory::get(Item::LEATHER_TUNIC),
			ItemFactory::get(Item::LEATHER_LEGGINGS),
			ItemFactory::get(Item::LEATHER_BOOTS)
		];

	}

	public function onRun(int $currentTick){
		$players = $this->sca->players;
		$armors = array_map(function(Armor $armor) : Armor{
   if($this->int == 32){ //flex
    $this->int = 0;
    }
    $tmp = $this->sca->colors[$this->int];
			$armor->setCustomColor(new Color(intval($tmp["r"]), intval($tmp["g"]), intval($tmp["b"])));
			return $armor;
		}, $this->armors);
  $this->int++;
		foreach($players as $player){		$player->getArmorInventory()->setContents($armors);
		}
	}

}