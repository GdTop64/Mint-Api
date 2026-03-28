<?php
namespace pocketmine\scheduler;

use pocketmine\block\Block;
use pocketmine\level\Level;
use pocketmine\item\Item;
use pocketmine\math\Vector3;

class ReactorTask extends Task {
    private $level, $x, $y, $z;

    public function __construct(Level $level, $x, $y, $z){
        $this->level = $level;
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }

    public function onRun($currentTick){
        $pos = new Vector3($this->x + 0.5, $this->y + 1.5, $this->z + 0.5);
        for($i = 0; $i < 20; $i++){
            $diamond = Item::get(Item::DIAMOND, 0, 1);
            $gold = Item::get(Item::GOLD_BLOCK, 0, 1);
            $this->level->dropItem($pos, $diamond, new Vector3(mt_rand(-4, 4) / 10, 0.8, mt_rand(-4, 4) / 10));
            $this->level->dropItem($pos, $gold, new Vector3(mt_rand(-4, 4) / 10, 0.6, mt_rand(-4, 4) / 10));
        }
        $this->level->setBlock(new Vector3($this->x, $this->y, $this->z), Block::get(49), true, true);
        $this->level->addChatRawMessage("§b[Nether Reactor] §f¡Núcleo agotado!");
    }
}
