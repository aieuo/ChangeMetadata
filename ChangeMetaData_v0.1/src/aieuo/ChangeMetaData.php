<?php
namespace aieuo;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerInteractEvent;

class ChangeMetaData extends PluginBase implements Listener{

       public function onEnable(){
            $this->getServer()->getPluginManager()->registerEvents($this,$this);
		    if(!file_exists($this->getDataFolder())){
		        mkdir($this->getDataFolder(), 0744, true);
		    }
    	    $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		    if($config->exists("id")){
				$this->touchid = $config->get("id");
		    }else{
				$config->set("id",280);
				$config->save();
				$this->touchid = 280;
		    }
       }

	public function onTouch(PlayerInteractEvent $event){
		$player = $event->getPlayer();
		$id = $player->getInventory()->getItemInHand()->getId();
		$block = $event->getBlock();
		$meta = $block->getDamage();
		if($id == $this->touchid){
		    if($player->isSneaking()){
				$nmeta = $meta -1;
		    	if($nmeta == -1)$nmeta = 15;
				$block->setDamage($nmeta);
		    }else{
				$nmeta = $meta +1;
		    	if($nmeta == 16)$nmeta = 0;
				$block->setDamage($nmeta);
		    }
		    $player->sendTip("ID =  ".$block->getId().":".$nmeta);
		}
	}

}