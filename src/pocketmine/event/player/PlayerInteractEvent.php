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

namespace pocketmine\event\player;

use pocketmine\block\Block;
use pocketmine\event\Cancellable;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;

/**
 * Called when a player interacts or touches a block (including air?)
 */
class PlayerInteractEvent extends PlayerEvent implements Cancellable{
    public static $handlerList = null;

    const LEFT_CLICK_BLOCK = 0;
    const RIGHT_CLICK_BLOCK = 1;
    const LEFT_CLICK_AIR = 2;
    const RIGHT_CLICK_AIR = 3;
    const PHYSICAL = 4;

    /** @var Block */
    protected $blockTouched;

    /** @var Vector3 */
    protected $touchVector;

    /** @var int */
    protected $blockFace;

    /** @var Item */
    protected $item;

    /** @var int */
    protected $action;

    /**
     * @param Player       $player
     * @param Item         $item
     * @param Block|null   $block
     * @param Vector3|null $touchVector
     * @param int          $face
     * @param int          $action
     */
    public function __construct(Player $player, Item $item, $block, $touchVector, int $face, int $action = PlayerInteractEvent::RIGHT_CLICK_BLOCK){
        assert($block !== null or $touchVector !== null);
        $this->player = $player;
        $this->item = $item;
        $this->blockTouched = $block ?? Block::get(0, 0, new Position(0, 0, 0, $player->level));
        $this->touchVector = $touchVector ?? new Vector3(0, 0, 0);
        $this->blockFace = $face;
        $this->action = $action;
    }

    /**
     * @return int
     */
    public function getAction() : int{
        return $this->action;
    }

    /**
     * @return Item
     */
    public function getItem() : Item{
        return $this->item;
    }

    /**
     * @return Block
     */
    public function getBlock() : Block{
        return $this->blockTouched;
    }

    /**
     * @return Vector3
     */
    public function getTouchVector() : Vector3{
        return $this->touchVector;
    }

    /**
     * @return int
     */
    public function getFace() : int{
        return $this->blockFace;
    }
}