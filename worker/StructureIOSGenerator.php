<?php
/**
 * Created by PhpStorm.
 * User: vchann
 * Date: 03/03/2017
 * Time: 18:35
 */

class StructureIOSGenerator extends BaseWorker
{
    /*
     * Attributes
     */
    protected $_input_file_master = "structure_master.json";
    protected $_input_file_generic_view_controller = "ios_generic_view_controller.json";
    protected $_input_file_generic_class_style = "ios_generic_class_style.json";
    protected $_input_file_pixate = "ios_pixate.css";

    protected $_structure_master;
    protected $_generic_view_controller;
    protected $_generic_class_style;

    protected $_output_structure_ios;
    protected $_output_pixate_ios;
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
        $this->_output_structure_ios = new StdClass();
        $this->setDefaultStructure();
        $this->setAllViewControllers();

        print_r($this->_output_structure_ios);
        file_put_contents($this->_output_directory . DS . "app_structure_ios.json", json_encode($this->_output_structure_ios, JSON_PRETTY_PRINT));
        file_put_contents($this->_output_directory . DS . "pixate.css", $this->getPixateString());

    }

    /*
     * Init structure
     */
    public function setDefaultStructure()
    {
        $this->_output_structure_ios->schemaVersion = 1;
        $this->_output_structure_ios->startControllerId = 1;
        $this->_output_structure_ios->title = $this->_structure_master->title;
        $this->_output_structure_ios->backgroundSplash = "launch_image.png";
        $this->_output_structure_ios->classStyleDescriptions = $this->_generic_class_style;
    }

    /*
     * ViewControllerDescription
     */
    public function setAllViewControllers()
    {
        $this->_output_structure_ios->viewControllerDescriptions = array();
        foreach ($this->_structure_master->menus as $position => $menu)
        {
            $this->setViewController($menu, $this->_idx_count);
            $this->_idx_count++;
        }

        $this->_output_structure_ios->viewControllerDescriptions = array_values($this->_output_structure_ios->viewControllerDescriptions);
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
            case "list":
                $genericViewController = $this->getGenericViewController("list");
                $vc->descriptions->action = $genericViewController->action;
                $vc->descriptions->tours = $menu->tours;
                break;

            case "web":
                $genericViewController = $this->getGenericViewController("web");
                $vc->descriptions->action = $genericViewController->action;
                $vc->descriptions->url = $menu->url;
                break;
        }

        foreach (get_object_vars($genericViewController->descriptions) as $key => $value) {
            $vc->descriptions->$key = $value;
        }

        $this->_output_structure_ios->viewControllerDescriptions[$idx] = $vc;
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
     * Colors & Fonts
     */
    public function getGenericViewControllerString()
    {
        $content = file_get_contents($this->_input_directory.DS.$this->_input_file_generic_view_controller);
        $content = str_replace(array_keys(get_object_vars($this->_structure_master->colors)), array_values(get_object_vars($this->_structure_master->colors)), $content);
        $content = str_replace(array_keys(get_object_vars($this->_structure_master->fonts)), array_values(get_object_vars($this->_structure_master->fonts)), $content);

        return $content;
    }

    public function getGenericClassStyleString()
    {
        $content = file_get_contents($this->_input_directory.DS.$this->_input_file_generic_class_style);
        $content = str_replace(array_keys(get_object_vars($this->_structure_master->colors)), array_values(get_object_vars($this->_structure_master->colors)), $content);
        $content = str_replace(array_keys(get_object_vars($this->_structure_master->fonts)), array_values(get_object_vars($this->_structure_master->fonts)), $content);

        return $content;
    }

    public function getPixateString()
    {
        $content = file_get_contents($this->_input_directory.DS.$this->_input_file_pixate);
        $content = str_replace(array_keys(get_object_vars($this->_structure_master->colors)), array_values(get_object_vars($this->_structure_master->colors)), $content);
        $content = str_replace(array_keys(get_object_vars($this->_structure_master->fonts)), array_values(get_object_vars($this->_structure_master->fonts)), $content);

        return $content;
    }
}