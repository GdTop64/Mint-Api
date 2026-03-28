<?php

namespace pocketmine\event;

use pocketmine\event\{Event, Listener};
use pocketmine\event\player\{PlayerChatEvent, PlayerLoginEvent, PlayerPreLoginEvent, PlayerAnimationEvent, PlayerCommandPreprocessEvent, PlayerJoinEvent, PlayerMoveEvent, PlayerQuitEvent, PlayerRespawnEvent,PlayerKickEvent,PlayerInteractEvent};

class EventManager implements Listener
{
	/*
	@function PlayerJoinEvent
	*/
	public function onJoin(PlayerJoinEvent $ev){
		}
    /*
    @function PlayerQuitEvent
    */
    public function onQuit(PlayerQuitEvent $ev){
    	}
    
    public function onCommand(PlayerCommandPreprocessEvent $ev){
    	}
    
    public function onChat(PlayerChatEvent $ev){
    	}
    
    public function onKick(PlayerKickEvent $ev){
    	}
    	
	}