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

use pocketmine\event\block\LeavesDecayEvent;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\Server;

class Leaves extends Transparent {
	const OAK = 0;
	const SPRUCE = 1;
	const BIRCH = 2;
	const JUNGLE = 3;
	const ACACIA = 0;
	const DARK_OAK = 1;

	protected $id = self::LEAVES;
    protected $woodType = self::WOOD;

	public function __construct(int $meta = 0){
		$this->meta = $meta;
	}

	public function getHardness(){
		return 0.2;
	}

	public function getToolType(){
		return Tool::TYPE_SHEARS;
	}

	public function getBurnChance() : int{
		return 30;
	}

	public function getBurnAbility() : int{
		return 60;
	}

	public function getName() : string{
		static $names = [
			self::OAK => "Oak Leaves",
			self::SPRUCE => "Spruce Leaves",
			self::BIRCH => "Birch Leaves",
			self::JUNGLE => "Jungle Leaves",
		];
		return $names[$this->getVariant()];
	}

    public function diffusesSkyLight() : bool{
        return true;
    }

    public function ticksRandomly() : bool{
        return true;
    }

	private function findLog(Block $pos, array $visited, $distance, &$check, $fromSide = null){
        ++$check;
        $index = $pos->x . "." . $pos->y . "." . $pos->z;
        if(isset($visited[$index])){
            return false;
        }
        if($pos->getId() === $this->woodType){
            return true;
        }elseif($pos->getId() === $this->id and $distance < 3){
            $visited[$index] = true;
            $down = $pos->getSide(Vector3::SIDE_DOWN)->getId();
            if($down === $this->woodType){
                return true;
            }
            if($fromSide === null){
                for($side = 2; $side <= 5; ++$side){
                    if($this->findLog($pos->getSide($side), $visited, $distance + 1, $check, $side) === true){
                        return true;
                    }
                }
            }else{ //No more loops
                switch($fromSide){
                    case 2:
                        if($this->findLog($pos->getSide(Vector3::SIDE_NORTH), $visited, $distance + 1, $check, $fromSide) === true){
                            return true;
                        }elseif($this->findLog($pos->getSide(Vector3::SIDE_WEST), $visited, $distance + 1, $check, $fromSide) === true){
                            return true;
                        }elseif($this->findLog($pos->getSide(Vector3::SIDE_EAST), $visited, $distance + 1, $check, $fromSide) === true){
                            return true;
                        }
                        break;
                    case 3:
                        if($this->findLog($pos->getSide(Vector3::SIDE_SOUTH), $visited, $distance + 1, $check, $fromSide) === true){
                            return true;
                        }elseif($this->findLog($pos->getSide(Vector3::SIDE_WEST), $visited, $distance + 1, $check, $fromSide) === true){
                            return true;
                        }elseif($this->findLog($pos->getSide(Vector3::SIDE_EAST), $visited, $distance + 1, $check, $fromSide) === true){
                            return true;
                        }
                        break;
                    case 4:
                        if($this->findLog($pos->getSide(Vector3::SIDE_NORTH), $visited, $distance + 1, $check, $fromSide) === true){
                            return true;
                        }elseif($this->findLog($pos->getSide(Vector3::SIDE_SOUTH), $visited, $distance + 1, $check, $fromSide) === true){
                            return true;
                        }elseif($this->findLog($pos->getSide(Vector3::SIDE_WEST), $visited, $distance + 1, $check, $fromSide) === true){
                            return true;
                        }
                        break;
                    case 5:
                        if($this->findLog($pos->getSide(Vector3::SIDE_NORTH), $visited, $distance + 1, $check, $fromSide) === true){
                            return true;
                        }elseif($this->findLog($pos->getSide(Vector3::SIDE_SOUTH), $visited, $distance + 1, $check, $fromSide) === true){
                            return true;
                        }elseif($this->findLog($pos->getSide(Vector3::SIDE_EAST), $visited, $distance + 1, $check, $fromSide) === true){
                            return true;
                        }
                        break;
                }
            }
        }

        return false;
	}

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			if(($this->meta & 0b00001100) === 0){
				$this->meta |= 0x08;
				$this->getLevel()->setBlock($this, $this, false, false);
			}
		}elseif($type === Level::BLOCK_UPDATE_RANDOM){
			if(($this->meta & 0b00001100) === 0x08){
				$this->meta &= 0x03;
				$visited = [];
				$check = 0;

				Server::getInstance()->getPluginManager()->callEvent($ev = new LeavesDecayEvent($this));

				if($ev->isCancelled() or $this->findLog($this, $visited, 0, $check) === true){
					$this->getLevel()->setBlock($this, $this, false, false);
				}else{
					$this->getLevel()->useBreakOn($this);

					return Level::BLOCK_UPDATE_NORMAL;
				}
			}
		}

		return false;
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool{
		$this->meta |= 0x04;
		return $this->getLevel()->setBlock($this, $this, true);
	}

    public function getDrops(Item $item) : array{
        if($item->isShears()){
            return parent::getDrops($item);
        }
        $drops = [];
        if(mt_rand(1, 20) === 1){ //Saplings
            $drops[] = $this->getSaplingItem();
        }
        if($this->canDropApples() and mt_rand(1, 200) === 1){ //Apples
            $drops[] = Item::get(Item::APPLE);
        }
        return $drops;
    }

    public function getVariantBitmask() : int{
        return 0x03;
    }

    public function getSaplingItem() : Item{
        return Item::get(Item::SAPLING, $this->getVariant());
    }

    public function canDropApples() : bool{
        return $this->meta === self::OAK;
    }
}
