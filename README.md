KML Filter
=================
About
-----
* website: https://github.com/HTSolution/Ushahidi-plugin-kmlfilter
* description: Adds filter for the report by KML layers
* author: HTSolution Pvt. Ltd.
* author website: http://himalayantechies.com

Description
-----------------
* Adds layer filter to reports index filter page 
* Reference for KML files 
	
	http://www.gadm.org

* For your own KML file to work refer to another branch (master)

Installation
----------------
* Copy the entire /kmlfilter/ directory into your /plugins/ directory.
* Activate the plugin.


__NOTE:__
* KML file requires 

	<Placemark>
		<styleUrl></styleUrl>
	</Placemark>
	
	tag with unique id inside <styleUrl></styleUrl> for each <Placemark>
	and coordinates should be in format

	<Placemark>
		<MultiGeometry>
			<Polygon>
				<outerBoundaryIs>
					<LinearRing>
						<coordinates>
						</coordinates>
					</LinearRing>
				</outerBoundaryIs>
			</Polygon>
		</MultiGeometry>
	</Placemark>

* You can get KML files from 

	http://www.gadm.org
	
* If activating plugin does not show location filter on main page then search for

	if (layerType !== Ushahidi.KML) {
	
and its related
	
	}
	
code in media/js/ushahidi.js and comment out these two lines

* If plugin does not filter timeline by location then search for 
	
	// Fetch the timeline data
	$query = 'SELECT UNIX_TIMESTAMP('.$select_date_text.') AS time, COUNT(id) AS number '
	. 'FROM '.$this->table_prefix.'incident '
		. 'WHERE incident_active = 1 '.$incident_id_in.' '
	. 'GROUP BY '.$groupby_date_text;

in application/controllers/json.php inside function timeline() and above the $query add
	
	Event::run('ushahidi_filter.timeline_update_query', $incident_id_in);
