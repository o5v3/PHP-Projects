<?php
function getMap() {
    $map = file_get_contents("aoc.txt");
    $map = explode("\n", $map);
    return $map;
};

class Element {
    public static $universe = [];
    public $orbits = [];
    public $name = "";
    public $parent;

    function __construct($name, $parent = null) {
        $this->name = $name;
        $this->orbits = [];
        $this->parent = $parent;
        if ($parent !== null) {
            $this->orbits = $parent->orbits;
            array_push($this->orbits, $parent);
        };

    }

    public function __toString() {
        return $this->name;
    }

    public function countOrbits() {
        return count($this->orbits);
    }
};

function processOrbit($data) {
    $data = rtrim($data);
    $fullData = explode(")", $data);
    $emtA = false;
    $emtB = false;

    foreach (Element::$universe as $element) {
        if ($element->name == $fullData[0]) {
            $emtA = true;
        };
        if ($element->name == $fullData[1]) {
            $emtB = true;
        };
    };

    if (!$emtA) {
        Element::$universe[$fullData[0]] = new Element($fullData[0]);
    };
    if ($emtB) {
        array_push(Element::$universe[$fullData[1]]->orbits, Element::$universe[$fullData[0]]);
        Element::$universe[$fullData[1]]->parent = Element::$universe[$fullData[0]];
    } else {
        Element::$universe[$fullData[1]] = new Element($fullData[1], Element::$universe[$fullData[0]]);
    };
};

function deepCount($element) {
    if (count($element->orbits) == 0) {
        return 0;
    } else {
        return deepCount($element->parent) + count($element->orbits);
    };
}

function countDeep($element, $steps = 0) {
    if ($element->parent === null) {
        return $steps;
    } else {
        $steps += 1;
        return countDeep($element->parent, $steps);
    };    
}

function visualize($universe) {
    foreach ($universe as $element) {
        echo "$element orbits: ";
        foreach ($element->orbits as $orbit) {
            echo "$orbit, ";
        }
        echo "<br>";
    }
}

function main() {
    $map = getMap();
    foreach ($map as $orbit) {
        processOrbit($orbit);
    };

    $orbitCount = 0;
    /*
    echo "<pre>";
    print_r(Element::$universe);
    echo "</pre>";
    */

    foreach (Element::$universe as $element) {
        $orbitCount += deepCount($element);
        //echo $element->name . " has " . $element->countOrbits() . " orbits<br>";
    };
    //visualize(Element::$universe);

    return $orbitCount;
}
Element::$universe = [];

echo main();
?>