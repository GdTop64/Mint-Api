<?php

namespace pocketmine;
define("pocketmine\BASE_PATH", realpath(__DIR__ . "/../../") . DIRECTORY_SEPARATOR);

use pocketmine\{Player, Server};
use pocketmine\plugin\PluginBase;

class refactor extends PluginBase {
    
    public function refactorial($path, $txt, $replace){
        // Limpiar la ruta para evitar el error de "storage/emulated/0/storage/emulated/0"
        if (strpos($path, "storage") !== false) {
            $basePath = $path;
        } else {
            $basePath = \pocketmine\BASE_PATH . trim($path, "/");
        }
        
        $basePath = str_replace("//", "/", $basePath);

        if (is_dir($basePath)) {
            $this->refactorDir($basePath, $txt, $replace);
        } elseif (is_file($basePath)) {
            $ext = strtolower(pathinfo($basePath, PATHINFO_EXTENSION));
            if ($ext === "phar") {
                $this->refactorPhar($basePath, $txt, $replace);
            } elseif ($ext === "zip") {
                $this->refactorZip($basePath, $txt, $replace);
            }
        } else {
            Server::getinstance()->getLogger()->info("§c[Refactor] Ruta no encontrada: $basePath");
        }
    }

    private function refactorDir($dir, $txt, $replace) {
        $extensions = ['php', 'yml', 'yaml', 'json', 'txt', 'ini'];
        // Banderas especiales para asegurar que lea TODO en Android
        $directory = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS | \RecursiveDirectoryIterator::UNIX_PATHS);
        $iterator = new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::SELF_FIRST);

        $count = 0;
        foreach ($iterator as $file) {
            if ($file->isFile() && in_array(strtolower($file->getExtension()), $extensions)) {
                $path = $file->getPathname();
                $content = file_get_contents($path);
                $newContent = str_ireplace($txt, $replace, $content);
                
                if ($content !== $newContent) {
                    file_put_contents($path, $newContent);
                    $count++;
                    Server::getinstance()->getLogger()->info("§b[Refactor] §fModificado: " . $file->getFilename());
                }
            }
        }
        
        if($count > 0){
            Server::getinstance()->getLogger()->info("§a[Refactor] ¡Éxito! $count archivos actualizados en $dir");
        } else {
            Server::getinstance()->getLogger()->info("§e[Refactor] No se encontraron textos para cambiar en esta carpeta.");
        }
    }

    /**
     * Refactoriza dentro de un archivo .phar
     */
    private function refactorPhar($path, $txt, $replace) {
        // IMPORTANTE: En Termux/PHP el phar.readonly debe ser 0
        if (ini_get("phar.readonly") == 1) {
            echo "Error: No se puede editar PHAR. Ejecuta con -d phar.readonly=0\n";
            return;
        }

        try {
            $phar = new \Phar($path);
            foreach (new \RecursiveIteratorIterator($phar) as $file) {
                if (preg_match('/\.(php|yml|yaml|json|ini)$/', $file->getFilename())) {
                    $content = file_get_contents($file->getPathname());
                    $newContent = str_ireplace($txt, $replace, $content);
                    if ($content !== $newContent) {
                        $phar[$file->getFileName()] = $newContent;
                    }
                }
            }
                      
Server::getinstance()->getLogger()->info("PHAR Modificado: " . basename($path) . "\n");
        } catch (\Exception $e) {
            Server::getinstance()->getLogger()->info("Error procesando PHAR: " . $e->getMessage() . "\n");
        }
    }

    /**
     * Refactoriza dentro de un archivo .zip
     */
    private function refactorZip($path, $txt, $replace) {
        $zip = new \ZipArchive();
        if ($zip->open($path) === TRUE) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                $ext = pathinfo($name, PATHINFO_EXTENSION);

                if (in_array($ext, ['php', 'yml', 'yaml', 'json'])) {
                    $content = $zip->getFromIndex($i);
                    $newContent = str_ireplace($txt, $replace, $content);
                    
                    if ($content !== $newContent) {
                        $zip->addFromString($name, $newContent);
                    }
                }
            }
            $zip->close();
                      

            Server::getinstance()->getLogger()->info("ZIP Modificado: " . basename($path) . "\n");
        } else {
           Server::getinstance()->getLogger()->info("Error: No se pudo abrir el ZIP: $path\n");
        }
    }
}
