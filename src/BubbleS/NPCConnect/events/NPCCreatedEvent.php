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

namespace BubbleS\NPCConnect\events;

use pocketmine\event\entity\EntityEvent;
use BubbleS\NPCConnect\npc\NPC;

class NPCCreatedEvent extends EntityEvent{
  
  public NPC $npc;
  
  public function __construct(NPC $npc){
    $this->npc = $npc;
  }
  
  public function getNpc():NPC{
    return $this->npc;
  }
}