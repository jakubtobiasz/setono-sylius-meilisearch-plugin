<?php

declare(strict_types=1);

namespace Setono\SyliusMeilisearchPlugin\DataMapper;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Setono\SyliusMeilisearchPlugin\Document\Document;
use Setono\SyliusMeilisearchPlugin\Document\ImageUrlsAwareInterface;
use Setono\SyliusMeilisearchPlugin\Model\IndexableInterface;
use Setono\SyliusMeilisearchPlugin\Provider\IndexScope\IndexScope;
use Sylius\Component\Core\Model\ImagesAwareInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Webmozart\Assert\Assert;

final class ImageUrlsDataMapper implements DataMapperInterface
{
    /**
     * @param array<class-string<IndexableInterface>, string> $entityToFilterSetMapping
     */
    public function __construct(
        private readonly CacheManager $cacheManager,
        private readonly array $entityToFilterSetMapping = [],
        // todo add this to the plugin configuration
        private readonly string $defaultFilterSet = 'sylius_large',
    ) {
    }

    public function map(
        IndexableInterface $source,
        Document $target,
        IndexScope $indexScope,
        array $context = [],
    ): void {
        Assert::true(
            $this->supports($source, $target, $indexScope, $context),
            'The given $source and $target is not supported',
        );

        $imageUrls = [];

        foreach ($source->getImages() as $image) {
            $imageUrls[] = $this->cacheManager->getBrowserPath(
                (string) $image->getPath(),
                $this->entityToFilterSetMapping[$source::class] ?? $this->defaultFilterSet,
                [],
                null,
                UrlGeneratorInterface::ABSOLUTE_PATH,
            );
        }

        $target->setImageUrls($imageUrls);
    }

    /**
     * @psalm-assert-if-true ImagesAwareInterface $source
     * @psalm-assert-if-true ImageUrlsAwareInterface $target
     */
    public function supports(
        IndexableInterface $source,
        Document $target,
        IndexScope $indexScope,
        array $context = [],
    ): bool {
        return $source instanceof ImagesAwareInterface && $target instanceof ImageUrlsAwareInterface;
    }
}
