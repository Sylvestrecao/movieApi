<?php // src/AppBundle/Twig/AppExtension.php
namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('truncate', array($this, 'truncateFilter')),
        );
    }
    /**
     * Truncate a string
     * @param $content
     * @param int $limit
     * @param string $ending
     * @return string
     */
    public function truncateFilter($content, $max_words, $ending = "...")
    {
        $text = $content;
        $words = explode(' ', $text);
        if (count($words) > $max_words) {
            return implode(' ', array_slice($words, 0, $max_words)) . $ending;
        }
        return $text;
    }

    public function getName()
    {
        return 'app_extension';
    }
}