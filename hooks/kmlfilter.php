<?php defined('SYSPATH') or die('No direct script access.');

class kmlfilter {

	public function __construct()
	{
		// Hook into routing
		Event::add('system.pre_controller', array($this, 'add'));
	}
	
	public function add()
	{
		if (Router::$controller == 'reports' AND Router::$method == 'index') {
			Event::add('ushahidi_action.report_filters_ui', array($this, '_filter_ui'));
			Event::add('ushahidi_action.report_js_filterReportsAction', array($this, '_filter_js'));
			Event::add('ushahidi_filter.fetch_incidents_set_params', array($this,'_add_kml_filter'));
			Event::add('ushahidi_filter.layer_features', array($this,'_add_layer_features'));
			//plugin::add_stylesheet('downloadreports/views/css/download_reports');
		} else {
			Event::add('ushahidi_action.report_js_filterReportsAction', array($this, '_filter_js'));
			Event::add('ushahidi_filter.fetch_incidents_set_params', array($this,'_add_kml_filter'));
			Event::add('ushahidi_filter.layer_features', array($this,'_add_layer_features'));
		}
		if (Router::$controller == 'main' AND Router::$method == 'index') {
			Event::add('ushahidi_action.main_sidebar_post_filters', array($this,'_main_sidebar_kmlfilter'));
		}
		
		//Event::add('ushahidi_filter.json_replace_markers', _json_replace_markers);
	}

	public function _filter_js() {
		$view = new View('kmlfilter/report_filter_js');
		$view->render(true);
	}
	
	public function _filter_ui() {
		$view = new View('kmlfilter/report_filter_ui');
		$view->render(true);
	}
	
	public function _add_kml_filter() {
		$params = Event::$data;
		Event::$data = kmlfilter_helper::addkmlfilter($params);
	}
	
	public function _add_layer_features() {
		$params = Event::$data;
		Event::$data = kmlfilter_helper::addlayerfeatures($params);
	}
	
	public function _main_sidebar_kmlfilter() {
		$view = new View('kmlfilter/main_sidebar_post_filter');
		$layers = array();
		$config_layers = Kohana::config('map.layers'); // use config/map layers if set
		if ($config_layers == $layers) {
			foreach (ORM::factory('layer')->where('layer_visible', 1)->find_all() as $layer)
			{
				$layers[$layer->id] = array($layer->layer_name, $layer->layer_color,
				$layer->layer_url, $layer->layer_file);
			}
		}
		else
		{
			$layers = $config_layers;
		}
		$jsFile = new View('kmlfilter/main_sidebar_js');
		$view->js = $jsFile;
		$view->kmlfilterlayers = $layers;
		$view->render(true);
	}
	
	/*public function _json_replace_markers() {
		$params = Event::$data;
		Event::$data = kmlfilter_helper::addkmlfilter($params);
	}*/

}

new kmlfilter();
