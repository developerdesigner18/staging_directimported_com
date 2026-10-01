<?php

namespace App\Services;

/**
 * Allowlist HTML sanitizer for untrusted rich text (AI output, imported listing descriptions).
 *
 * The output is rebuilt from scratch: only the tags below are emitted, with no attributes
 * except a checked href on links, and all text is escaped. Anything else is either dropped
 * with its content (scripts, embeds, forms...) or unwrapped to its text.
 */
class HtmlSanitizer
{
    private const ALLOWED_TAGS = [
        'p', 'br', 'hr', 'h2', 'h3', 'h4', 'h5', 'h6', 'ul', 'ol', 'li', 'strong', 'b', 'em', 'i', 'u',
        'blockquote', 'a', 'table', 'thead', 'tbody', 'tr', 'th', 'td',
    ];

    /** Removed together with everything inside them. */
    private const DROPPED_TAGS = [
        'script', 'style', 'iframe', 'frame', 'frameset', 'object', 'embed', 'applet', 'noscript', 'template',
        'svg', 'math', 'form', 'input', 'button', 'textarea', 'select', 'option', 'head', 'title', 'meta', 'link', 'base',
    ];

    private const VOID_TAGS = ['br', 'hr'];

    /** Tags mapped to an allowed equivalent. */
    private const RENAMED_TAGS = ['h1' => 'h2'];

    public function clean(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return $html;
        }

        $previous = libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        $doc->loadHTML(
            '<?xml encoding="utf-8"?><div>' . $html . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $doc->getElementsByTagName('div')->item(0);

        return $root ? trim($this->render($root)) : '';
    }

    private function render(\DOMNode $node): string
    {
        $html = '';

        foreach ($node->childNodes as $child) {
            if ($child instanceof \DOMText) {
                $html .= htmlspecialchars($child->nodeValue, ENT_NOQUOTES | ENT_HTML5, 'UTF-8');
                continue;
            }

            // Comments, processing instructions, CDATA, etc. are dropped
            if (!$child instanceof \DOMElement) {
                continue;
            }

            $tag = strtolower($child->nodeName);
            $tag = self::RENAMED_TAGS[$tag] ?? $tag;

            if (in_array($tag, self::DROPPED_TAGS, true)) {
                continue;
            }

            if (!in_array($tag, self::ALLOWED_TAGS, true)) {
                // Unknown wrapper (div, span, font...): keep its content only
                $html .= $this->render($child);
                continue;
            }

            if (in_array($tag, self::VOID_TAGS, true)) {
                $html .= "<{$tag}>";
                continue;
            }

            $html .= "<{$tag}{$this->attributes($tag, $child)}>{$this->render($child)}</{$tag}>";
        }

        return $html;
    }

    private function attributes(string $tag, \DOMElement $element): string
    {
        if ($tag !== 'a') {
            return '';
        }

        $href = trim($element->getAttribute('href'));
        if (!preg_match('#^(https?://|mailto:)#i', $href)) {
            return '';
        }

        return ' href="' . htmlspecialchars($href, ENT_QUOTES | ENT_HTML5, 'UTF-8') . '" target="_blank" rel="nofollow noopener noreferrer"';
    }
}
