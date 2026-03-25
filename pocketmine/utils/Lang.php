<?php

namespace pocketmine\utils;

use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\Server;

class Lang {
    
    private static $instance;
    private static $cachedLangs = [];
    private $playerPrefs;

    public function __construct(string $dataPath) {
        self::$instance = $this;
        $this->playerPrefs = new Config($dataPath . "players_lang.yml", Config::YAML);
    }

    public static function getInstance(): self {
        return self::$instance;
    }

    public function setPlayerLang(Player $player, string $lang) {
        $this->playerPrefs->set(strtolower($player->getName()), strtolower($lang));
        $this->playerPrefs->save();
    }

    public function getPlayerLang(Player $player): string {
        return $this->playerPrefs->get(strtolower($player->getName()), "spa");
    }

    /**
     * @param string|Player $sender
     * @param string $key Clave en el .ini
     * @param string $pluginPath Ruta del plugin (admite phar://)
     * @param array $params
     */
    public static function translate($sender, string $key, string $pluginPath, array $params = []): string {
        $langCode = "spa";
        if ($sender instanceof Player) {
            $langCode = self::getInstance()->getPlayerLang($sender);
        }

        // Estructura dinámica: CarpetaPlugin/languages/en.ini
        $cacheKey = $pluginPath . "_" . $langCode;

        if (!isset(self::$cachedLangs[$cacheKey])) {
            $fullPath = $pluginPath . DIRECTORY_SEPARATOR . "lang" . DIRECTORY_SEPARATOR . $langCode . ".ini";
            
            if (file_exists($fullPath)) {
                self::$cachedLangs[$cacheKey] = parse_ini_file($fullPath);
            } else {
                // Fallback automático al español si no existe el idioma seleccionado
                $fallback = $pluginPath . DIRECTORY_SEPARATOR . "lang" . DIRECTORY_SEPARATOR . "spa.ini";
                self::$cachedLangs[$cacheKey] = file_exists($fallback) ? parse_ini_file($fallback) : [];
            }
        }

        $message = self::$cachedLangs[$cacheKey][$key] ?? "§c[Missing: $key]§r";

        foreach ($params as $i => $value) {
            $message = str_replace("{%{$i}}", $value, $message);
        }

        return $message;
    }
}
