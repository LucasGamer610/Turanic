<?php

/*
 *
 *    _______                    _
 *   |__   __|                  (_)
 *      | |_   _ _ __ __ _ _ __  _  ___
 *      | | | | | '__/ _` | '_ \| |/ __|
 *      | | |_| | | | (_| | | | | | (__
 *      |_|\__,_|_|  \__,_|_| |_|_|\___|
 *
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author TuranicTeam
 * @link https://github.com/TuranicTeam/Turanic
 *
 */

declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\event\block\BlockSpreadEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\Server;

class Mycelium extends Solid {

	protected $id = self::MYCELIUM;

	public function __construct(int $meta = 0){
		$this->meta = $meta;
	}

	public function getName() : string{
		return "Mycelium";
	}

	public function getToolType() : int{
		return Tool::TYPE_SHOVEL;
	}

	public function getHardness() : float{
		return 0.6;
	}

	public function getDrops(Item $item) : array{
		if($item->getEnchantmentLevel(Enchantment::TYPE_MINING_SILK_TOUCH) > 0){
			return parent::getDrops($item);
		}else{
			return [
				Item::get(Item::DIRT)
			];
		}
	}

    public function ticksRandomly() : bool{
        return true;
    }

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_RANDOM){
            //TODO: light levels
            $x = mt_rand($this->x - 1, $this->x + 1);
            $y = mt_rand($this->y - 2, $this->y + 2);
            $z = mt_rand($this->z - 1, $this->z + 1);
            $block = $this->getLevel()->getBlockAt($x, $y, $z);
            if($block->getId() === Block::DIRT){
                if($block->getSide(Vector3::SIDE_UP) instanceof Transparent){
                    Server::getInstance()->getPluginManager()->callEvent($ev = new BlockSpreadEvent($block, $this, BlockFactory::get(Block::MYCELIUM)));
                    if(!$ev->isCancelled()){
                        $this->getLevel()->setBlock($block, $ev->getNewState());
                    }
                }
            }
		}
	}
}
