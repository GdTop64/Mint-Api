<?php

namespace pocketmine\command\defaults;

use pocketmine\{Server, Player};
use pocketmine\command\CommandSender;

class Register extends VanillaCommand {
	
	private $server
	public function __construct($name){
		parent::__construct($name,"Registrar Tu Cuenta.",null,["r","registrar"]);
		$this->server = $server;
		}
		
		public function getPlugin(){
			return $this->server;
			}
			
		public function execute(CommandSender $p, $label, array $args){

				$name = $p->getName();
        $lowerName = strtolower($name);
        
				if(empty($args)){
					$p->sendMessage($loader->lang($p, "register.auth.err"));
					return true;
					}
					
			                $contra = $args[0];
                $ip = $p->getAddress();

                $this->loader->regs->set($lowerName, [
                    "password" => strtolower($contra),
                    "ip" => $ip
                ]);
                $this->server->regs->save();
                
                $this->loader->auth[$name] = $name;
                $p->sendMessage($this->loader->getMsg($p, "login.first"));
                }
              }
               