<?php

class JsonAdapterWorker extends BaseWorker {

    /*
     * Attributes
     */
    protected $_default_directory;
    protected $_view_controllers_array;
    protected $_class_style_array;
    protected $_ios_structure;

    protected $_primary_color = "#ffffff";          // main color
    protected $_primary_color_dark = "#000000";     // status bar color

    protected $_flashy_color = "#abcabc";           // flashy color
    protected $_flashy_color_dark = "#defdef";      // flashy color

    protected $_background_color = "#eeeeee";       // background fragment color
    protected $_background_color_dark = "#dddddd";  // background fragment color

    protected $_title_color = "#555555";
    protected $_title_font = "";

    protected $_subtitle_color = "#999999";
    protected $_subtitle_font = "";

    protected $_text_color = "#555555";
    protected $_text_font = "";

    protected $_white = "#ffffff";
    protected $_black = "#333333";

    /*
    protected $_view_controller_adapter = [
        "WebViewController" => "fr.smartapps.smartguide.fragment.WebViewFragment",
        "CustomMapController" => "fr.smartapps.smartguide.fragment.MapFragment",
        "POIListViewController" => "fr.smartapps.smartguide.fragment.ListPOIFragment",
        "KeypadViewController" => "fr.smartapps.smartguide.fragment.KeypadPOIFragment",
    ];
    */

    /*
     * Constructor
     */
    public function __construct($_base_directory, $_input_directory, $_output_directory, $_default_directory)
    {
        parent::__construct($_base_directory, $_input_directory, $_output_directory);
        $this->_default_directory = $_default_directory;
        $this->_view_controllers_array = json_decode(file_get_contents($this->_default_directory.DS.get_class().DS."view_controllers_android.json"))->viewControllerDescriptions;
        $this->_class_style_array = json_decode(file_get_contents($this->_default_directory.DS.get_class().DS."class_style_android.json"))->classStyleDescriptions;
        $this->_ios_structure = json_decode(file_get_contents($this->_input_directory.DS."app_structure.json"));
    }

    /*
     * Get all viewControllers from ios structure to android
     */
    protected function getAllAndroidViewControllers() {
        $iosViewControllers = $this->_ios_structure->viewControllerDescriptions;
        $result = [];
        foreach ($iosViewControllers as $iosViewController)
        {
            // LIST VIEW
            if (FileUtils::endsWith($iosViewController->description->class, "POIListViewController"))
            {
                // Multiple tours
                if (sizeof($iosViewController->description->tours) > 1)
                {
                    $androidViewControllerMain = $this->getAndroidViewControllerByAction("fr.smartapps.smartguide.fragment.ToggleFragment");
                    $androidViewControllerMain->idx = $iosViewController->idx;
                    $androidViewControllerMain->descriptions->title = $iosViewController->description->title;
                    $androidViewControllerMain->descriptions->tabTitle = $iosViewController->description->tabBarTitle;

                    for ($i = 0; $i < sizeof($iosViewController->description->tours); $i++)
                    {
                        $androidViewControllerChild = $this->getAndroidViewControllerByAction("fr.smartapps.smartguide.fragment.ListPOIFragment");
                        $androidViewControllerChild->descriptions->title = $iosViewController->description->titles[$i];
                        $androidViewControllerChild->descriptions->tabTitle = $iosViewController->description->titles[$i];
                        $androidViewControllerChild->descriptions->tours[] = $iosViewController->description->tours[$i];
                        $androidViewControllerChild->idx = $iosViewController->idx * 100 + $i + 1;
                        $androidViewControllerMain->descriptions->viewControllers[] = $iosViewController->idx * 100 + $i + 1;
                        $result[] = $androidViewControllerChild;
                    }
                    $result[] = $androidViewControllerMain;

                }

                // Solo tour
                else
                {
                    $androidViewController = $this->getAndroidViewControllerByAction("fr.smartapps.smartguide.fragment.ListPOIFragment");
                    $androidViewController->idx = $iosViewController->idx;
                    $androidViewController->descriptions->title = $iosViewController->description->title;
                    $androidViewController->descriptions->tabTitle = $iosViewController->description->tabBarTitle;
                    $androidViewController->descriptions->tours = $iosViewController->description->tours[0];
                    $result[] = $androidViewController;

                }

            }

            // MAP VIEW
            else if (FileUtils::endsWith($iosViewController->description->class, "CustomMapController"))
            {
                // Multiple tours
                if (sizeof($iosViewController->description->tours) > 1)
                {
                    $androidViewControllerMain = $this->getAndroidViewControllerByAction("fr.smartapps.smartguide.fragment.ToggleFragment");
                    $androidViewControllerMain->idx = $iosViewController->idx;
                    $androidViewControllerMain->descriptions->title = $iosViewController->description->title;
                    $androidViewControllerMain->descriptions->tabTitle = $iosViewController->description->tabBarTitle;

                    for ($i = 0; $i < sizeof($iosViewController->description->tours); $i++)
                    {
                        $androidViewControllerChild = $this->getAndroidViewControllerByAction("fr.smartapps.smartguide.fragment.MapFragment");
                        $androidViewControllerChild->descriptions->title = $iosViewController->description->titles[$i];
                        $androidViewControllerChild->descriptions->tabTitle = $iosViewController->description->titles[$i];
                        $androidViewControllerChild->descriptions->tours[] = $iosViewController->description->tours[$i];
                        $androidViewControllerChild->idx = $iosViewController->idx * 100 + $i + 1;
                        $androidViewControllerMain->descriptions->viewControllers[] = $iosViewController->idx * 100 + $i + 1;
                        $result[] = $androidViewControllerChild;
                    }
                    $result[] = $androidViewControllerMain;

                }

                // Solo tour
                else
                {
                    $androidViewController = $this->getAndroidViewControllerByAction("fr.smartapps.smartguide.fragment.MapFragment");
                    $androidViewController->idx = $iosViewController->idx;
                    $androidViewController->descriptions->title = $iosViewController->description->title;
                    $androidViewController->descriptions->tabTitle = $iosViewController->description->tabBarTitle;
                    $androidViewController->descriptions->tours = $iosViewController->description->tours[0];
                    $result[] = $androidViewController;

                }

            }



        }
        return $result;
    }

    /*
     *@return array of main controllers idx
     */
    protected function getMainControllersIdx() {
        $iosViewControllers = $this->_ios_structure->viewControllerDescriptions;
        foreach ($iosViewControllers as $viewController)
        {
            if (FileUtils::endsWith($viewController->description->class, "CustomTabBarController")) {
                return $viewController->description->controllers;
            }
        }
    }

    protected function getAndroidViewControllerByAction($action) {
        $androidViewControllers = $this->_view_controllers_array;
        foreach ($androidViewControllers as $viewController)
        {
            if (FileUtils::endsWith($viewController->action, $action)) {
                return clone $viewController;
            }
        }
    }

    /*
     * classStyleDescriptions
     * @return array
     */
    protected function getAndroidClassStyleDescriptions()
    {
        $classStyleDescriptions = $this->_class_style_array->classStyleDescriptions;
        foreach ($classStyleDescriptions as $position => $classStyleDescription)
        {
            foreach ($classStyleDescription->descriptions as $key => $value)
            {
                if (is_string($value) && property_exists(get_class(), "_".$value)) {
                    $value = "_".$value;
                    $classStyleDescription->descriptions->$key = $this->$value;
                }
                else {
                    $classStyleDescription->descriptions->$key = $value;
                }
            }
            $viewControllers[$position] = $classStyleDescription;
        }
        return $classStyleDescriptions;

    }

    /**
     * viewControllerDescriptions
     * @return array
     */
    protected function parseColorValue($controllers)
    {
        foreach ($controllers as $position => $controller)
        {
            foreach ($controller->descriptions as $key => $value)
            {
                if (is_string($value) && property_exists(get_class(), "_".$value)) {
                    $value = "_".$value;
                    $controller->descriptions->$key = $this->$value;
                }
                else {
                    $controller->descriptions->$key = $value;
                }
            }
            $controllers[$position] = $controller;

        }
        return $controllers;
    }


    /*
     * Start process
     */
    public function run()
    {
        var_dump($this->getAllAndroidViewControllers());
    }

}