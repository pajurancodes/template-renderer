<?php

namespace PajuranCodes\Template\Renderer;

use function trim;
use PajuranCodes\Template\Renderer\ContextCollectionInterface;

/**
 * A renderer of template files.
 *
 * @author pajurancodes
 */
abstract class TemplateRenderer {

    /**
     *
     * @param ContextCollectionInterface $contextCollection A collection of values passed to 
     * template files as context parameters.
     */
    public function __construct(
        protected readonly ContextCollectionInterface $contextCollection
    ) {
        
    }

    /**
     * Get the collection of values passed to 
     * template files as context parameters.
     *
     * @return ContextCollectionInterface
     */
    public function getContextCollection(): ContextCollectionInterface {
        return $this->contextCollection;
    }

    /**
     * Build the name of a template file.
     *
     * @param string $templateName The name of a template file.
     * @return string The name of a template file without leading and trailing slashes.
     * @throws \InvalidArgumentException The name of a template file is empty.
     */
    protected function buildTemplateName(string $templateName): string {
        if (empty($templateName)) {
            throw new \InvalidArgumentException('A template name must be provided.');
        }

        return trim($templateName, '/\\');
    }

    /**
     * Save the values which will be passed to template 
     * files as context parameters to the context collection.
     * 
     * @param (string|int|float|bool|null|object|array)[] $context A list of key/value pairs 
     * which are passed to a template file as context parameters.
     * @return static
     */
    protected function saveContextValuesToContextCollection(array $context): static {
        foreach ($context as $key => $value) {
            $this->contextCollection->set($key, $value);
        }

        return $this;
    }

}
