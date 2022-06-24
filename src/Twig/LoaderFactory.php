<?php

namespace PajuranCodes\Template\Renderer\Twig;

use function is_dir;
use function is_string;
use function file_exists;
use Twig\Loader\{
    ArrayLoader as TwigArrayLoader,
    ChainLoader as TwigChainLoader,
    LoaderInterface as TwigLoaderInterface,
    FilesystemLoader as TwigFilesystemLoader,
};
use PajuranCodes\Template\Renderer\Twig\LoaderFactoryInterface;

/**
 * A factory to create a Twig loader.
 *
 * @author pajurancodes
 */
class LoaderFactory implements LoaderFactoryInterface {

    /**
     * @inheritDoc
     */
    public function createFilesystemLoader(
        string|array $templatesPaths = [],
        ?string $rootPath = null,
        bool $enableNamespaces = false
    ): TwigFilesystemLoader {
        if (empty($templatesPaths)) {
            $templatesPaths = [];
        }

        if (is_string($templatesPaths)) {
            $templatesPaths = [$templatesPaths];
        }

        // Create a file system loader with no templates paths, but with a root path.
        $loader = new TwigFilesystemLoader([], $rootPath);

        /*
         * Validate the templates paths and save them into the loader, along
         * with the corresponding namespaces, e.g. with the non-empty string
         * keys, if namespaced templates should be used.
         */
        foreach ($templatesPaths as $namespace => $path) {
            /*
             * Validate the templates path.
             */
            if (empty($path)) {
                throw new \UnexpectedValueException(
                        'A path must be provided in the templates '
                        . 'paths list, at key "' . $namespace . '".'
                );
            }

            if (!is_string($path)) {
                throw new \UnexpectedValueException(
                        'The path in the templates paths list at '
                        . 'key "' . $namespace . '" must be a string.'
                );
            }

            if (!file_exists($path)) {
                throw new \UnexpectedValueException(
                        'The path "' . $path . '" at key "' . $namespace . '" in '
                        . 'the templates paths list is invalid.'
                );
            }

            if (!is_dir($path)) {
                throw new \UnexpectedValueException(
                        'The path "' . $path . '" must point to a '
                        . 'templates directory, not to a file.'
                );
            }

            /*
             * Save the templates path into the loader, along with its namespace.
             */
            if ($enableNamespaces) {
                /*
                 * Validate the templates namespace.
                 */
                if (empty($namespace)) {
                    throw new \UnexpectedValueException(
                            'A namespace for the path "' . $path . '" in the templates '
                            . 'paths list at key "' . $namespace . '" must be provided.'
                    );
                }

                if (!is_string($namespace)) {
                    throw new \UnexpectedValueException(
                            'The namespace "' . $namespace . '" in the templates '
                            . 'paths list at key "' . $namespace . '" must be a string.'
                    );
                }

                $loader->addPath($path, $namespace);
            } else {
                $loader->addPath($path);
            }
        }

        return $loader;
    }

    /**
     * @inheritDoc
     */
    public function createArrayLoader(array $templates = []): TwigArrayLoader {
        // Validate the templates list.
        foreach ($templates as $name => $code) {
            if (empty($name)) {
                throw new \UnexpectedValueException(
                        'A template name for the template code "' . $code . '" '
                        . 'in the templates list must be provided.'
                );
            }

            if (!is_string($name)) {
                throw new \UnexpectedValueException(
                        'The template name "' . $name . '" in '
                        . 'the templates list must be a string.'
                );
            }
        }

        return new TwigArrayLoader($templates);
    }

    /**
     * @inheritDoc
     */
    public function createChainLoader(array $loaders = []): TwigChainLoader {
        foreach ($loaders as $key => $loader) {
            if (!($loader instanceof TwigLoaderInterface)) {
                throw new \UnexpectedValueException(
                        'The loader in the provided loaders list at key "' . $key . '" '
                        . 'must be an instance of Twig\Loader\LoaderInterface.'
                );
            }
        }

        return new TwigChainLoader($loaders);
    }

}
