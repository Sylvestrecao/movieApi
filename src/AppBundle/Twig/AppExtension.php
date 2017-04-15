<?php // src/AppBundle/Twig/AppExtension.php
namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('truncate', array($this, 'truncateFilter')),
            new \Twig_SimpleFilter('sortMovies', array($this, 'sortFilter')),
            new \Twig_SimpleFilter('formatedDate', array($this, 'formatedDate')),
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

    /**
     * sort array item by release date
     * @param $content
     * @return array
     */
    public function sortFilter($content)
    {
        $allMovies = $content->body->movie_credits->cast;

        for($i = 0; $i < count($allMovies); $i++){
            for($j = $i+1; $j < count($allMovies); $j++){
                if($allMovies[$i]->release_date < $allMovies[$j]->release_date){
                    $temp = $allMovies[$i];
                    $allMovies[$i] = $allMovies[$j];
                    $allMovies[$j] = $temp;
                }
            }
        }
        return $allMovies;
    }

    /**
     * sort array item by release date
     * @param $content
     * @return string 
     */
    public function formatedDate($content)
    {
        $date = $content->format('\à H:i:s \l\e d/m/Y ');
        return $date;
    }

    public function getName()
    {
        return 'app_extension';
    }
}