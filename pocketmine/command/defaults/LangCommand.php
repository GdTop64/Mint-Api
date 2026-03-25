<?php

namespace pocketmine\command\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Lang;

class LangCommand extends VanillaCommand {

    public function __construct($name) {
        // Registramos el comando
        parent::__construct($name, "Cambia tu idioma personal", "/lang <idioma>", ["language"]);
    }

    public function execute(CommandSender $sender, $label, array $args) {
    if(!$sender instanceof Player) {
        $sender->sendMessage("Use this command in-game.");
        return false;
    }

    if(!isset($args[0])) {
        $sender->sendMessage("§cUsage: /lang <spa|eng|bra>");
        return false;
    }

    $selected = strtolower($args[0]);
    // Validación simple (puedes expandirla)
    if(!in_array($selected, ["spa", "eng", "bra"])) {
        $sender->sendMessage("§cLanguage not supported.");
        return false;
    }

    Lang::getInstance()->setPlayerLang($sender, $selected);
    
    $sender->sendMessage("§aLanguage updated to: §f" . $selected);
    return true;
   }
}