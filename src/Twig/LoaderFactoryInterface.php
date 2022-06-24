<?php

namespace PajuranCodes\Template\Renderer\Twig;

use Twig\Loader\{
    ArrayLoader as TwigArrayLoader,
    ChainLoader as TwigChainLoader,
    LoaderInterface as TwigLoaderInterface,
    FilesystemLoader as TwigFilesystemLoader,
};

/**
 * An interface to a Twig loader factory.
 *
 * @author pajurancodes
 */
interface LoaderFactoryInterface {

    /**
     * Create a file system loader for loading templates from the file system.
     *
     * It looks for the templates in an array of directories and can also support
     * namespaced templates, allowing to group the templates under different
     * namespaces which have their own template paths.
     *
     * If the given root path is set to null, then Twig uses the current
     * working directory for relative paths, e.g. the result of getcwd().
     *
     * Each templates path in the given templates paths list must be a non-empty string
     * and point to an already created directory - e.g. no directory is created by default.
     * If namespaced templates are not used, then this list can be an indexed or an associative
     * array, with the paths as values. If namespaced templates are used, then each key must
     * represent a namespace, and the value a path.
     *
     * Even if absolute templates paths can be used, it is preferred to use
     * relative paths, e.g. paths relative to the templates root path, because
     * then the cache keys are made independent of the project root directory.
     *
     * @param string|string[] $templatesPaths (optional) A path or an array of paths where to 
     * look for templates.
     * @param string|null $rootPath (optional) The root path to which all templates paths are 
     * relative. Set to null for the current working directory, e.g. for getcwd().
     * @param bool $enableNamespaces (optional) A flag to indicate if namespaced templates 
     * should be used.
     * @return TwigFilesystemLoader A file system loader.
     * @throws \UnexpectedValueException One of the templates paths is empty.
     * @throws \UnexpectedValueException One of the templates paths is not a string.
     * @throws \UnexpectedValueException One of the templates paths is not a valid path.
     * @throws \UnexpectedValueException One of the templates paths is not a directory.
     * @throws \UnexpectedValueException One of the keys to which a 
     * template path is assigned is empty.
     * @throws \UnexpectedValueException One of the keys to which a 
     * template path is assigned is not a string.
     */
    public function createFilesystemLoader(
        string|array $templatesPaths = [],
        ?string $rootPath = null,
        bool $enableNamespaces = false
    ): TwigFilesystemLoader;

    /**
     * Create an array loader for loading a template from a PHP array.
     *
     * The passed argument must be an associative array with each item
     * containing a template name as key and a template source code as value.
     *
     * When using this loader with a cache mechanism, a new cache key is 
     * generated each time a template content "changes" (the cache key 
     * being the source code of the template). In order to avoid that 
     * the cache grows out of control, the old cache file must be 
     * manually removed.
     *
     * @param array $templates (optional) An associative array with each item 
     * containing a template name as key and a template source code as value.
     * @return TwigArrayLoader An array loader.
     * @throws \UnexpectedValueException One of the template names is empty.
     * @throws \UnexpectedValueException One of the template names is not a string.
     */
    public function createArrayLoader(array $templates = []): TwigArrayLoader;

    /**
     * Create a chain loader for loading of templates from multiple loaders.
     *
     * Twig uses each loader to look for a template 
     * and returns as soon as the template is found.
     *
     * @param TwigLoaderInterface[] $loaders (optional) A list of loaders.
     * @return TwigChainLoader A chain loader.
     * @throws \UnexpectedValueException One of the values in the loaders list is 
     * not an instance of Twig\Loader\LoaderInterface.
     */
    public function createChainLoader(array $loaders = []): TwigChainLoader;
}
