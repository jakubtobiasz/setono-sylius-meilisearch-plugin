<?php

declare(strict_types=1);

namespace Setono\SyliusMeilisearchPlugin\Tests\Unit\Document\Metadata;

use PHPUnit\Framework\TestCase;
use Setono\SyliusMeilisearchPlugin\Document\Attribute\Facet;
use Setono\SyliusMeilisearchPlugin\Document\Attribute\Filterable;
use Setono\SyliusMeilisearchPlugin\Document\Attribute\Searchable;
use Setono\SyliusMeilisearchPlugin\Document\Attribute\Sortable;
use Setono\SyliusMeilisearchPlugin\Document\Document as BaseDocument;
use Setono\SyliusMeilisearchPlugin\Document\Metadata\Metadata;

final class MetadataTest extends TestCase
{
    /**
     * @test
     */
    public function it_resolves_attributes(): void
    {
        $metadata = new Metadata(Document::class);

        self::assertCount(3, $metadata->getFilterableAttributes());
        self::assertArrayHasKey('size', $metadata->getFilterableAttributes());
        self::assertArrayHasKey('price', $metadata->getFilterableAttributes());
        self::assertArrayHasKey('taxons', $metadata->getFilterableAttributes());

        self::assertCount(2, $metadata->getFacetableAttributes());
        self::assertArrayHasKey('size', $metadata->getFacetableAttributes());
        self::assertArrayHasKey('price', $metadata->getFacetableAttributes());

        self::assertCount(1, $metadata->getSearchableAttributes());
        self::assertArrayHasKey('name', $metadata->getSearchableAttributes());

        self::assertCount(1, $metadata->getSortableAttributes());
        self::assertArrayHasKey('price', $metadata->getSortableAttributes());
    }
}

final class Document extends BaseDocument
{
    #[Searchable]
    public ?string $name = null;

    #[Facet]
    public ?string $size = null;

    #[Facet]
    #[Sortable]
    public ?int $price = null;

    #[Filterable]
    public array $taxons = [];
}
