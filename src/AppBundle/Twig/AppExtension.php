<?php // src/AppBundle/Twig/AppExtension.php
namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('truncate', array($this, 'truncateFilter')),
            new \Twig_SimpleFilter('sortMovies', array($this, 'sortFilter')),
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
     * Truncate a string
     * @param $content
     * @return string
     */
    public function sortFilter($content)
    {
        $allMovies = $content->body->movie_credits->cast;

        /*for($i = 0; $i < count($allMovies) - 1; $i++){
            if($allMovies[$i]->release_date < $allMovies[$i+1]->release_date){
                $temp = $allMovies[$i];
                $allMovies[$i] = $allMovies[$i+1];
                $allMovies[$i+1] = $temp;
                $i = 0;
            }
        }*/
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

    public function getName()
    {
        return 'app_extension';
    }
}