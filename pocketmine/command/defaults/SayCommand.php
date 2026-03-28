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

use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\TranslationContainer;
use pocketmine\{Player, Server};
use pocketmine\utils\TextFormat;

class SayCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%pocketmine.command.say.description",
			"%commands.say.usage",
			["broadcast", "announce","send"]
		);
		$this->setPermission("pm.cmd.say");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(empty($args) or $args < 2){
			$sender->sendMessage("§b» §fUso: /$currentAlias [tip|msg|pop] [*|(Player)] [Msg]");

			return false;
		}

	   $via = $args[0];
	   $dir = $args[1];
	$m = array_slice($args, 2);
	   $msg = implode(" ", $m);
	   $plc = ["\n","{o}","{r}","{rp}"];
	   $fun = [PHP_EOL,
 count(Server::getinstance()->getOnlinePlayers()), 
mt_rand(1,20),
 array_rand(Server::getinstance()->getOnlinePlayers())
];
$msg = str_replace($plc, $fun, $msg);
	switch(strtolower($args[0])){
		case "msg":
		if($args[1] === "*"){
			Server::getinstance()->broadcastMessage($msg);
			}elseif ($dir !== "*"){
				
				$p = $sender->getServer()->getPlayer($dir);
				if($p instanceof Player){
				$p->sendMessage($msg);
				}else{
 $sender->sendMessage(TextFormat::RED. "User Not Found");
		}
	}
		break;
		case "pop":
		case "popup":
		if($args[1] === "*"){
			Server::getinstance()->broadcastPopup($msg);
			}elseif ($dir !== "*"){
				
				$p = $sender->getServer()->getPlayer($dir);
				if($p instanceof Player){
				$p->sendPopup($msg);
				}else{
 $sender->sendMessage(TextFormat::RED. "User Not Found");
		}
	}
		break;
		case "tip":
		if($args[1] === "*"){
			Server::getinstance()->broadcastTip($msg);
			}elseif ($dir !== "*"){
				
				$p = $sender->getServer()->getPlayer($dir);
				if($p instanceof Player){
				$p->sendTip($msg);
				}else{
 $sender->sendMessage(TextFormat::RED. "User Not Found");
		}
	}
		break;
		}
		return true;
	}
		
}
