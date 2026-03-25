<?php

namespace pocketmine\command\defaults;

use pocketmine\Server, Player;
use pocketmine\command\CommandSender;
class Arf extends VanillaCommand {

public function __construct($name){
	parent::__construct($name, "","",[""]);
	}
	
public function execute(CommandSender $s, $label, array $args){
	
	if($s instanceof Player){
		$s->sendMessage("no");
		return true;
		}
	
	if(empty($args)){
		$s->sendMessage("Uso: refactor [folder] [txt] [replace] ");
		return true;
		}
		$folder = $args[0];
		$txt = $args[1];
		$replace = $args[2];
		if(isset($folder)){
			Server::getinstance()->getRefactor()->refactorial($folder, $txt, $replace);
  }
    }
}