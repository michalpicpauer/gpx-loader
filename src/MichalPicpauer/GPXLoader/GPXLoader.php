<?php

namespace MichalPicpauer\GPXLoader;

/**
 * This library loads and parse gpx file
 *
 * @author Michal Picapuer <michalpicpauer@gmail.com>
 */
class GPXLoader implements \Iterator
{

    /**
     * @var string
     */
    private $trackName;

    /**
     * @var int
     */
    private $position;

    /**
     * @var Point[]
     */
    private $points = array();

    public function __construct($file)
    {
        $this->position = 0;
        $this->parseGPXFile($file);
    }

    /**
     * @return string
     */
    public function getTrackName()
    {
        return $this->trackName;
    }

    /**
     * @return Point[]
     */
    public function getPoints()
    {
        return $this->points;
    }

    public function rewind() {
        $this->position = 0;
    }

    /**
     * @return Point
     */
    public function current() {
        return $this->points[$this->position];
    }

    /**
     * @return int
     */
    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    /**
     * @return bool
     */
    public function valid() {
        return isset($this->points[$this->position]);
    }


    private function parseGPXFile($file) {
        if (file_exists($file)) {
            $gpx = simplexml_load_file($file);

            // save name
            if (isset($gpx->trk->name)) {
                $this->trackName = (string) $gpx->trk->name;
            }

            // push points to array
            foreach($gpx->trk->trkseg->children() as $trkpt) {
                $this->points[] = new Point(floatval($trkpt['lat']), floatval($trkpt['lon']));
            }

        } else {
            throw new \Exception('The file does not exist.');
        }
    }
}