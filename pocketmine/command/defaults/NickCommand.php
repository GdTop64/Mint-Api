<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author pocketmine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

namespace pocketmine\command\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as T;
use pocketmine\{Server, Player};

class NickCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"Cambia El Nick del Usuario.",
			"Uso : /Nick [Nick] [User]"
		);
		$this->setPermission("pm.cmd.nick");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$sender instanceof Player){
			echo "Usalo dentro del Juego!";
			return true;
			}
			
		if(!$this->testPermission($sender)){
			return true;
			}
		
	if(empty($args)){
		$sender->sendMessage("§b»§f Uso: /$currentAlias [Nick] [User]");
		return true;
		}
		
		$nick = $args[0];
		$target = $sender;
		if(isset($args[1])){
			$target = Server::getinstance()->getPlayer($args[1]);
			
			return true;
			}
			
			if($target instanceof Player or $sender instanceof Player){
				$target->setDisplayName($nick);
				$target->setNameTag($nick);
				if($target === $sender){
				$sender->sendMessage("§b» §fTu Nick Ha Sido Establecido a $nick");
				} else{
					$sender->sendMessage("§b» §fEl Nick de $target Ha Sido Establecido a $nick");
					
$target->sendMessage("§b» §fTu Nick Ha Sido Establecido a $nick");
}


				} else{
					if(!$sender instanceof Player){
					Server::getinstance()->getLogger()->info(T::RED ."Nececitas 2 Argumentos Estando En Consola!");
					return true;
					}
					$sender->sendMessage(T::RED ."Jugador no encontrado.");
					return true;
					}
		
		if(strtolower($nick) === "off"){
			$target->setDisplayName($target->getName());
            $target->setNameTag($target->getName());
            $target->sendMessage(T::AQUA . "Tu Nick ha sido restablecido.");
            return true;
        }
      }
    }