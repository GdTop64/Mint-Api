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
use pocketmine\Player;
use pocketmine\Server;

class NickCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"Cambia El Nick del Usuario.",
			"Uso : /Nick [Nick] [User]"
		);
		$this->setPermission("pocketmine.cmd.nick");
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
		$this->usageMessage;
		return true;
		}
		
		$nick = $args[0];
		$user = $this->getServer()->getPlayer($args[1]);
		if(strlen($nick) > 20){
			$sender->sendMessage(T::RED . "El Nombre es demasiado largo!");
			return true;
			}
	    if(!isset($user)){
				$user = $sender;
		   }
		if(!$user){
			$sender->sendMessage(T::RED . "$nick No ha Sido encontrado.");
			return true;
			}
		$user->setDisplayName($nick);
		$user->setNameTag($nick);
		$sender->sendMessage("El Nick de $user Ha Sido Establecido a \"$nick§r§f\"");
		$user->sendMessage("Tu Nick Ha Sido Establecido a \"$nick§r§f\"");
		}
	}		