<?php

namespace Phing\PhingConfigurator;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;

/**
 * Class TaskInstaller
 * @package Phing\PhingConfigurator
 */
class ExtensionInstaller extends LibraryInstaller
{
    /**
     * @inheritDoc
     */
    public function supports($packageType)
    {
        return 'phing-extension' === $packageType;
    }
    
    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        parent::install($repo, $package);

        $extra = $package->getExtra();
        $this->installInternalComponents($extra, 'phing-custom-taskdefs', 'task');
        $this->installInternalComponents($extra, 'phing-custom-typedefs', 'type');
    }

    /**
     * @param array $extra
     */
    private function installInternalComponents(array $extra, string $type, $file): void
    {
        foreach ($extra[$type] ?? [] as $name => $class) {
            file_put_contents("custom.${file}.properties", sprintf('%s=%s', $name, $class), FILE_APPEND);
        }
    }
}
