<?php

namespace milk\pureentities; // Asegúrate de que el namespace sea el correcto

use pocketmine\scheduler\Task;
use pocketmine\Player;
use milk\pureentities\PureEntities;
use pocketmine\math\Vector3;

class AutoSpawnTask extends Task {

    private $plugin;

    public function __construct(PureEntities $plugin) {
        $this->plugin = $plugin;
    }

    public function onRun($currentTick) {
        foreach ($this->plugin->getServer()->getLevels() as $level) {
            $worldName = $level->getName();
            
            // 1. Intentamos obtener los límites del pocketmine.yml
            // Usamos el config del servidor para leer la ruta worlds.nombre.spawn-limits
            $monsterLimit = $this->plugin->getServer()->getConfigString("worlds.$worldName.spawn-limits.monsters", null);
            $animalLimit = $this->plugin->getServer()->getConfigString("worlds.$worldName.spawn-limits.animals", null);

            // 2. Si el límite es 0 o no está definido, no spawneamos nada aquí
            if ($monsterLimit === 0 || $monsterLimit === "0" || $monsterLimit === null) {
                continue;
            }

            // 3. Lógica simple de Spawning
            $players = $level->getPlayers();
            if (count($players) > 0) {
                foreach ($players as $player) {
                    if (mt_rand(1, 100) <= 5) { // 5% de probabilidad por tick de intentar un spawn
                        $this->trySpawn($player, $level);
                    }
                }
            }
        }
    }

    private function trySpawn(Player $player, $level) {
        // Generamos una posición aleatoria cerca del jugador (entre 16 y 32 bloques)
        $radius = mt_rand(16, 32);
        $ang = mt_rand(0, 360);
        $x = $player->x + ($radius * cos($ang));
        $z = $player->z + ($radius * sin($ang));
        $y = $level->getHighestBlockAt((int)$x, (int)$z) + 1;

        $pos = new Vector3($x, $y, $z);
        
        // Aquí decidimos si spawnear animal o monstruo
        $type = (mt_rand(1, 10) > 7) ? "Cow" : "Zombie"; // Ejemplo simple
        
        // Usamos el método create que tiene PureEntities.php
        $entity = PureEntities::create($type, $pos, $level);
        if ($entity !== null) {
            $entity->spawnToAll();
        }
    }
}
