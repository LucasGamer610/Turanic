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

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\item\Tool;

class CoalOre extends Solid {

	protected $id = self::COAL_ORE;

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
		return "Coal Ore";
	}

	public function getDrops(Item $item) : array{
		if($item->isPickaxe() >= 1){
			if($item->getEnchantmentLevel(Enchantment::TYPE_MINING_SILK_TOUCH) > 0){
				return [
					Item::get(Item::COAL_ORE)
				];
			}else{
				$fortunel = $item->getEnchantmentLevel(Enchantment::TYPE_MINING_FORTUNE);
				$fortunel = $fortunel > 3 ? 3 : $fortunel;
				$times = [1, 1, 2, 3, 4];
				$time = $times[mt_rand(0, $fortunel + 1)];
				return [
					Item::get(Item::COAL, 0, $time)
				];
			}

		}else{
			return [];
		}
	}

    public function canHarvestWithHand() : bool{
        return false;
    }
}
