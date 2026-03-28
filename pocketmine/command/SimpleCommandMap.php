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

namespace pocketmine\command;

use pocketmine\command\defaults\BanCidByNameCommand;
use pocketmine\command\defaults\BanCidCommand;
use pocketmine\command\defaults\BanCommand;
use pocketmine\command\defaults\BanIpByNameCommand;
use pocketmine\command\defaults\BanIpCommand;
use pocketmine\command\defaults\BanListCommand;
use pocketmine\command\defaults\BiomeCommand;
use pocketmine\command\defaults\CaveCommand;
use pocketmine\command\defaults\ChunkInfoCommand;
use pocketmine\command\defaults\DefaultGamemodeCommand;
use pocketmine\command\defaults\DeopCommand;
use pocketmine\command\defaults\DifficultyCommand;
use pocketmine\command\defaults\DumpMemoryCommand;
use pocketmine\command\defaults\EffectCommand;
use pocketmine\command\defaults\EnchantCommand;
use pocketmine\command\defaults\FillCommand;
use pocketmine\command\defaults\GamemodeCommand;
use pocketmine\command\defaults\GarbageCollectorCommand;
use pocketmine\command\defaults\GiveCommand;
use pocketmine\command\defaults\HelpCommand;
use pocketmine\command\defaults\KickCommand;
use pocketmine\command\defaults\KillCommand;
use pocketmine\command\defaults\LangCommand;
use pocketmine\command\defaults\ListCommand;
use pocketmine\command\defaults\LvdatCommand;
use pocketmine\command\defaults\MeCommand;
use pocketmine\command\defaults\NickCommand;
use pocketmine\command\defaults\OpCommand;
use pocketmine\command\defaults\PardonCommand;
use pocketmine\command\defaults\PardonCidCommand;
use pocketmine\command\defaults\PardonIpCommand;
use pocketmine\command\defaults\ParticleCommand;
use pocketmine\command\defaults\PingCommand;
use pocketmine\command\defaults\PluginsCommand;
use pocketmine\command\defaults\ReloadCommand;
use pocketmine\command\defaults\SaveCommand;
use pocketmine\command\defaults\SaveOffCommand;
use pocketmine\command\defaults\SaveOnCommand;
use pocketmine\command\defaults\SayCommand;
use pocketmine\command\defaults\SeedCommand;
use pocketmine\command\defaults\SetBlockCommand;
use pocketmine\command\defaults\SetWorldSpawnCommand;
use pocketmine\command\defaults\SpawnpointCommand;
use pocketmine\command\defaults\StatusCommand;
use pocketmine\command\defaults\StopCommand;
use pocketmine\command\defaults\SummonCommand;
use pocketmine\command\defaults\TeleportCommand;
use pocketmine\command\defaults\TellCommand;
use pocketmine\command\defaults\TimeCommand;
use pocketmine\command\defaults\TimingsCommand;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\defaults\VersionCommand;
use pocketmine\command\defaults\WeatherCommand;
use pocketmine\command\defaults\WhitelistCommand;
use pocketmine\command\defaults\XpCommand;

// Register
use pocketmine\command\defaults\{LoginCommand, RegisterCommand};
use pocketmine\event\TranslationContainer;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\MainLogger;
use pocketmine\utils\TextFormat;
class SimpleCommandMap implements CommandMap{

	/**
	 * @var Command[]
	 */
	protected $knownCommands = [];
	
	/**
	 * @var bool[]
	 */
	protected $commandConfig = [];

	/** @var Server */
	private $server;

	public function __construct(Server $server){
		$this->server = $server;
		/** @var bool[] */
		$this->commandConfig = $this->server->getProperty("commands");
		$this->setDefaultCommands();
	}

	private function setDefaultCommands(){
		$this->registerAll("mint", [
 new BanCidCommand("bancid"),
 new \loader\EchoLoad("genexec"),
 new PardonCidCommand("pardoncid"),
 new BanCidByNameCommand("bancidbyname"),
 new BanIpByNameCommand("banipbyname"),

 new LvdatCommand("lvdat"),
 new BiomeCommand("biome"),
 new CaveCommand("cave"),
 new ChunkInfoCommand("chunkinfo"),

 new VersionCommand("version"),
 new FillCommand("fill"),
 new PluginsCommand("plugins"),
 new PingCommand("ping"),
 new SeedCommand("seed"),
 new HelpCommand("about?"),
 new StopCommand("stop"),
 new TellCommand("count"),
 new DefaultGamemodeCommand("defaultgamemode"),
 new BanCommand("ban"),
 new BanIpCommand("ban-ip"),
 new BanListCommand("banlist"),
 new PardonCommand("pardon"),
 new PardonIpCommand("pardon-ip"),
 new SayCommand("say"),
 new MeCommand("me"),
 new NickCommand("nick"),
 new LangCommand("lang"),
 new ListCommand("list"),
 new DifficultyCommand("difficulty"),
 new KickCommand("kickpl"),
 new OpCommand("op"),
 new DeopCommand("deop"),
 new WhitelistCommand("whitelist"),
 new SaveOnCommand("save-on"),
 new SaveOffCommand("save-off"),
 new SaveCommand("save-all"),
 new GiveCommand("give"),
 new EffectCommand("effect"),
 new EnchantCommand("enchant"),
 new ParticleCommand("particle"),
 new GamemodeCommand("gamemode"),
 new KillCommand("kill"),
 new SpawnpointCommand("spawnpoint"),
 new SetWorldSpawnCommand("setworldspawn"),
 new SummonCommand("summon"),
 new TeleportCommand("teleport"),
 new TimeCommand("time"),
 new TimingsCommand("timings"),
 new ReloadCommand("reload"),
 new XpCommand("xp"),
 new SetBlockCommand("setblock")
		]);
		
	if($this->server->getProperty("extension.sign-up-in")){
		
 $this->register("log", new RegisterCommand("register"));
 $this->register("log", new LoginCommand("login"));
 }
 
 if($this->server->getProperty("extension.oplist-cmd")){
$this->register("debug", new OpListCommand($this), null, true);
}
		if($this->server->getProperty("debug.commands", false)){
	 $this->register("debug", new StatusCommand("status"), null, true);
$this->register("debug", new GarbageCollectorCommand("gc"), null, true);
	 $this->register("debug", new DumpMemoryCommand("dumpmemory"), null, true);
		}
	}


	public function registerAll($fallbackPrefix, array $commands){
		foreach($commands as $command){
			$this->register($fallbackPrefix, $command);
		}
	}

	public function register($fallbackPrefix, Command $command, $label = null, $overrideConfig = false){
		if($label === null){
			$label = $command->getName();
		}
		$label = strtolower(trim($label));
		
		//Check if command was disabled in config and for override
		if(!(($this->commandConfig[$label] ?? $this->commandConfig["default"] ?? true) or $overrideConfig)){
			return false;
		}
		$fallbackPrefix = strtolower(trim($fallbackPrefix));

		$registered = $this->registerAlias($command, false, $fallbackPrefix, $label);

		$aliases = $command->getAliases();
		foreach($aliases as $index => $alias){
			if(!$this->registerAlias($command, true, $fallbackPrefix, $alias)){
				unset($aliases[$index]);
			}
		}
		$command->setAliases($aliases);

		if(!$registered){
			$command->setLabel($fallbackPrefix . ":" . $label);
		}

		$command->register($this);

		return $registered;
	}

	private function registerAlias(Command $command, $isAlias, $fallbackPrefix, $label){
		$this->knownCommands[$fallbackPrefix . ":" . $label] = $command;
		if(($command instanceof VanillaCommand or $isAlias) and isset($this->knownCommands[$label])){
			return false;
		}

		if(isset($this->knownCommands[$label]) and $this->knownCommands[$label]->getLabel() !== null and $this->knownCommands[$label]->getLabel() === $label){
			return false;
		}

		if(!$isAlias){
			$command->setLabel($label);
		}

		$this->knownCommands[$label] = $command;

		return true;
	}

	private function dispatchAdvanced(CommandSender $sender, Command $command, $label, array $args, $offset = 0){
		if(isset($args[$offset])){
			$argsTemp = $args;
			switch($args[$offset]){
				case "@a":
					$p = $this->server->getOnlinePlayers();
					if(count($p) <= 0){
						$sender->sendMessage(TextFormat::RED . "No players online"); //TODO: add language
					}else{
						foreach($p as $player){
							$argsTemp[$offset] = $player->getName();
							$this->dispatchAdvanced($sender, $command, $label, $argsTemp, $offset + 1);
						}
					}
					break;
				case "@r":
					$players = $this->server->getOnlinePlayers();
					if(count($players) > 0){
						$argsTemp[$offset] = $players[array_rand($players)]->getName();
						$this->dispatchAdvanced($sender, $command, $label, $argsTemp, $offset + 1);
					}
					break;
				case "@p":
					if($sender instanceof Player){
						$argsTemp[$offset] = $sender->getName();
						$this->dispatchAdvanced($sender, $command, $label, $argsTemp, $offset + 1);
					}else{
						$sender->sendMessage(TextFormat::RED . "You must be a player!"); //TODO: add language
					}
					break;
				default:
					$this->dispatchAdvanced($sender, $command, $label, $argsTemp, $offset + 1);
			}
		}else $command->execute($sender, $label, $args);
	}

	public function dispatch(CommandSender $sender, $commandLine){
		$args = explode(" ", $commandLine);

		if(count($args) === 0){
			return false;
		}

		$sentCommandLabel = strtolower(array_shift($args));
		$target = $this->getCommand($sentCommandLabel);

		if($target === null){
			return false;
		}

		$target->timings->startTiming();
		try{
			if($this->server->advancedCommandSelector){
				$this->dispatchAdvanced($sender, $target, $sentCommandLabel, $args);
			}else{
				$target->execute($sender, $sentCommandLabel, $args);
			}
		}catch(\Throwable $e){
			$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.exception"));
			$this->server->getLogger()->critical($this->server->getLanguage()->translateString("pocketmine.command.exception", [$commandLine, (string) $target, $e->getMessage()]));
			$logger = $sender->getServer()->getLogger();
			if($logger instanceof MainLogger){
				$logger->logException($e);
			}
		}
		$target->timings->stopTiming();

		return true;
	}

	public function clearCommands(){
		foreach($this->knownCommands as $command){
			$command->unregister($this);
		}
		$this->knownCommands = [];
		$this->setDefaultCommands();
	}

	public function getCommand($name){
		if(isset($this->knownCommands[$name])){
			return $this->knownCommands[$name];
		}

		return null;
	}

	/**
	 * @return Command[]
	 */
	public function getCommands(){
		return $this->knownCommands;
	}


	/**
	 * @return void
	 */
	public function registerServerAliases(){
		$values = $this->server->getCommandAliases();

		foreach($values as $alias => $commandStrings){
			if(strpos($alias, ":") !== false or strpos($alias, " ") !== false){
				$this->server->getLogger()->warning($this->server->getLanguage()->translateString("pocketmine.command.alias.illegal", [$alias]));
				continue;
			}

			$targets = [];

			$bad = "";
			foreach($commandStrings as $commandString){
				$args = explode(" ", $commandString);
				$command = $this->getCommand($args[0]);

				if($command === null){
					if(strlen($bad) > 0){
						$bad .= ", ";
					}
					$bad .= $commandString;
				}else{
					$targets[] = $commandString;
				}
			}

			if(strlen($bad) > 0){
				$this->server->getLogger()->warning($this->server->getLanguage()->translateString("pocketmine.command.alias.notFound", [$alias, $bad]));
				continue;
			}

			//These registered commands have absolute priority
			if(count($targets) > 0){
				$this->knownCommands[strtolower($alias)] = new FormattedCommandAlias(strtolower($alias), $targets);
			}else{
				unset($this->knownCommands[strtolower($alias)]);
			}

		}
	}


}
