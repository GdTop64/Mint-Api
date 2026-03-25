<?php

namespace pocketmine\level\generator;

use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;
use pocketmine\level\generator\noise\Simplex;
use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\level\generator\biome\Biome;
use pocketmine\tile\Tile;
use pocketmine\tile\Chest;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\item\Item;

class Aether extends Generator {
    private $level, $random;

    public function __construct(array $options = []){}

    public function init(ChunkManager $level, Random $random){
        $this->level = $level;
        $this->random = $random;
    }

    public function getSpawn() : Vector3 {
        return new Vector3(0, 60, 0);
    }

    public function generateChunk($chunkX, $chunkZ){
        $chunk = $this->level->getChunk($chunkX, $chunkZ);
        
        // Sistema de rejilla para asegurar la aparición de islas grandes
        $gridSize = 8; 
        $regionX = floor($chunkX / $gridSize);
        $regionZ = floor($chunkZ / $gridSize);
        $this->random->setSeed(($regionX * 31337) ^ ($regionZ * 13331));
        
        $islandCenterX = ($regionX * $gridSize * 16) + 64; 
        $islandCenterZ = ($regionZ * $gridSize * 16) + 64;
        
        $radius = 55; // Radio fijo grande para visibilidad garantizada
        $surfaceY = 54;

        for($x = 0; $x < 16; $x++){
            for($z = 0; $z < 16; $z++){
                $chunk->setBiomeId($x, $z, Biome::PLAINS);
                $worldX = ($chunkX << 4) + $x;
                $worldZ = ($chunkZ << 4) + $z;
                $distance = sqrt(pow($worldX - $islandCenterX, 2) + pow($worldZ - $islandCenterZ, 2));
                
                if($distance <= $radius){
                    $baseDepth = (int)(sqrt(pow($radius, 2) - pow($distance, 2)) * 0.5);
                    $bottomY = $surfaceY - $baseDepth;

                    for($y = 0; $y < 128; $y++){
                        if($y >= $bottomY && $y <= $surfaceY){
                            if($y === $surfaceY){
                                $chunk->setBlockId($x, $y, $z, Block::GRASS);
                            } elseif ($y > $surfaceY - 3) {
                                $chunk->setBlockId($x, $y, $z, Block::DIRT);
                            } else {
                                $chunk->setBlockId($x, $y, $z, Block::STONE);
                                if(mt_rand(0, 100) < 5){
                                    $ores = [Block::COAL_ORE, Block::IRON_ORE, Block::GOLD_ORE, Block::DIAMOND_ORE];
                                    $chunk->setBlockId($x, $y, $z, $ores[array_rand($ores)]);
                                }
                            }
                        }
                        $chunk->setBlockSkyLight($x, $y, $z, 15);
                    }
                }
            }
        }
    }

    public function populateChunk($chunkX, $chunkZ){
        $chunk = $this->level->getChunk($chunkX, $chunkZ);
        $this->random->setSeed(0xdeadbeef ^ ($chunkX << 8) ^ $chunkZ);

        $maxStructures = 2;
        $placedStructures = 0;
        
        // Generación de Estructuras Especiales (Máximo 2)
        for($attempts = 0; $attempts < 10; $attempts++){
            if($placedStructures >= $maxStructures) break;

            $x = mt_rand(2, 13); $z = mt_rand(2, 13);
            $y = $this->getHighestWorkableBlock($chunk, $x, $z);
            
            if($y > 45 && $chunk->getBlockId($x, $y, $z) === Block::GRASS){
                $wX = ($chunkX << 4) + $x; $wZ = ($chunkZ << 4) + $z;
                $r = mt_rand(0, 100);

                if($r < 50){ 
                    $this->generateHayCube3x3($wX, $y + 1, $wZ);
                    $placedStructures++;
                } else {
                    $this->generateGlassGardenSmall($wX, $y, $wZ);
                    $placedStructures++;
                }
            }
        }

        // Árboles y Decoración (Solo Flor Roja para evitar errores)
        for($i = 0; $i < 10; $i++){
            $tx = mt_rand(0, 15); $tz = mt_rand(0, 15);
            $ty = $this->getHighestWorkableBlock($chunk, $tx, $tz);
            
            if($ty > 40 && $chunk->getBlockId($tx, $ty, $tz) === Block::GRASS){
                $wTX = ($chunkX << 4) + $tx; $wTZ = ($chunkZ << 4) + $tz;
                $rDeco = mt_rand(0, 100);

                if($rDeco < 10){
                    $this->level->setBlockIdAt($wTX, $ty + 1, $wTZ, Block::RED_FLOWER);
                } elseif($rDeco < 30){
                    $this->generateGlowingBush($wTX, $ty + 1, $wTZ);
                } elseif($rDeco < 50){
                    mt_rand(0, 1) === 0 ? 
                        $this->generateLargeCustomTree($wTX, $ty + 1, $wTZ) : 
                        $this->generateCurvedTree($wTX, $ty + 1, $wTZ);
                }
            }
        }
    }

    private function generateHayCube3x3($x, $y, $z){
        for($iy = 0; $iy < 3; $iy++) {
            for($ix = -1; $ix <= 1; $ix++) {
                for($iz = -1; $iz <= 1; $iz++) {
                    $this->level->setBlockIdAt($x + $ix, $y + $iy, $z + $iz, 170);
                }
            }
        }
        $this->createLootChest($x, $y + 3, $z);
    }

    private function generateGlassGardenSmall($x, $y, $z){
        $depthZ = mt_rand(1, 2); // Determina si es 3x3 o 3x4
        for($ix = -1; $ix <= 1; $ix++){
            for($iz = -$depthZ; $iz <= 1; $iz++){
                $this->level->setBlockIdAt($x + $ix, $y, $z + $iz, Block::GLASS);
                $this->level->setBlockIdAt($x + $ix, $y - 2, $z + $iz, Block::GLOWSTONE);
                $this->level->setBlockIdAt($x + $ix, $y - 1, $z + $iz, Block::STILL_WATER);
            }
        }
    }

    private function generateGlowingBush($x, $y, $z){
        $this->level->setBlockIdAt($x, $y, $z, 124); 
        $this->level->setBlockIdAt($x, $y + 1, $z, Block::LEAVES);
        $this->level->setBlockIdAt($x + 1, $y, $z, Block::LEAVES);
        $this->level->setBlockIdAt($x - 1, $y, $z, Block::LEAVES);
        $this->level->setBlockIdAt($x, $y, $z + 1, Block::LEAVES);
        $this->level->setBlockIdAt($x, $y, $z - 1, Block::LEAVES);
    }

    private function createLootChest($x, $y, $z){
        $this->level->setBlockIdAt($x, $y, $z, Block::CHEST);
        $nbt = new CompoundTag("", [
            new ListTag("Items", []),
            new StringTag("id", Tile::CHEST),
            new IntTag("x", $x), new IntTag("y", $y), new IntTag("z", $z)
        ]);
        $tile = Tile::createTile(Tile::CHEST, $this->level->getChunk($x >> 4, $z >> 4), $nbt);
        if($tile instanceof Chest){
            $loot = [
                [Item::DIAMOND, 0, 6, 100], [Item::GOLD_INGOT, 0, 4, 100],
                [Item::IRON_INGOT, 0, 12, 100], [Item::POTION, 0, 1, 40],
                [Item::OBSIDIAN, 0, 7, 100], [Item::IRON_SWORD, 0, 1, 36],
                [Item::GOLDEN_APPLE, 0, 12, 30], [Item::GOLDEN_APPLE, 1, 1, 15]
            ];
            foreach($loot as $d) if(mt_rand(0, 100) <= $d[3]) $tile->getInventory()->addItem(Item::get($d[0], $d[1], mt_rand(1, $d[2])));
        }
    }

    private function generateLargeCustomTree($x, $y, $z){
        $h = mt_rand(5, 7);
        for($i = 0; $i < $h; $i++) $this->level->setBlockIdAt($x, $y + $i, $z, Block::LOG);
        for($iy = $h - 2; $iy <= $h + 1; $iy++){
            $r = ($iy >= $h) ? 1 : 2;
            for($ix = -$r; $ix <= $r; $ix++) {
                for($iz = -$r; $iz <= $r; $iz++) {
                    if($this->level->getBlockIdAt($x + $ix, $y + $iy, $z + $iz) === 0) 
                        $this->level->setBlockIdAt($x + $ix, $y + $iy, $z + $iz, Block::LEAVES);
                }
            }
        }
    }

    private function generateCurvedTree($x, $y, $z){
        $h = mt_rand(5, 6); $off = 0;
        for($i = 0; $i < $h; $i++){
            $this->level->setBlockIdAt($x + $off, $y + $i, $z, Block::LOG);
            if($i > 1) $off++;
        }
        for($ix = -1; $ix <= 1; $ix++) for($iz = -1; $iz <= 1; $iz++) 
            $this->level->setBlockIdAt($x + $off + $ix, $y + $h, $z + $iz, Block::LEAVES);
    }

    private function getHighestWorkableBlock($chunk, $x, $z){
        for($y = 100; $y > 0; $y--) if($chunk->getBlockId($x, $y, $z) !== Block::AIR) return $y;
        return -1;
    }

    public function getName(){ return "aether"; }
    public function getSettings(){ return []; }
}
