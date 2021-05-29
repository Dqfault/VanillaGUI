<?php

/**
 * VanillaGUI
 *
 * Copyright (c) 2021 Dqfault
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */

namespace Dqfault\VanillaGUI\block;


use Dqfault\VanillaGUI\inventory\EnchantInventory;
use pocketmine\block\EnchantingTable;
use pocketmine\item\Item;
use pocketmine\Player as BasePlayer;

class EnchantmentBlock extends EnchantingTable{

    public function onActivate(Item $item, BasePlayer $player = null) : bool{
        $player->addWindow(new EnchantInventory($this));

        return true;
    }

}