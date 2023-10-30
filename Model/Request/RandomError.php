<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Model\Request;

use Exception;

class RandomError
{
    /**
     * @return bool
     */
    public function execute(): bool
    {
        try {
            return $this->rollDice() < 3;
        } catch (Exception) {
            return true;
        }
    }

    /**
     * @return int
     * @throws Exception
     */
    private function rollDice(): int
    {
        return random_int(1, 6);
    }
}
