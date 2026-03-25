<?php

namespace pocketmine\plugin;

use pocketmine\event\plugin\PluginDisableEvent;
use pocketmine\event\plugin\PluginEnableEvent;
use pocketmine\Server;
use pocketmine\utils\PluginException;
use pocketmine\utils\MainLogger;
use pocketmine\utils\TextFormat;

class PharPluginLoader implements PluginLoader{

	private $server;

	public function __construct(Server $server){
		$this->server = $server;
	}

	public function loadPlugin($file){
		$description = $this->getPluginDescription($file);

		if($description instanceof PluginDescription){
			$this->server->getLogger()->info("Cargando el Plugin " . $description->getName() . "...");
			$dataFolder = dirname($file) . DIRECTORY_SEPARATOR . $description->getName();

			if(file_exists($dataFolder) and !is_dir($dataFolder)){
				throw new \InvalidStateException("La carpeta de datos proyectada '" . $dataFolder . "' para " . $description->getName() . " existe y no es un directorio");
			}

			$isPhar = pathinfo($file, PATHINFO_EXTENSION) === "phar";
			$pluginPath = $file;

			if($isPhar){
				$pluginPath = "phar://$file";
				$this->server->getLoader()->addPath("$pluginPath/src");
			} else {
				$this->server->getLoader()->addPath("$file/src");
			}
			
			$className = $description->getMain();

			if(class_exists($className, true)){
				$plugin = new $className();
				$this->initPlugin($plugin, $description, $dataFolder, $pluginPath);
				return $plugin;
			} else {
				throw new PluginException("No pudo Cargar el Plugin " . $description->getName() . ": No se encontró la Clase Main");
			}
		}

		return null;
	}

	public function getPluginDescription($file){
		if(is_file($file) and pathinfo($file, PATHINFO_EXTENSION) === "phar"){
			try {
				$phar = new \Phar($file);
				if(isset($phar["plugin.yml"])){
					$pluginYml = $phar["plugin.yml"];
					if($pluginYml instanceof \PharFileInfo){
						return new PluginDescription($pluginYml->getContent());
					}
				}
			} catch (\Exception $e) {
				MainLogger::getLogger()->debug("No se pudo abrir el archivo PHAR " . $file . ": " . $e->getMessage());
			}
		} elseif(is_dir($file) and file_exists($file . "/plugin.yml")){
			$yaml = @file_get_contents($file . "/plugin.yml");
			if($yaml !== false && $yaml !== ""){
				return new PluginDescription($yaml);
			}
		}

		return null;
	}

	public function getPluginFilters(){
		return "/(\\.phar$)|([^\\.]+$)/i";
	}

	private function initPlugin(PluginBase $plugin, PluginDescription $description, $dataFolder, $file){
		$plugin->init($this, $this->server, $description, $dataFolder, $file);
		$plugin->onLoad();
	}

	public function enablePlugin(Plugin $plugin){
		if($plugin instanceof PluginBase and !$plugin->isEnabled()){
			$this->server->getLogger()->info("Plugin " . $plugin->getDescription()->getName() . " Status: §eON");
			$plugin->setEnabled(true);
			$this->server->getPluginManager()->callEvent(new PluginEnableEvent($plugin));
		}
	}

	public function disablePlugin(Plugin $plugin){
		if($plugin instanceof PluginBase and $plugin->isEnabled()){
			$this->server->getLogger()->info("Plugin " . $plugin->getDescription()->getName() . " Status: §cOFF");
			$this->server->getPluginManager()->callEvent(new PluginDisableEvent($plugin));
			$plugin->setEnabled(false);
		}
	}
}