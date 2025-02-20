<?php

declare(strict_types=1);

namespace Setono\SyliusMeilisearchPlugin\Provider\IndexScope;

use Setono\SyliusMeilisearchPlugin\Config\Index;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class TaxonIndexScopeProvider implements IndexScopeProviderInterface
{
    public function __construct(
        private readonly RepositoryInterface $localeRepository,
        private readonly LocaleContextInterface $localeContext,
    ) {
    }

    public function getAll(Index $index): iterable
    {
        /** @var LocaleInterface[] $locales */
        $locales = $this->localeRepository->findAll();

        foreach ($locales as $locale) {
            yield new IndexScope(index: $index, localeCode: $locale->getCode());
        }
    }

    public function getFromContext(Index $index): IndexScope
    {
        return new IndexScope(index: $index, localeCode: $this->localeContext->getLocaleCode());
    }

    public function supports(Index $index): bool
    {
        return count($index->entities) === 1 && $index->hasEntity(TaxonInterface::class);
    }
}
