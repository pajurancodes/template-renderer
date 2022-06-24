<?php

namespace PajuranCodes\Template\Renderer\Php;

use function rtrim;
use function is_dir;
use function extract;
use function is_file;
use function ob_start;
use function file_exists;
use function ob_end_clean;
use function ob_get_contents;
use PajuranCodes\Template\Renderer\{
    TemplateRendererInterface,
    ContextCollectionInterface,
    TemplateRenderer as BaseTemplateRenderer,
};

/**
 * @author pajurancodes
 */
class TemplateRenderer extends BaseTemplateRenderer implements TemplateRendererInterface {

    /**
     * A path where to look for template files.
     *
     * @var string
     */
    private readonly string $templatesPath;

    /**
     *
     * @param string $templatesPath A path where to look for template files.
     * @param ContextCollectionInterface $contextCollection A collection of values passed to 
     * template files as context parameters.
     */
    public function __construct(
        string $templatesPath,
        ContextCollectionInterface $contextCollection
    ) {
        $this->templatesPath = $this->buildTemplatesPath($templatesPath);

        parent::__construct($contextCollection);
    }

    /**
     * @inheritDoc
     */
    public function render(string $templateName, array $context = []): string {
        $templateFile = $this->templatesPath . $this->buildTemplateName($templateName);

        $this
            ->validateTemplateFile($templateFile)
            ->saveContextValuesToContextCollection($context)
        ;

        return $this->renderTemplateFile($templateFile);
    }

    /**
     * Build the path where to look for template files.
     *
     * @param string $templatesPath A path where to look for template files.
     * @return string The path where to look for template files, with a trailing slash.
     * @throws \InvalidArgumentException The path where to look for template files is empty.
     * @throws \RuntimeException The path where to look for template files is not a directory.
     */
    private function buildTemplatesPath(string $templatesPath): string {
        if (empty($templatesPath)) {
            throw new \InvalidArgumentException(
                    'A path where to look for templates must be provided.'
            );
        }

        if (!is_dir($templatesPath)) {
            throw new \RuntimeException(
                    'The path where to look for template files ("' . $templatesPath . '") '
                    . 'must point to an existing directory.'
            );
        }

        return rtrim($templatesPath, '/\\') . '/';
    }

    /**
     * Validate the path to a template file.
     *
     * @param string $filename A path to a template file.
     * @return static
     * @throws \RuntimeException The given path points to a non-existent file.
     * @throws \RuntimeException The given path points to a non-regular file.
     */
    private function validateTemplateFile(string $filename): static {
        if (!file_exists($filename)) {
            throw new \RuntimeException(
                    'The template file "' . $filename . '" could not be found.'
            );
        }

        if (!is_file($filename)) {
            throw new \RuntimeException(
                    'The template file "' . $filename . '" is not a regular file.'
            );
        }

        return $this;
    }

    /**
     * Render a template file.
     *
     * @param string $filename A path to a template file.
     * @return string The rendered content of the template file.
     */
    private function renderTemplateFile(string $filename): string {
        ob_start();

        /*
         * Import the context variables into the current symbol table.
         * 
         * This function treats keys as variable names and values 
         * as variable values. For each key/value pair it will create 
         * a variable in the current symbol table, subject to flags 
         * and prefix parameters.
         * 
         * An associative array must be used. A numerically 
         * indexed array will not produce results unless 
         * EXTR_PREFIX_ALL or EXTR_PREFIX_INVALID is used.
         * 
         * @todo Because of the condition imposed by 'extract', it must be ensured somewhere, that the collection elements form an associative array (maybe in 'PajuranCodes\Template\Renderer\ContextCollection').
         */
        extract($this->contextCollection->all());

        require $filename;

        $content = ob_get_contents();

        ob_end_clean();

        return $content;
    }

}
