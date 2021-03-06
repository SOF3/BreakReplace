<?php
namespace BreakReplace;

use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\math\Vector3;

class EventListener implements Listener {
    public function __construct($plugin) {
        $this->plugin = $plugin;
    }

    /**
     * @param BlockBreakEvent $event
     *
     * @priority MONITOR
     * @ignoreCancelled true
     */
    public function onBreak(BlockBreakEvent $event) {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $item = $event->getItem();
        $drops = $event->getDrops();
        $vector3 = new Vector3($block->x, $block->y, $block->z);
        if($this->plugin->getBRStatus($player)) {
            if($item->canBePlaced()) {
                $player->getLevel()->setBlock($vector3, Block::get($item->getId(), $item->getDamage()), true, true);
                if(!$player->isCreative()) {
                    $player->getInventory()->removeItem(Item::get($item->getId(), $item->getDamage(), 1));
                }
                foreach($drops as $drop) {
                    $player->getInventory()->addItem($drop);
                }
                $event->setCancelled();
            }
        }
    }

}
