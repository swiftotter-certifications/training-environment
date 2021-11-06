<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/24/21
 * @website https://swiftotter.com
 **/

namespace Project\Bug4SlideshowBroken\Command;

use Magento\Framework\App\Area;
use Magento\Framework\App\Cache\Manager;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\State;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\View\Asset\Repository;
use Magento\Setup\Console\Command\DeployStaticContentCommand;
use phpDocumentor\Reflection\Types\Static_;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Initialize extends Command
{
    const CLEAN_DIRECTORIES = ['var/page_cache', 'var/view_preprocessed', 'pub/static/frontend/'];

    private WriterInterface $configWriter;

    private Repository $assetRepository;

    private State $state;
    /** @var \Magento\Framework\App\Cache\Manager */
    private Manager $cacheManager;
    private DeployStaticContentCommand $deployStaticContentCommand;
    private DirectoryList $directoryList;

    public function __construct(
        WriterInterface $configWriter,
        Repository $assetRepository,
        State $state,
        Manager $cacheManager,
        DirectoryList $directoryList,
        string $name = null
    ) {
        parent::__construct($name);
        $this->configWriter = $configWriter;
        $this->assetRepository = $assetRepository;
        $this->state = $state;
        $this->cacheManager = $cacheManager;
        $this->directoryList = $directoryList;
    }

    protected function configure()
    {
        $this->setName('project:bug4:initialize');
        $this->setDescription('Updates the store for Bug 4.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Beginning update for "slideshow broken".');

        $jsAssetUrl = $this->state->emulateAreaCode(
            Area::AREA_FRONTEND,
            function () {
                return $this->assetRepository->getUrlWithParams(base64_decode('U3dpZnRPdHRlcl9NYWlsY2hpbXA6OmpzL21haWxjaGltcC5qcw=='), [
                    'theme' => 'SwiftOtter/OtterSplash'
                ]);
            }
        );

        $this->configWriter->save('mailchimp/general/active', 1);
        $this->configWriter->save(base64_decode('bWFpbGNoaW1wL2dlbmVyYWwvbWFpbGNoaW1wanN1cmw='), $jsAssetUrl);

        $output->writeln('Clearing appropriate caches');
        $this->cacheManager->flush(['layout', 'block_html', 'full_page']);
        foreach (static::CLEAN_DIRECTORIES as $directory) {
            $output->writeln('Deleting ' . $this->directoryList->getRoot() . '/' . $directory);
            exec('rm -rf ' . $this->directoryList->getRoot() . '/' . $directory);
        }

        $output->writeln("<fg=black;bg=green> The update is complete and this is ready for you. You got this! </>");
    }
}
