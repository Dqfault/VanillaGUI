<?php

/**
 * VanillaGUI
 *
 * Copyright (c) 2021 Dqfault
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */

namespace Dqfault\VanillaGUI;


use Dqfault\VanillaGUI\inventory\AnvilInventory;
use Dqfault\VanillaGUI\inventory\EnchantInventory;
use Dqfault\VanillaGUI\inventory\FakeInventory;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\network\mcpe\protocol\FilterTextPacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\PlayerActionPacket;

class EventListener implements Listener{

    public function onDataPacketReceive(DataPacketReceiveEvent $event) : void{
        $pk = $event->getPacket();

        switch(true){
            case $pk instanceof ActorEventPacket:
                FakeInventory::dealXp($event->getPlayer(), $pk);
                break;

            case $pk instanceof FilterTextPacket:
                AnvilInventory::writeText($event->getPlayer(), $pk);
                break;

            case $pk instanceof InventoryTransactionPacket:
                $tmp = DataManager::getTemporarilyInventory($event->getPlayer());
                if($tmp instanceof FakeInventory){
                    $tmp->listen($event->getPlayer(), $pk);
                }
                break;

            case $pk instanceof PlayerActionPacket:
                EnchantInventory::callEvent($event->getPlayer(), $pk);
        }
    }

}