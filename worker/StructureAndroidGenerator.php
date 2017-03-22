<?php

/**
 * Created by PhpStorm.
 * User: vchann
 * Date: 02/03/2017
 * Time: 10:58
 */
class StructureAndroidGenerator extends BaseWorker
{
    /*
     * Attributes
     */
    protected $_input_file_master = "structure_master.json";
    protected $_input_file_generic_view_controller = "android_generic_view_controller.json";
    protected $_input_file_generic_class_style = "android_generic_class_style.json";

    protected $_structure_master;
    protected $_generic_view_controller;
    protected $_generic_class_style;

    protected $_output_structure_android;
    protected $_idx_count = 1;


    /*
     * constructor
     */
    public function __construct($_base_directory, $_input_directory, $_output_directory)
    {
        parent::__construct($_base_directory, $_input_directory, $_output_directory);
        $this->_structure_master = json_decode(file_get_contents($this->_input_directory.DS.$this->_input_file_master));
        $this->_generic_view_controller = json_decode($this->getGenericViewControllerString());
        $this->_generic_class_style = json_decode($this->getGenericClassStyleString());
    }

    /*
     * Start process
     */
    public function run()
    {
        $this->_output_structure_android = new StdClass();
        $this->setDefaultStructure();
        $this->setAllViewControllers();

        print_r($this->_output_structure_android);
        file_put_contents($this->_output_directory . DS . "app_structure_android.json", json_encode($this->_output_structure_android, JSON_PRETTY_PRINT));
    }

    /*
     * ViewControllerDescription
     */
    public function setAllViewControllers()
    {
        $this->_output_structure_android->viewControllerDescriptions = array();
        foreach ($this->_structure_master->menus as $position => $menu)
        {
            $this->setViewController($menu, $this->_idx_count);
            $this->_output_structure_android->viewControllers[$position] = $this->_idx_count;
            $this->_idx_count++;
        }

        $this->_output_structure_android->viewControllerDescriptions = array_values($this->_output_structure_android->viewControllerDescriptions);
        $this->_output_structure_android->viewControllers = array_values($this->_output_structure_android->viewControllers);
    }

    public function setViewController($menu, $idx)
    {
        $vc = new StdClass();
        $vc->idx = $idx;
        $vc->descriptions = new StdClass();
        $vc->descriptions->title = $menu->title;
        $vc->descriptions->tabTitle = $menu->tabTitle;
        $vc->descriptions->tabIcon = "icon_tab" . $idx . ".png";
        $vc->descriptions->selectedTabIcon = "icon_selected_tab" . $idx . ".png";

        $genericViewController = new stdClass();
        switch ($menu->name) {

            case "web";
                $genericViewController = $this->getGenericViewController("web");
                $vc->action = $genericViewController->action;
                $vc->descriptions->url = $menu->url;
                break;

            case "list";
                $genericViewController = $this->getGenericViewController("list");
                $vc->action = $genericViewController->action;
                $vc->descriptions->tours = $menu->tours;
                break;

            case "map":
                $genericViewController = $this->getGenericViewController("map");
                $vc->action = $genericViewController->action;
                $vc->descriptions->tour = $menu->tour;
                break;

            case "mapbox";
                $genericViewController = $this->getGenericViewController("mapbox");
                $vc->action = $genericViewController->action;
                $vc->descriptions->tour = $menu->tour;
                break;

            case "keypad";
                $genericViewController = $this->getGenericViewController("keypad");
                $vc->action = $genericViewController->action;
                $vc->descriptions->tours = $menu->tours;
                break;

            case "toggle";
                $genericViewController = $this->getGenericViewController("toggle");
                $vc->action = $genericViewController->action;
                $array = array();
                foreach ($menu->menus as $position => $sub_menu)
                {
                    if ($sub_menu->name == "mapbox")
                    {
                        $vc->descriptions->tabMode = "fragmentContainer";
                    }
                    $this->setSubViewController($sub_menu, $idx * 10 + $position);
                    $array[$position] = $idx * 10 + $position;
                }
                $vc->descriptions->viewControllers = $array;
                break;

            case "switcher";
                $genericViewController = $this->getGenericViewController("switcher");
                $vc->action = $genericViewController->action;
                $array = array();
                foreach ($menu->menus as $position => $sub_menu)
                {
                    $this->setSubViewController($sub_menu, $idx * 10 + $position);
                    $array[$position] = $idx * 10 + $position;
                }
                $vc->descriptions->viewControllers = $array;
                break;

            case "poiShortText";
                $genericViewController = $this->getGenericViewController("poiShortText");
                $vc->action = $genericViewController->action;
                $vc->descriptions->poiIdx = $menu->poiIdx;
                $vc->descriptions->tourIdx = $menu->tourIdx;
                break;

            case "poiLongText";
                $genericViewController = $this->getGenericViewController("poiLongText");
                $vc->action = $genericViewController->action;
                $vc->descriptions->poiIdx = $menu->poiIdx;
                $vc->descriptions->tourIdx = $menu->tourIdx;
                break;

            case "poiFatherSon";
                $genericViewController = $this->getGenericViewController("poiFatherSon");
                $vc->action = $genericViewController->action;
                $vc->descriptions->poiIdx = $menu->poiIdx;
                $vc->descriptions->tourIdx = $menu->tourIdx;
                break;

            case "poiFullScreen";
                $genericViewController = $this->getGenericViewController("poiFullScreen");
                $vc->action = $genericViewController->action;
                $vc->descriptions->poiIdx = $menu->poiIdx;
                $vc->descriptions->tourIdx = $menu->tourIdx;
                break;

            case "poiWebView";
                $genericViewController = $this->getGenericViewController("poiWebView");
                $vc->action = $genericViewController->action;
                $vc->descriptions->poiIdx = $menu->poiIdx;
                $vc->descriptions->tourIdx = $menu->tourIdx;
                break;

            case "selfie";
                $genericViewController = $this->getGenericViewController("selfie");
                $vc->action = $genericViewController->action;
                break;

            case "lexique";
                $genericViewController = $this->getGenericViewController("lexique");
                $vc->action = $genericViewController->action;
                break;

            case "agenda";
                $genericViewController = $this->getGenericViewController("agenda");
                $vc->action = $genericViewController->action;
                $vc->descriptions->url = $menu->url;
                break;

            case "game";
                $genericViewController = $this->getGenericViewController("game");
                $vc->action = $genericViewController->action;
                $vc->descriptions->url = $menu->url;
                break;
        }

        // add image background for list framework
        if ($this->_structure_master->framework == "list")
        {
            $vc->descriptions->image = "cellule_rubrique" . $idx . "@2x.png";
        }

        foreach (get_object_vars($genericViewController->descriptions) as $key => $value) {
            $vc->descriptions->$key = $value;
        }

        $this->_output_structure_android->viewControllerDescriptions[$idx] = $vc;
    }

    public function setSubViewController($menu, $idx)
    {
        $menu->tabTitle = $menu->title;
        return $this->setViewController($menu, $idx);
    }

    public function getGenericViewController($name)
    {
        foreach ($this->_generic_view_controller as $viewController)
        {
            if (strcmp($viewController->name, $name) == 0)
            {
                return $viewController;
            }
        }
        return null;
    }

    /*
     * Init structure
     */
    public function setDefaultStructure()
    {
        $this->_output_structure_android->schemaVersion = 1;
        $this->_output_structure_android->title = $this->_structure_master->title;
        $this->_output_structure_android->backgroundSplash = "launch_image.png";

        // set framework
        switch ($this->_structure_master->framework)
        {
            case "tab":
                $this->_output_structure_android->action = "fr.smartapps.smartguide.activity.PagerTabActivity";
                break;

            case "multi-language":
                $this->_output_structure_android->action = "fr.smartapps.smartguide.activity.TwoStepsPackageActivity";
                break;

            case "list":
                $this->_output_structure_android->action = "fr.smartapps.smartguide.activity.ListActivity";
                break;
        }

        $this->_output_structure_android->viewControllers = array();
        $this->_output_structure_android->classStyleDescriptions = $this->_generic_class_style;
    }

    /*
     * Colors & Fonts
     */
    public function getGenericViewControllerString()
    {
        $content = file_get_contents($this->_input_directory.DS.$this->_input_file_generic_view_controller);
        $content = str_replace(array_keys(get_object_vars($this->_structure_master->colors)), array_values(get_object_vars($this->_structure_master->colors)), $content);
        $content = str_replace(array_keys(get_object_vars($this->_structure_master->fonts)), array_values(get_object_vars($this->_structure_master->fonts)), $content);
        $content = str_replace(array_keys(get_object_vars($this->_structure_master->background)), array_values(get_object_vars($this->_structure_master->background)), $content);

        return $content;
    }

    public function getGenericClassStyleString()
    {
        $content = file_get_contents($this->_input_directory.DS.$this->_input_file_generic_class_style);
        $content = str_replace(array_keys(get_object_vars($this->_structure_master->colors)), array_values(get_object_vars($this->_structure_master->colors)), $content);
        $content = str_replace(array_keys(get_object_vars($this->_structure_master->fonts)), array_values(get_object_vars($this->_structure_master->fonts)), $content);
        $content = str_replace(array_keys(get_object_vars($this->_structure_master->background)), array_values(get_object_vars($this->_structure_master->background)), $content);

        return $content;
    }

    /*
     * Log
     */
    public function __toString()
    {
        return parent::__toString();
    }
}