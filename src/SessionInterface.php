<?php

namespace vakata\session;

use \vakata\kvstore\StorageInterface;

interface SessionInterface extends StorageInterface
{
    public function id(): string;
    public function start(string $id = ''): void;
    public function isStarted(): bool;
    public function close(): void;
    public function destroy(): void;
    public function regenerate(bool $deleteOld = true): void;
}

