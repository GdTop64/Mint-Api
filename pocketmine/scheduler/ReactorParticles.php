<?php

namespace pocketmine\scheduler;

use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\Server;
use pocketmine\nbt\tag\FloatTag;

class ReactorParticles extends Task {
    private $level, $x, $y, $z, $seconds = 0;

    public function __construct($level, $x, $y, $z){
        $this->level = $level;
        $this->x = $x; $this->y = $y; $this->z = $z;
    }

    public function onRun($currentTick){
        $this->seconds++;
        $pos = new Vector3($this->x + 0.5, $this->y + 1, $this->z + 0.5);
        
        // 1. PARTICULAS
        for($i = 0; $i < 5; $i++){
            $this->level->addParticle(new \pocketmine\level\particle\FlameParticle($pos->add(mt_rand(-2, 2), mt_rand(0, 2), mt_rand(-2, 2))));
        }

        // 2. OLEADAS (Cada 60 segundos)
        if($this->seconds % 60 === 0 && $this->seconds < 420){
            $this->spawnWave();
        }

        // 3. GRAN LOOT (Después de 3 min)
        if($this->seconds > 5 && $this->seconds % 2 === 0){
            $this->dropBigLoot($pos);
        }

        if($this->seconds >= 420){
            \pocketmine\Server::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }
    }

    private function spawnWave(){
        for($i = 0; $i < 3; $i++){
            $sX = $this->x + mt_rand(-4, 4);
            $sZ = $this->z + mt_rand(-4, 4);
            $sY = $this->y + 1;

            // Construcción manual de NBT compatible con tu versión
            $nbt = new CompoundTag("", [
                "Pos" => new ListTag("Pos", [
                    new DoubleTag("", $sX),
                    new DoubleTag("", $sY),
                    new DoubleTag("", $sZ)
                ]),
                "Motion" => new ListTag("Motion", [
                    new DoubleTag("", 0),
                    new DoubleTag("", 0),
                    new DoubleTag("", 0)
                ]),
                "Rotation" => new ListTag("Rotation", [
                    new FloatTag("", mt_rand(0, 360)),
                    new FloatTag("", 0)
                ]),
            ]);

            // Usamos el ID 36 que ya confirmamos que funciona en NetherReactor.php
            $en = Entity::createEntity(36, $this->level->getChunk($sX >> 4, $sZ >> 4), $nbt);
            
            if($en !== null){
                $en->spawnToAll();
            }
        }
        $this->getServer()->broadcastMessage("§6[Reactor] §e¡Han llegado refuerzos del Nether!");
    }

    private function dropBigLoot(Vector3 $pos){
        $item = \pocketmine\item\Item::get(\pocketmine\item\Item::DIAMOND, 0, 1);
        if(mt_rand(1,2) == 1) $item = \pocketmine\item\Item::get(\pocketmine\item\Item::GOLD_INGOT, 0, 2);
        $this->level->dropItem($pos, $item, new Vector3(mt_rand(-5, 5) / 20, 0.4, mt_rand(-5, 5) / 20));
    }
}