<?php

namespace Rompetomp\InertiaBundle\Service;

use Rompetomp\InertiaBundle\LazyProp;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface InertiaInterface.
 *
 * @author  Hannes Vermeire <hannes@codedor.be>
 *
 * @since   2019-08-09
 */
interface InertiaInterface
{
    /**
     * Adds global component properties for the templating system.
     */
    public function share(string $key, mixed $value = null): void;

    public function getShared(string $key = null): mixed;

    /**
     * Adds global view data for the templating system.
     */
    public function viewData(string $key, mixed $value = null): void;

    public function getViewData(string $key = null): mixed;

    public function version(string $version): void;

    /**
     * Adds a context for the serializer.
     */
    public function context(string $key, mixed $value = null): void;

    public function getContext(string $key = null): mixed;

    public function getVersion(): ?string;

    public function setRootView(string $rootView): void;

    public function getRootView(): string;

    /**
     * Set if it will use ssr.
     */
    public function useSsr(bool $useSsr): void;

    /**
     * Check if it using ssr.
     */
    public function isSsr(): bool;

    /**
     * Set the ssr url where it will fetch its content.
     */
    public function setSsrUrl(string $url): void;

    /**
     * Get the ssr url where it will fetch its content.
     */
    public function getSsrUrl(): string;

    /**
     * @param callable|array|string $callback
     */
    public function lazy(callable|array|string $callback): LazyProp;

    /**
     * @param string $component component name
     * @param array<array-key, mixed> $props     component properties
     * @param array<array-key, mixed> $viewData  templating view data
     * @param array<array-key, mixed> $context   serialization context
     * @param string|null $url       custom url
     */
    public function render(string $component, array $props = [], array $viewData = [], array $context = [], string $url = null): Response;
}
