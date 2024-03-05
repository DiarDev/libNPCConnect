<?php

/*
* Absolutely do not change the author to share
* Using this library helps you create NPCs to communicate with players, create quests, etc...

* Copyright © BubbleStone50 | 2024 - 2025
* Github: BubbleStone50
* Facebook: Trần Huy Bảo

* published 2024 *
*/

declare(strict_types=1);

namespace BubbleS\NPCConnect;

use pocketmine\player\Player;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\entity\Human;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase as PB;
use pocketmine\entity\Location;
use pocketmine\world\{World,WorldManager};
use pocketmine\entity\Skin;
use BubbleS\NPCConnect\npc\{NPC,NPCChat};
use BubbleS\NPCConnect\events\NPCCreatedEvent;

class Runner{
  
  private static bool $isRegister = false;
  
  public static function init(PB $plugin): void{
    if(!self::$isRegister){
      self::$isRegister = true;
      $plugin->getServer()->getPluginManager()->registerEvents(new EventListener($plugin),$plugin);
      foreach(self::getAllNpc() as $entity => $entityClass){
        EntityFactory::getInstance()->register($entityClass, function (World $world, CompoundTag $nbt): NPC {
            return new $entityClass(EntityDataHelper::parseLocation($nbt, $world), Human::parseSkinNBT($nbt), $nbt);
        }, [$entity]);
      }
    }
  }
  
  public static function getAllNpc(): array{
    return ["NPCChat"=>NPCChat::class];
  }
  
  public static function removeAllNpc(PB $plugin): void{
    $world = $plugin->getServer()->getWorldManager()->getWorlds();
    foreach($world as $worlds){
      foreach($worlds->getEntities() as $entities){
        if($entities instanceof NPC){
          $entities->close();
        }
      }
    }
  }
  
  public function getSkin($nameFile){
        $file = glob($this->getDataFolder() . DIRECTORY_SEPARATOR . "*.png");
        foreach($file as $file){
            $path = $this->getDataFolder() . DIRECTORY_SEPARATOR . "$nameFile.png";
            $img = @imagecreatefrompng($path);
            $skinbytes = "";
            $s = (int)@getimagesize($path)[1];
            for($y = 0; $y < $s; $y++){
                for($x = 0; $x < 64; $x++){
                    $colorat = @imagecolorat($img, $x, $y);
                    $a = ((~((int)($colorat >> 24))) << 1) & 0xff;
                    $r = ($colorat >> 16) & 0xff;
                    $g = ($colorat >> 8) & 0xff;
                    $b = $colorat & 0xff;
                    $skinbytes .= chr($r) . chr($g) . chr($b) . chr($a);
                }
            }
            @imagedestroy($img);
            return new Skin("Standard_CustomSlim", $skinbytes, "", "geometry.$nameFile", $nameFile."geometry.json");           
        }
    }
  
  public static function createNPCChat(Player $player,string $name,array $text = ["Example"=>0], string $skin = null, Player|string $type_spawn = ''): NPCChat{
    if($skin == null){
      $npc = new NPCChat($player->getLocation(),$player->getSkin(), null);
    }
    if($skin != null){
      if(file_exists($this->getDataFolder().DIRECTORY_SEPARATOR."{$skin}.png")){
        $npc = new NPCChat($player->getLocation(),$this->getSkin($skin), null);
      }else{
        $skin = null;
        $npc = new NPCChat($player->getLocation(),$player->getSkin(), null);
      }
    }
    $npc->player = $player->getName();
    $npc->chat = $text;
    $npc->setNameTagAlwaysVisible();
    $npc->setNameTag($name);
    if($type_spawn == '')$npc->spawnToAll();
    if($type_spawn instanceof Player || $type_spawn != '')$npc->spawnTo($type_spawn);
    $player->sendMessage("Da tao npc");
    (new NPCCreatedEvent($npc))->call();
    return $npc;
  }
}