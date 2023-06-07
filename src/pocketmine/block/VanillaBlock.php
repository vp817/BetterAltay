<?php

/**
 * 
 * by vp817
 * 
 */

declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\convert\RuntimeBlockMapping;
use pocketmine\Player;
use pocketmine\tile\Tile;
use pocketmine\tile\VanillaBlock as TileVanillaBlock;

class VanillaBlock extends Solid
{

	/**
	 * @param int $id
	 * @param int $meta
	 * @param string|null $name
	 * @param int $itemId
	 */
	public function __construct(int $id, int $meta, string $name = null, int $itemId = null)
	{
		parent::__construct($id, $meta, $name, $itemId !== null ? $itemId : ($itemId > 255 ? 255 - $itemId : $itemId));
	}

	/**
	 * @return float
	 */
	public function getHardness(): float
	{
		return 0;
	}

	/**
	 * @return int
	 */
	public function getRuntimeId(): int
	{
		return RuntimeBlockMapping::toStaticRuntimeId($this->id, $this->meta);
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null): bool
	{
		$nbt = TileVanillaBlock::createNBT($this, $face, $item, $player);
		Tile::createTile(Tile::VANILLA_BLOCK, $this->getLevelNonNull(), $nbt);
		$t = $this->getLevelNonNull()->getTile($this);
		$vanillaBlock = null;
		if ($t instanceof TileVanillaBlock) {
			$vanillaBlock = $t->getBlock($nbt);
			$vanillaBlock->position($blockReplace);
		} else {
			$nbt = TileVanillaBlock::createNBT($blockReplace);
			$t = Tile::createTile(Tile::VANILLA_BLOCK, $this->getLevelNonNull(), $nbt);
			if(!($vanillaBlock instanceof TileVanillaBlock)){
				return true;
			}
			$vanillaBlock = $t->getBlock($nbt);
		}
		$this->getLevelNonNull()->setBlock($vanillaBlock->asPosition(), $this, true, true);
		return true;
	}
}
