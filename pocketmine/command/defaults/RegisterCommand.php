<?php

namespace pocketmine\command\defaults;

use pocketmine\{Server, Player};
use pocketmine\command\CommandSender;

class RegisterCommand extends VanillaCommand {
	
	public function __construct($name){
		parent::__construct($name,"Registrar Tu Cuenta.",null,["r","registrar"]);
		}
		
		
		public function execute(CommandSender $p, $label, array $args){

				$name = $p->getName();
        $lowerName = strtolower($name);
        
				if(empty($args)){
					$p->sendMessage("§b» Uso: /$label [Contraseña]");
					return true;
					}
					
			                $contra = $args[0];
                $ip = $p->getAddress();
                

                Server::getinstance()->regs->set($lowerName, [
                    "password" => strtolower($contra),
                    "ip" => $ip
                ]);
                Server::getinstance()->regs->save();
                //🌊 xd yo 
                Server::getinstance()->auth[$name] = $name;
                $p->sendMessage("§b» §fHas Iniciado Sesión Correctamente!. §7Contraseña :§c$contra ");
                }
              }
               