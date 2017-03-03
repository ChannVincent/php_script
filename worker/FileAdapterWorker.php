<?php

class FileAdapterWorker extends BaseWorker
{
    /*
     * Attributes
     */
    protected $_default_directory;

    protected $_adapter_sample = array(
        "icon_tab1@2x.png" => "icon_tab1.png",
        "icon_tab2@2x.png" => "icon_tab2.png",
        "icon_tab3@2x.png" => "icon_tab3.png",
        "icon_tab4@2x.png" => "icon_tab4.png",
        "icon_tab5@2x.png" => "icon_tab5.png",
        "tab_bg@2x.png" => "tab_background.png",
        "launch_image@2x.png" => "launch_image.png",

        "list_cell_bkg_stretchable@2x.png" => "list_cell_background.png",
        "list_cell_bkg_stretchable_active@2x.png" => "list_cell_background_selected.png",
        "map_disclosure@2x.png" => "map_disclosure.png",
        "map_pin@2x.png" => "map_pin.png",
        "navbar_portrait@2x.png" => "navbar_portrait.png",
        "player_earspeaker_off@2x.png" => "player_earspeaker_off.png",
        "player_earspeaker_on@2x.png" => "player_earspeaker_on.png",
        "player_hp_off@2x.png" => "player_hp_off.png",
        "player_hp_on@2x.png" => "player_hp_on.png",

        "player_map@2x.png" => "player_map.png",
        "player_more@2x.png" => "player_more.png",
        "player_slideshow@2x.png" => "player_slideshow.png",
        "player_transcript@2x.png" => "player_transcript.png",
        "player_video@2x.png" => "player_video.png",
    );

    protected $_adapter_default = array(
        "arrow_list.png" => "arrow_list.png",
        "btn_pause.png" => "btn_pause.png",
        "btn_play.png" => "btn_play.png",
        "ic_back.png" => "ic_back.png",
        "ic_burger.png" => "ic_burger.png",
        "ic_close.png" => "ic_close.png",
        "map_disclosure.png" => "map_disclosure.png",
        "spinner_off.png" => "spinner_off.png",
        "spinner_on.png" => "spinner_on.png",
    );

    protected $_adapter_selected = array(
        "player_map@2x.png" => "player_map_selected.png",
        "player_more@2x.png" => "player_more_selected.png",
        "player_slideshow@2x.png" => "player_slideshow_selected.png",
        "player_transcript@2x.png" => "player_transcript_selected.png",
        "player_video@2x.png" => "player_video_selected.png",
    );

    protected $_adapter_resources = array(
        "ic_launcher.png" => "ic_launcher.png"
    );
    protected $_folder_resources = array(
        "drawable-xhdpi",
        "drawable-hdpi",
        "drawable-mdpi",
        "drawable-ldpi",
    );

    /*
     * constructor
     */
    public function __construct($_base_directory, $_input_directory, $_output_directory, $_default_directory)
    {
        parent::__construct($_base_directory, $_input_directory, $_output_directory);
        $this->_default_directory = $_default_directory;
    }

    /*
     * Methods
     */
    protected function copySample() {
        foreach($this->_adapter_sample as $src => $dst) {
            $this->copyFile($src, $dst);
        }
    }

    protected function copyDefault() {
        foreach($this->_adapter_default as $src => $dst) {
            $this->copyDefaultFile($this->_default_directory.DS.get_class().DS.$src, $this->_output_directory.DS.$dst);
        }
    }

    protected function copySelected() {
        foreach($this->_adapter_selected as $src => $dst) {
            PngUtils::pngBrightness($this->_input_directory.DS.$src, $this->_output_directory.DS.$dst, 20);
        }
    }

    protected function copyResources() {
        $resources_directory = $this->_output_directory.DS."res";
        foreach ($this->_folder_resources as $folder) {
            mkdir($resources_directory.DS.$folder, 0777, true);
            foreach($this->_adapter_resources as $src => $dst) {
                $this->copyFile($src, "res".DS.$folder.DS.$dst);
            }
        }
    }

    /*
     * Start process
     */
    public function run() {
        $this->copySample();
        $this->copyDefault();
        $this->copySelected();
        $this->copyResources();
    }

    /*
     * Log
     */
    public function __toString() {
        return parent::__toString();
    }


}