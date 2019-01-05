<?php
namespace aieuo;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerInteractEvent;

class ChangeMetaData extends PluginBase implements Listener {

	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
		if(!file_exists($this->getDataFolder())) {
			mkdir($this->getDataFolder(), 0744, true);
		}
		$config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		if(!$config->exists("id")) {
			$config->set("id",280);
			$config->save();
		}
		$this->touchId = $config->get("id");
	}

	public function onTouch(PlayerInteractEvent $event) {
		$player = $event->getPlayer();
		$id = $player->getInventory()->getItemInHand()->getId();
		$block = $event->getBlock();
		$meta = $block->getDamage();
		if($id === $this->touchId) {
			if($player->isSneaking()) {
				$meta --;
				if($meta == -1) $meta = 15;
				$block->setDamage($meta);
			} else {
				$meta ++;
				if($meta == 16) $meta = 0;
				$block->setDamage($meta);
			}
			$player->sendTip("ID = ".$block->getId().":".$meta);
		}
	}

}
