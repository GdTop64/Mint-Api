<?php

namespace pocketmine\command\defaults;

use pocketmine\{Server, Player};
use pocketmine\command\CommandSender;

class LoginCommand extends VanillaCommand {
	
	public function __construct($name){
		parent::__construct($name,"Registrar Tu Cuenta.",null,["l","logar"]);
		}
		
		public function execute(CommandSender $s, $label, array $args){
                if (count($args) == 0) {
                	$p->sendMessage("§b»§f Uso : /$label [Contraseña]");
                    return;
                }
                if (isset(Server::getinstance()->auth[$name])) {
                	$p->sendMessage("§a»§f Ya Has Iniciado sesión");
                    return;
                }
                if (!Server::getinstance()->regs->exists($lowerName)) {
                	$p->sendMessage("§e»§f No estás Registrado Aún!");
                    return;
                }
                
                $userData = Server::getinstance()->regs->get($lowerName);
                $contraReal = is_array($userData) ? $userData["password"] : $userData;
                $contraIngresada = strtolower($args[0]);

                if ($contraIngresada !== $contraReal) {
                	
                    $p->sendMessage("§c» Contraseña incorrecta");
                    return;
                }

                Server::getinstance()->regs->set($lowerName, [
                    "password" => $contraReal,
                    "ip" => $p->getAddress()
                ]);
                Server::getinstance()->regs->save();

                Server::getinstance()->auth[$name] = $name;
                $p->sendMessage("§ Has iniciado sesión correctamente.");
                }
             }