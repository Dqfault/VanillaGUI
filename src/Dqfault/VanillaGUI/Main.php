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


use Dqfault\VanillaGUI\block\Anvil;
use Dqfault\VanillaGUI\block\EnchantmentBlock;
use pocketmine\block\BlockFactory;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{

    public function onEnable(){
        BlockFactory::registerBlock(new Anvil(), true);
        BlockFactory::registerBlock(new EnchantmentBlock(), true);

        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
    }

}