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


use Dqfault\VanillaGUI\event\PlayerEnchantItemEvent;
use Dqfault\VanillaGUI\DataManager;
use pocketmine\network\mcpe\protocol\PlayerActionPacket;
use pocketmine\network\mcpe\protocol\types\WindowTypes;
use pocketmine\Player;

class EnchantInventory extends FakeInventory{

    public function getName() : string{
        return 'EnchantInventory';
    }

    public function getDefaultSize() : int{
        return 2;
    }

    public function getNetworkType() : int{
        return WindowTypes::ENCHANTMENT;
    }

    public function getFirstVirtualSlot() : int{
        return 14;
    }

    public function getVirtualSlots() : array{
        return [14, 15];
    }

    public static function callEvent(Player $player, PlayerActionPacket $packet) : void{
        if($packet->action === PlayerActionPacket::ACTION_SET_ENCHANTMENT_SEED){
            $inventory = DataManager::getTemporarilyInventory($player);
            if($inventory instanceof EnchantInventory){
                $ev = new PlayerEnchantItemEvent($player, $inventory->getItem(0));
                $ev->call();
                if($ev->isCancelled()){
                    $ev->getItem()->removeEnchantments();
                }
                $inventory->setItem(0, $ev->getItem());
            }
        }
    }

}