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

declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\entity\Entity;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\TieredTool;
use pocketmine\item\Item;
use pocketmine\item\Tool;

class Cobweb extends Flowable {

	protected $id = self::COBWEB;

	public function __construct(int $meta = 0){
		$this->meta = $meta;
	}

	public function hasEntityCollision(){
		return true;
	}

	public function getName() : string{
		return "Cobweb";
	}

	public function getHardness() : float{
		return 4;
	}

	public function getToolType() : int{
		return Tool::TYPE_SHEARS;
	}

	public function onEntityCollide(Entity $entity){
		$entity->resetFallDistance();
	}

	public function getDrops(Item $item) : array{
		if($item->isShears()){
			return [
                Item::get(Item::COBWEB)
			];
		}elseif($item->isSword() >= TieredTool::TIER_WOODEN){
			if($item->getEnchantmentLevel(Enchantment::TYPE_MINING_SILK_TOUCH) > 0){
				return [
					Item::get(Item::COBWEB)
				];
			}else{
				return [
					Item::get(Item::STRING)
				];
			}
		}
		return [];
	}

    public function canHarvestWithHand(): bool{
        return false;
    }
}
