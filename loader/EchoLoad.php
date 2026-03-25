<?php

namespace loader;

use pocketmine\utils\TextFormat;
use pocketmine\Server;
use pocketmine\command\{ConsoleCommandSender, CommandSender};
class EchoLoad {
	
	public function onCommand(CommandSender $s, Command $cmd, $label, array $args){
		}
    
    
    public function StartAll(){
    	$this->searchEcho();
    }
    public function searchEcho(){
        // Definimos la ruta de la carpeta plugins
        $path = \pocketmine\BASE_PATH . "plugins" . DIRECTORY_SEPARATOR;

        if(!is_dir($path)) return;

        $dir = new \DirectoryIterator($path);

        foreach ($dir as $file) {
            if ($file->isFile() && $file->getExtension() === "echo") {
                
                $content = file($file->getPathname()); // Lee el archivo como un array de líneas

                foreach($content as $line){
                    switch($file->getExtension()){
                    	case "echo":
                    if(preg_match('/echo\("([^"]*)"\);/', $line, $match)){
                        // Como no tenemos getLogger, usamos echo o el servidor de PocketMine
                        echo "Echo: " . $match[1] . PHP_EOL;
                        #Server::getinstance()->dispatchCommand($match[1], new ConsoleCommandSender);
                      #  $this->onCommand(new ConsoleCommandSender, $match[1], "OP", null);
                    }
            break;
    	
    	case "exec":
   
    	if(preg_match('/exec\("([^"]*)"\);/', $line, $match)){
    	
    echo "ola";
    	Server::getinstance()->dispatchCommand($match[1], new ConsoleCommandSender);
    $this->getLogger()->info("Ejecutando {$file->getPathname}");
    }  else {
    	echo "nope";
    }
    break;
    }
    }// Switch
   }// ElseIf
  }// Foreach(content as line)
  }// Foreach (Dir as File)
    
}
