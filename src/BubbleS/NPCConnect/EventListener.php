<?php

/*
* Absolutely do not change the author to share
* Using this library helps you create NPCs to communicate with players, create quests, etc...

* Copyright © BubbleStone50 | 2024 - 2025
* Github: BubbleStone50
* Facebook: Trần Huy Bảo

* This file is used to test and run event
*/

declare(strict_types=1);

namespace BubbleS\NPCConnect;

use BubbleS\NPCConnect\NPCChat;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener as L;
use pocketmine\plugin\Plugin;

class EventListener implements L{
  
  public array $class = [NPCChat::class];
  public Plugin $plugin;
  
  public function __construct(Plugin $plugin){
    $this->plugin = $plugin;
  }
  public function onDamage(EntityDamageByEntityEvent $ev){
    $damager = $ev->getDamager();
    $entity = $ev->getEntity();
    foreach($this->class as $class){
      if($entity instanceof $class){
        $ev->cancel();
      }
    }
  }
}