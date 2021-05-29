<?php

/**
 * VanillaGUI
 *
 * Copyright (c) 2021 Dqfault
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */

namespace Dqfault\VanillaGUI\inventory;


use Dqfault\VanillaGUI\DataManager;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\inventory\ContainerInventory;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\inventory\transaction\InventoryTransaction;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\types\ContainerIds;
use pocketmine\network\mcpe\protocol\types\NetworkInventoryAction;
use pocketmine\Player;

abstract class FakeInventory extends ContainerInventory{

    /**
     * @return int
     */
    abstract public function getFirstVirtualSlot() : int;

    /**
     * @return int[]
     */
    abstract public function getVirtualSlots() : array;

    public function open(Player $who) : bool{
        DataManager::setTemporarilyInventory($who, $this);
        return parent::open($who);
    }

    public function close(Player $who) : void{
        DataManager::resetTemporarilyData($who);
        parent::close($who);
    }

    public function listen(Player $who, InventoryTransactionPacket $packet) : void{
        $tmp = DataManager::getTemporarilyInventory($who);
        if($tmp instanceof $this){
            $transactionData = $packet->trData;
            foreach($transactionData->getActions() as $action){
                switch($action->sourceType){
                    case NetworkInventoryAction::SOURCE_WORLD:
                        if($action->windowId === null){
                            $ev = new PlayerDropItemEvent($who, $action->newItem->getItemStack());
                            $ev->call();
                            if($ev->isCancelled()){
                                $tmp->setItem($action->inventorySlot, $action->newItem->getItemStack());
                            }else{
                                $who->dropItem($action->newItem->getItemStack());
                            }
                        }
                        break;

                    case NetworkInventoryAction::SOURCE_CONTAINER:
                        $adjustedSlot = $action->inventorySlot - $this->getFirstVirtualSlot();
                        $ev = new InventoryTransactionEvent(new InventoryTransaction($who, [
                            new SlotChangeAction($who->getWindow($action->windowId), $action->inventorySlot, $action->oldItem->getItemStack(), $action->newItem->getItemStack()),
                            new SlotChangeAction($tmp, $adjustedSlot, $action->oldItem->getItemStack(), $action->newItem->getItemStack())
                        ]));
                        $ev->call();

                        if($action->windowId === ContainerIds::UI && in_array($action->inventorySlot, $this->getVirtualSlots(), true)){
                            $tmp->setItem($adjustedSlot, $ev->isCancelled() ? $action->oldItem->getItemStack() : $action->newItem->getItemStack());
                        }else{
                            $who->getWindow($action->windowId)->setItem($action->inventorySlot, $ev->isCancelled() ? $action->oldItem->getItemStack() : $action->newItem->getItemStack());
                        }
                }
            }
        }
    }

    public static function dealXp(Player $player, ActorEventPacket $packet) : void{
        if($packet->event === ActorEventPacket::PLAYER_ADD_XP_LEVELS && DataManager::equalsTemporarilyInventory($player, static::class)){
            $player->addXpLevels($packet->data);
        }
    }

}