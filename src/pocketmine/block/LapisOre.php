<?php

/*
 *
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
 *
*/

namespace pocketmine\block;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\item\Tool;

class LapisOre extends Solid {

	protected $id = self::LAPIS_ORE;

	public function __construct(int $meta = 0){
		$this->meta = $meta;
	}

	public function getHardness() : float{
		return 3;
	}

	public function getToolType() : int{
		return Tool::TYPE_PICKAXE;
	}

	public function getName() : string{
		return "Lapis Lazuli Ore";
	}

	public function getDrops(Item $item) : array{
		if($item->isPickaxe() >= 3){
			if($item->getEnchantmentLevel(Enchantment::TYPE_MINING_SILK_TOUCH) > 0){
				return parent::getDrops($item);
			}else{
				$fortunel = $item->getEnchantmentLevel(Enchantment::TYPE_MINING_FORTUNE);
				$fortunel = $fortunel > 3 ? 3 : $fortunel;
				$times = [1, 1, 2, 3, 4];
				$time = $times[mt_rand(0, $fortunel + 1)];
				return [
					Item::get(Item::DYE, 4, mt_rand(4, 8) * $time)
				];
			}
		}else{
			return [];
		}
	}

    public function canHarvestWithHand(): bool{
        return false;
    }
}
