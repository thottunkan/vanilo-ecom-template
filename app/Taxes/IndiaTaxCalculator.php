<?php
namespace App\Taxes;

use Log;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Vanilo\Adjustments\Adjusters\SimpleTax;
use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Contracts\DetailedAmount;
use Vanilo\Support\Dto\DetailedAmount as DetailedAmountDto;
use Vanilo\Taxes\Contracts\TaxCalculator;

class IndiaTaxCalculator implements TaxCalculator
{
    public static function getName(): string
    {
        return 'Indian GST';
    }

    public function getAdjuster(?array $configuration = null): ?object
    {
        $rate = (float) ($configuration['rate'] ?? 18);

        $adjuster = new SimpleTax(
            $rate,
            (bool) ($configuration['included'] ?? false)
        );

        $adjuster->setTitle($configuration['title'] ?? 'GST');

        return $adjuster;
    }

    public function calculate(?object $subject = null, ?array $configuration = null): DetailedAmount
    {
        Log::info('IndiaTaxCalculator called', [
            'configuration' => $configuration,
        ]);
        if (!$subject instanceof Adjustable) {
            return new DetailedAmountDto(0);
        }

        $adjuster = $this->getAdjuster($configuration);

        return DetailedAmountDto::fromArray([
            [
                'title' => $configuration['title'] ?? 'GST',
                'amount' => $adjuster->createAdjustment($subject)->getAmount(),
            ]
        ]);
    }

    

    public function getSchema(): Schema
    {
        return Expect::structure([
            'rate' => Expect::float()->required(),
            'title' => Expect::string()->default('GST'),
            'included' => Expect::bool()->default(false),
        ]);
    }

    public function getSchemaSample(array $mergeWith = null): array
    {
        return array_merge([
            'rate' => 18,
            'title' => 'GST',
            'included' => false,
        ], $mergeWith ?? []);
    }
}