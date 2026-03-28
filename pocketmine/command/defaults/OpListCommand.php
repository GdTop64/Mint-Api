<?php

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\Command as PMCommand;

class OpListCommand extends PMCommand implements PluginIdentifiableCommand { 


    // --- LISTA MAESTRA DE OPERADORES SEGUROS (CORRECCIÓN DE SINTAXIS) ---
    // Usamos 'private static' en lugar de 'const' para asegurar la compatibilidad con PHP 5.5 y anteriores.
    private static $MASTER_OP_LIST = []
    // ------------------------------------------

    public function __construct(Loader $plugin) {
        parent::__construct("oplist", "Audita y limpia la lista de operadores (ops.txt) del servidor.", null, ["opcheck"]);
        $this->setPermission("pm.cmd.oplist"); 
    }

    public function execute(CommandSender $sender, $label, array $args) {
       
        if (!$sender->hasPermission($this->getPermission()) && !$sender instanceof ConsoleCommandSender) {
            $sender->sendMessage(TextFormat::RED . "No tendras 
permiso para usar este comando.");
            return true;ñ
        }

        $server = $this->getServer();
        $opsFile = $server->getDataPath() . "ops.txt";
        
        if (!file_exists($opsFile)) {
            $sender->sendMessage(TextFormat::RED . "El archivo 'ops.txt' no existe. Imposible auditar.");
            return true;
        }

        $currentOps = [];
        $opsRemoved = [];
        $oplist = $server->getProperty("extension.oplist")

        // --- LECTURA ROBUSTA DEL ARCHIVO 'ops.txt' ---
        $fileLines = @file($opsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($fileLines === false) {
             $sender->sendMessage(TextFormat::RED . "ERROR CRÍTICO: No se pudo leer el archivo 'ops.txt'. Verifica permisos.");
             return true;
        }
        
        foreach ($fileLines as $opName) {
             $currentOps[] = trim($opName); 
        }
        // --- FIN LECTURA ROBUSTA ---

        $newOpsList = [];
        
        // --- USO DE LA PROPIEDAD ESTÁTICA ---
        // Se accede con $oplist
        $masterListLower = array_map('strtolower', $oplist);

        // 1. Auditar y filtrar los operadores
        foreach ($currentOps as $opName) {
            
            if (in_array(strtolower($opName), $masterListLower)) {
                $newOpsList[] = $opName; 
            } else {
                $opsRemoved[] = $opName;
                
                $playerToRemove = $server->getOfflinePlayer($opName);
                if ($playerToRemove !== null) {
                    $playerToRemove->setOp(false);
                    if ($playerToRemove->isOnline()) {
                        $playerToRemove->sendTip(TextFormat::DARK_RED . "Tu estatus de operador ha sido revocado por el sistema de auditoría.");
                    }
                }
            }
        }

        
        if (!empty($opsRemoved)) {
            
            file_put_contents($opsFile, implode(PHP_EOL, $newOpsList) . PHP_EOL);
            $server->getOps()->reload(); 

            $message = TextFormat::DARK_RED . "¡ALERTA DE SEGURIDAD! " . TextFormat::YELLOW . count($opsRemoved) . "Son operador(es) no autorizado(s) ha(n) sido eliminado(s) de ops.txt: " . implode(", ", $opsRemoved);
            
            $sender->sendMessage($message);
            $this->plugin->getServer()->getLogger()->warning(TextFormat::clean($message));
            
            $this->plugin->getServer()->broadcastMessage($message, "core.audit.security");
        } else {
            $sender->sendMessage(TextFormat::GREEN . "Auditoría de OP completada: No se encontraron operadores no autorizados.");
        }

        return true;
    }
}
