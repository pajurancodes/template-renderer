<?php

namespace PajuranCodes\Template\Renderer;

use PajuranCodes\Template\Renderer\ContextCollectionInterface;

/**
 * An interface to a renderer of template files.
 *
 * @author pajurancodes
 */
interface TemplateRendererInterface {

    /**
     * Render a template file.
     *
     * @param string $templateName The name of a template file.
     * @param (string|int|float|bool|null|object|array)[] $context (optional) A list of key/value pairs 
     * which are passed to the template file as context parameters.
     * @return string The rendered content of the template file.
     */
    public function render(string $templateName, array $context = []): string;

    /**
     * Get the collection of values passed to 
     * template files as context parameters.
     *
     * @return ContextCollectionInterface
     */
    public function getContextCollection(): ContextCollectionInterface;
}
