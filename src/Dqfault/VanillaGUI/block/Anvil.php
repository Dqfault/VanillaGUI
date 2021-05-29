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


use Dqfault\VanillaGUI\inventory\AnvilInventory;
use pocketmine\block\Anvil as BaseAnvil;
use pocketmine\item\Item;
use pocketmine\Player;

class Anvil extends BaseAnvil{

    public function onActivate(Item $item, Player $player = null) : bool{
        $player->addWindow(new AnvilInventory($this));

        return true;
    }

}