<?php

namespace SwiftOtter\GiftCard\Console;

use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Console\Cli;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\File\Csv;
use Psr\Log\InvalidArgumentException;
use SwiftOtter\GiftCard\Api\Data\GiftCardInterface;
use SwiftOtter\GiftCard\Api\GiftCardRepositoryInterface;
use SwiftOtter\GiftCard\Model\Import\GiftCardImport;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportGiftCards extends Command
{
    public const FILE = 'file';

    /** @var State */
    private $state;

    /** @var Csv */
    private $csv;

    /** @var GiftCardImport */
    private $giftCardImport;

    /** @var GiftCardRepositoryInterface */
    private $giftCardRepository;

    /** @var GiftCardInterface */
    private $giftCard;

    public function __construct(
        State $state,
        Csv $csv,
        GiftCardImport $giftCardImport,
        GiftCardRepositoryInterface $giftCardRepository,
        GiftCardInterface $giftCard,
        string $name = null
    ) {
        $this->state = $state;
        $this->csv = $csv;
        $this->giftCardImport = $giftCardImport;
        $this->giftCardRepository = $giftCardRepository;
        $this->giftCard = $giftCard;

        parent::__construct($name);
    }

    protected function configure()
    {
        $options = [
            new InputOption(
                self::FILE,
                null,
                InputOption::VALUE_REQUIRED,
                'The CSV file to import'
            )
        ];

        $this->setName('swiftotter:giftcard:import')
            ->setDescription('Imports Gift Card data from CSV file. Place a file named giftcards.csv in the root directory and the script will pick it up.')
            ->setDefinition($options);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->state->setAreaCode(Area::AREA_FRONTEND);
        } catch (LocalizedException $exception) {
            return $output->writeln("<error>{$exception->getMessage()}</error>");
        }

        if ($file = $input->getOption(self::FILE)) {
            try {
                $csvData = $this->csv->getData($file);
                $rows = $this->giftCardImport->import($csvData);

                foreach ($rows as $row) {
                    try {
                        $this->giftCardRepository->save($this->giftCard->setData($row));
                    } catch (CouldNotSaveException $exception) {
                        $output->writeln("<error>{$exception->getMessage()}</error>");
                        return Cli::RETURN_FAILURE;
                    }
                }
            } catch (\Exception $exception) {
                $output->writeln($exception->getMessage());
                return Cli::RETURN_FAILURE;
            }
        } else {
            $output->writeln("<error>You must include the file name.</error>");
            return Cli::RETURN_FAILURE;
        }

        $output->writeln("<info>Data successfully imported.</info>");
        return Cli::RETURN_SUCCESS;
    }
}
