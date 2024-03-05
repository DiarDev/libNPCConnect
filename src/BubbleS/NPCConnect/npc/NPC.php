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

use pocketmine\entity\{Location,Human};
use pocketmine\world\{World,Position};
use pocketmine\player\Player;
use pocketmine\math\Vector3;

class NPC extends Human{
  
  public function getName(): string{
    return "NPC";
  }
  
  protected function onMove(Vector3 $to, Vector3 $from) : void {
  		if (!$this->isAlive()) {
  			return;
  		}
  		$dx = $to->getX() - $from->getX();
  		$dy = $to->getY() - ($this->motion->y + $from->y);
  		$dz = $to->getZ() - $from->getZ();
  
  		$diff = abs($dx) + abs($dy) + abs($dz);
  
  		if ($diff == 0) {
  			return;
  		}
  		$x = $this->getMovementSpeed() * $dx / $diff;
  		$y = $this->getMovementSpeed() * $dy / $diff;
  		$z = $this->getMovementSpeed() * $dz / $diff;
  		$vector3 = new Vector3($x, $y, $z);
  		$this->move($x, $y, $z);
  }
  
  protected function lookAtLocation(Location $location): array{
          $angle = atan2($location->z - $this->getLocation()->z, $location->x - $this->getLocation()->x);
          $yaw = (($angle * 180) / M_PI) - 90;
          $angle = atan2((new Vector2($this->getLocation()->x, $this->getLocation()->z))->distance(new Vector2($location->x, $location->z)), $location->y - $this->getLocation()->y);
          $pitch = (($angle * 180) / M_PI) - 90;
  
          $this->setRotation($yaw, $pitch);
  
          return [$yaw, $pitch];
  }
}