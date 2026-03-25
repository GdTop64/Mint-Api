<?php

namespace pocketmine\entity;

use pocketmine\Player;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ByteArrayTag;
use pocketmine\nbt\tag\StringTag;

class FollowingNPC extends Human implements NPC {

    protected $owner = null;

    public function setOwner(Player $player) {
        $this->owner = $player;
    }

    public function onUpdate($currentTick){
        $parent = parent::onUpdate($currentTick);
        if($this->owner !== null && $this->owner->isOnline()){
            $dist = $this->distance($this->owner);
            if($dist > 3) $this->follow($this->owner);
        }
        return $parent;
    }

    public static function createBaseNBT(Vector3 $pos, Vector3 $motion = null, $yaw = 0.0, $pitch = 0.0, $skinName = "Standard_Custom", $skinData = ""){
        // Usamos Entity para saltarnos la validación inicial de Human
        $nbt = Entity::createBaseNBT($pos, $motion, $yaw, $pitch);
        
        // Estructura exacta que pide tu Human.php en la línea 660
        $nbt->Skin = new CompoundTag("Skin", [
            "Data" => new ByteArrayTag("Data", $skinData),
            "Name" => new StringTag("Name", $skinName)
        ]);
        
        return $nbt;
    }

    private function follow(Player $target){
        $x = $target->x - $this->x;
        $z = $target->z - $this->z;
        $diff = abs($x) + abs($z);
        if($diff > 0){
            $this->motionX = 0.15 * ($x / $diff);
            $this->motionZ = 0.15 * ($z / $diff);
        }
        // Saltar si hay un bloque enfrente
        if($this->level->getBlock($this->add($this->getDirectionVector()))->isSolid()) $this->jump();

        $this->yaw = rad2deg(atan2(-$x, $z));
        $this->move($this->motionX, $this->motionY, $this->motionZ);
        $this->updateMovement();
    }
}
