<?php

/*
* Absolutely do not change the author to share
* Using this virion helps you create NPCs to communicate with players, create quests, etc...

* Copyright © BubbleStone50 | 2024 - 2025
* Github: BubbleStone50
* Facebook: Trần Huy Bảo

* This file for create a NPC Chat with player
*/

declare(strict_types=1);

namespace BubbleS\NPCConnect\npc;

use pocketmine\entity\{Location,EntitySizeInfo,Skin};
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\world\{World,Position};
use pocketmine\player\Player;
use pocketmine\math\Vector3;
use pocketmine\Server;

class NPCChat extends NPC{
  
  public array $chat;
  public bool $math = false;
  public int $closeDelay;
  public string $player;
  public int $timeRunNextStep = 0;
  
  protected function getInitialSizeInfo() : EntitySizeInfo{
  		return new EntitySizeInfo(0.9, 0.3); //TODO: eye height ??
  }
  
  /*
  Get base name entity
  */
  public function getName(): string{
    return "NPCChat";
  }
  
  public function __construct(Location $location, Skin $skin, ?CompoundTag $nbt = null){
        parent::__construct($location,$skin, $nbt);
    $this->setMaxHealth(99999);
    $this->setHealth(99999);
  }
  
  public function initEntity(CompoundTag $nbt):void{
    parent::initEntity($nbt);
  }
  
  public function onUpdate(int $currentTicks = 1): bool{
    $player = Server::getInstance()->getPlayerExact($this->player);
    $location = $player->getLocation();
    $position = $this->getPosition();
    
    $this->onMove($player->getPosition()->add(1,0,1), $position);
    $this->lookAtLocation($location);
   
    if($this->math == false){
    $resultTime = 0;
    foreach($this->chat as $a => $b){
      $resultTime = ($b*20)+ $resultTime;
    }
    $this->closeDelay = $resultTime + 20;
    $this->math = true;
    }
    
    if($this->isOnFire()){
      $this->extinguish();
    }
    
    foreach($this->chat as $a => $b){
      if(($b * 20) == $this->timeRunNextStep){
        $player->sendPopup($a);
      }
    }
    
    if($this->closeDelay <= 0){
      $this->close();
    }
    
    $this->timeRunNextStep +=1;
    $this->closeDelay -=1;
    return true;
  }
}