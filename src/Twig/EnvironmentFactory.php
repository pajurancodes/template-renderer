<?php

namespace PajuranCodes\Template\Renderer\Twig;

use Twig\{
    Environment as TwigEnvironment,
    Loader\LoaderInterface as TwigLoaderInterface,
    Extension\ExtensionInterface as TwigExtensionInterface,
};
use PajuranCodes\Template\Renderer\Twig\EnvironmentFactoryInterface;

/**
 * A factory to create a Twig environment.
 *
 * @author pajurancodes
 */
class EnvironmentFactory implements EnvironmentFactoryInterface {

    /**
     * A list of extensions.
     *
     * @var TwigExtensionInterface[]
     */
    private readonly array $extensions;

    /**
     *
     * @param TwigLoaderInterface $loader A loader responsible for loading templates from a resource.
     * @param mixed[] $environmentOptions (optional) A list of environment options.
     * @param TwigExtensionInterface[] $extensions (optional) A list of extensions.
     */
    public function __construct(
        private readonly TwigLoaderInterface $loader,
        private readonly array $environmentOptions = [],
        array $extensions = []
    ) {
        $this->extensions = $this->buildExtensions($extensions);
    }

    /**
     * @inheritDoc
     */
    public function createEnvironment(): TwigEnvironment {
        $environment = new TwigEnvironment($this->loader, $this->environmentOptions);

        $this->addExtensionsToEnvironment($environment);

        return $environment;
    }

    /**
     * Build the extensions list.
     *
     * @param TwigExtensionInterface[] $extensions A list of extensions.
     * @return TwigExtensionInterface[] The extensions list.
     * @throws \UnexpectedValueException One of the values of the extensions list is empty.
     * @throws \UnexpectedValueException One of the values of the extensions 
     * list is not an instance of Twig\Extension\ExtensionInterface.
     */
    private function buildExtensions(array $extensions): array {
        if (empty($extensions)) {
            return [];
        }

        foreach ($extensions as $key => $value) {
            if (empty($value)) {
                throw new \UnexpectedValueException(
                        'A value for the item with the key "' . $key . '" '
                        . 'in the Twig extensions list must be provided.'
                );
            }

            if (!($value instanceof TwigExtensionInterface)) {
                throw new \UnexpectedValueException(
                        'The value of the item with the key "' . $key . '" '
                        . 'in the Twig extensions list must be an instance '
                        . 'of Twig\Extension\ExtensionInterface.'
                );
            }
        }

        return $extensions;
    }

    /**
     * Add all extensions in the extensions list to a Twig environment.
     * 
     * @param TwigEnvironment $environment A Twig environment.
     * @return static
     */
    private function addExtensionsToEnvironment(TwigEnvironment $environment): static {
        foreach ($this->extensions as $extension) {
            $environment->addExtension($extension);
        }

        return $this;
    }

    /**
     * Get the loader responsible for loading templates from a resource..
     *
     * @return TwigLoaderInterface
     */
    public function getLoader(): TwigLoaderInterface {
        return $this->loader;
    }

    /**
     * Get the list of environment options.
     *
     * @return mixed[]
     */
    public function getEnvironmentOptions() {
        return $this->environmentOptions;
    }

    /**
     * Get the list of extensions.
     *
     * @return TwigExtensionInterface[]
     */
    public function getExtensions(): array {
        return $this->extensions;
    }

}
