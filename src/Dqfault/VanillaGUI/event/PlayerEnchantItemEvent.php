<?php

/**
 * VanillaGUI
 *
 * Copyright (c) 2021 Dqfault
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */

namespace Dqfault\VanillaGUI\event;


use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\item\Item;
use pocketmine\Player;

class PlayerEnchantItemEvent extends PlayerEvent implements Cancellable{
    /** @var Item */
    private $item;

    /**
     * @param Player                $player
     * @param Item                  $item
     */
    public function __construct(Player $player, Item $item){
        $this->player = $player;
        $this->item = $item;
    }

    /**
     * Return an enchanted item
     *
     * @return Item
     */
    public function getItem() : Item{
        return $this->item;
    }
}