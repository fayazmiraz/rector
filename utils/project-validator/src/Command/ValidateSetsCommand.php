<?php

declare(strict_types=1);

namespace Rector\Utils\ProjectValidator\Command;

use Rector\Core\Application\ActiveRectorsProvider;
use Rector\Core\HttpKernel\RectorKernel;
use Rector\Set\RectorSetProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\PackageBuilder\Console\ShellCode;
use Throwable;

/**
 * We'll only check one file for now.
 * This makes sure that all sets are "runnable" but keeps the runtime at a managable level
 */
final class ValidateSetsCommand extends Command
{
    /**
     * @var string[]
     */
    private const EXCLUDED_SETS = [
        // required Kernel class to be set in parameters
        'symfony-code-quality',
    ];

    /**
     * @var RectorSetProvider
     */
    private $rectorSetProvider;

    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;

    public function __construct(RectorSetProvider $rectorSetProvider, SymfonyStyle $symfonyStyle)
    {
        $this->rectorSetProvider = $rectorSetProvider;
        $this->symfonyStyle = $symfonyStyle;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('[CI] Validate each sets has correct configuration by loading the configs');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $hasErrors = false;

        $message = sprintf('Testing %d sets', count($this->rectorSetProvider->provide()));
        $this->symfonyStyle->title($message);

        foreach ($this->rectorSetProvider->provide() as $set) {
            if (in_array($set->getName(), self::EXCLUDED_SETS, true)) {
                continue;
            }

            $rectorKernel = new RectorKernel('prod' . sha1($set->getName()), true);

            $rectorKernel->setConfigs([$set->getSetPathname()]);

            try {
                $rectorKernel->boot();
            } catch (Throwable $throwable) {
                $message = 'Failed to load "%s" set from "%s" path';
                $this->symfonyStyle->error($message);
                $this->symfonyStyle->writeln($throwable->getMessage());

                $hasErrors = true;
                sleep(3);

                continue;
            }

            $container = $rectorKernel->getContainer();
            $activeRectorsProvider = $container->get(ActiveRectorsProvider::class);

            $activeRectors = $activeRectorsProvider->provide();

            $setFileInfo = $set->getSetFileInfo();
            $message = sprintf(
                'Set  "%s" loaded correctly from "%s" path with %d rules',
                $set->getName(),
                $setFileInfo->getRelativeFilePathFromCwd(),
                count($activeRectors)
            );

            $this->symfonyStyle->success($message);
        }

        return $hasErrors ? ShellCode::ERROR : ShellCode::SUCCESS;
    }
}
