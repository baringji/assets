<?php

declare(strict_types=1);

namespace Inpsyde\Assets\OutputFilter;

use Inpsyde\Assets\Asset;

class AttributesOutputFilter implements AssetOutputFilter
{
    private const ROOT_ELEMENT_START = '<root>';
    private const ROOT_ELEMENT_END = '</root>';

    public function __invoke(string $html, Asset $asset): string
    {
        $attributes = $asset->attributes();
        if (count($attributes) > 0) {
            $html = $this->wrapHtmlIntoRoot($html);

            $doc = new \DOMDocument();
            libxml_use_internal_errors(true);
            // @phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
            @$doc->loadHTML(
                mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8"),
                LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
            );
            libxml_clear_errors();

            $scripts = $doc->getElementsByTagName('script');
            foreach ($scripts as $script) {
                // Only extend the <script> with "src"-attribute and
                // don't extend inline <script></script> before and after.
                if (!$script->hasAttribute('src')) {
                    continue;
                }
                $this->applyAttributes($script, $attributes);
            }

            $html = $this->removeRootElement($doc->saveHTML());
        }

        return $html;
    }

    /**
     * Wrapping multiple scripts into a root-element
     * to be able to load it via DOMDocument.
     *
     * @param string $html
     *
     * @return string
     */
    protected function wrapHtmlIntoRoot(string $html): string
    {
        return self::ROOT_ELEMENT_START . $html . self::ROOT_ELEMENT_END;
    }

    /**
     * Remove root element and return original HTML.
     *
     * @param string $html
     *
     * @return string
     * @see AttributesOutputFilter::wrapHtmlIntoRoot()
     *
     */
    protected function removeRootElement(string $html): string
    {
        $regex = '~' . self::ROOT_ELEMENT_START . '(.+?)' . self::ROOT_ELEMENT_END . '~';
        preg_match($regex, $html, $matches);

        return $matches[1];
    }

    /**
     * @param \DOMNode $script
     * @param array $attributes
     */
    protected function applyAttributes(\DOMNode $script, array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $key = esc_attr($key);
            if ($script->hasAttribute($key)) {
                continue;
            }
            if (is_bool($value) && !$value) {
                continue;
            }
            $value = is_bool($value)
                ? esc_attr($key)
                : esc_attr($value);

            $script->setAttribute($key, $value);
        }
    }
}
