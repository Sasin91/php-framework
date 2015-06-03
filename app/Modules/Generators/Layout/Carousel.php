<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 20-01-15
 * Time: 12:45
 */

namespace Modules\Generators\Layout;

use Core\Models\Layout\CarouselModel;
use Libraries\DatabaseInteractive\TextHandling\Parsedown\Parsedown;

class Carousel
{
    private $page;
    private $url;
    private $numImages;
    private $numText;
    private $text;
    private $images;
    private $numSlides;

    public function createWithArguments(array $params = array())
    {
        $this->page = $params[0];
        $this->url = $params[1];

        $carousel = '';

        $this->images = $this->getImages($this->page);
        $this->text = $this->getText($this->page);
        $this->numImages = $this->getNum($this->images);
        $this->numText = $this->getNum($this->text);
        $this->numSlides = $this->numImages;
        $carousel .= '
                <div class="carousel fade-carousel slide"  data-ride="carousel" id="bs-carousel">
                    <ol class="carousel-indicators">
                    ';
        for ($i = 0; $i < $this->numSlides; $i++) {
            $carousel .= '<li data-target="#bs-carousel" data-slide-to="' . $i . '"></li>';
        }
        $carousel .= '</ol>';

        $carousel .= '<div class="carousel-inner">';

        for ($i = 0; $i < $this->numSlides; $i++) {
            $carousel .= $this->genSlide($i);
        }

        $carousel .= '</div>';

        $carousel .=
            '    <script type="text/javascript">
         $("#bs-carousel").carousel({
         interval : 7000,
         pause: false
         });
        $("#1").addClass("active");
    </script>';

        $carousel .= '</div>';
        return $carousel;
    }


    private function genSlide($i)
    {
        $slide = '';

        $slide .= '<div id="' . $i . '" class="item slides">';

        $slide .= $this->genSlideImage($i);

        $slide .= '<div class="slide-' . $i . '">';

        $slide .= '</div>';

        $slide .= $this->genSlideText($i);

        $slide .= '</div>';

        return $slide;
    }

    private function genSlideText($i)
    {
        if ($this->numText > 0) {

            $carousel = '';
            $carousel .= '<div class="hero">';
            $carousel .= '<hgroup>';
            $carousel .= $this->markdown($this->text[$i]->text);
            $carousel .= '</hgroup>';
            $carousel .= '<a href="' . $this->text[$i]->btn_link . '" class="btn btn-hero btn-lg" role="button">' . $this->text[$i]->btn_label . '</a>';
            $carousel .= '</div>';
            return $carousel;
        }
    }


    private function genSlideImage($i)
    {
        if ($this->numImages > 0) {
            return '<a href="' . $this->text[$i]->btn_link . '"><img class="img-responsive centered" src="' . $this->url . '/img/carousel/' . $this->page . '/' . $this->images[$i]->image . '"></a>';
        }
    }

    private function markdown($text)
    {
        $parsedown = new Parsedown();
        return $parsedown->instance()->setBreaksEnabled(true)->text($text);
    }

    private function getImages($page)
    {
        return CarouselModel::getImageBy(array(':page' => $page));
    }

    private function getText($page)
    {
        return CarouselModel::getTextBy(array(':page' => $page));
    }

    private function getNum($arrays)
    {
        $exists = array();
        foreach ($arrays as $key => $array) {
            foreach ($array as $string) {
                $value = trim($string);
                if (!empty($value)) {
                    $exists[] .= $value;
                }
            }
        }
        return count($exists);
    }

}