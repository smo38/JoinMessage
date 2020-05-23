<?php

namespace JoinMessage;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class Main extends PluginBase implements Listener{

	private $config;

	public function onEnable(): void {

		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->info("§bJoinMessageを読み込みました by smo");
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
	}

	public function onDisable(): void {

		$this->config->save();
	}

	public function onJoin(PlayerJoinEvent $event): void {

		$name = $event->getPlayer()->getName();

		if($this->c->exists($name)){
			$ms = $this->c->get($name);
			$event->setJoinMessage($ms);
		}else{
			$this->c->set($name, "§f".$name."§eさんが参加しました");
			$event->setJoinMessage("§f".$name."§eさんが参加しました");
		}
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {

        if(!isset($args[0]) or !isset($args[1])) return false;

        if(!$this->c->exists($args[0])){
            $sender->sendMessage("§6>> §e".$args[0]."§cのデータがありません");
        }else{
            $this->c->set($args[0], $args[1]);
            $this->c->save();
            $sender->sendMessage("§6>>§e".$args[0]."§bの参加メッセージを変更しました");
            $sender->sendMessage("§6変更内容>> §f".$args[1]);
        }

        return true;
	}
}
