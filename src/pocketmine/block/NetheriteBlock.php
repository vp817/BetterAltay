<?php

/**
 * 
 * by vp817
 * 
 */

declare(strict_types=1);

namespace pocketmine\block;

class NetheriteBlock extends VanillaBlock
{

	public function getBlastResistance(): float
	{
		return 1200;
	}

	public function getHardness(): float
	{
		return 50;
	}

	public function __construct()
	{
		parent::__construct(Block::BLOCK_OF_NETHERITE, 0, "Block Of Netherite");
	}
}
