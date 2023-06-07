<?php

/**
 * 
 * by vp817
 * 
 */

declare(strict_types=1);

namespace pocketmine\tile;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\LongTag;
use pocketmine\network\mcpe\convert\RuntimeBlockMapping;

class VanillaBlock extends Tile
{

	public const TAG_RUNTIME_ID = "runtimeID";

	/**
	 * @param CompoundTag $nbt
	 * @return void
	 */
	public function writeSaveData(CompoundTag $nbt): void
	{
		$block = $this->getBlock($nbt);
		$nbt->setTag(new LongTag(self::TAG_RUNTIME_ID, RuntimeBlockMapping::toStaticRuntimeId($block->getId(), $block->getDamage())));
	}

	/**
	 * @param CompoundTag $nbt
	 * @return void
	 */
	public function readSaveData(CompoundTag $nbt): void
	{
		$block = $this->getBlock($nbt);
		$block->position($this->asPosition());
	}

	/**
	 * @param CompoundTag|null $nbt
	 * @return Block
	 */
	public function getBlock(?CompoundTag $nbt = null): Block
	{
		$block = parent::getBlock();

		if ($nbt === null) {
			return $block;
		}

		$reserved6 = BlockFactory::get(Block::RESERVED6, 0);

		if (!$block->isValid()) return $reserved6;
		if (!$nbt->hasTag(self::TAG_RUNTIME_ID)) return $reserved6;

		$legacy = RuntimeBlockMapping::fromStaticRuntimeId($nbt->getLong(self::TAG_RUNTIME_ID));
		return BlockFactory::get((int) $legacy[0], (int) $legacy[1]);
	}
}
