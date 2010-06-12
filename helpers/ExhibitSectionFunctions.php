<?php

/**
 * Returns the current exhibit section.
 *
 * @return ExhibitSection|null
 **/
function exhibit_builder_get_current_section()
{
    return __v()->exhibitSection;
}

/**
 * Sets the current exhibit section.
 *
 * @param ExhibitSection|null $exhibitSection
 * @return void
 **/
function exhibit_builder_set_current_section($exhibitSection=null)
{
    __v()->exhibitSection = $exhibitSection;
}

/**
 * Returns whether an exhibit section is the current exhibit section.
 *
 * @param ExhibitSection|null $section
 * @return boolean
 **/
function exhibit_builder_is_current_section($section)
{
    $currentSection = exhibit_builder_get_current_section();
    return ($section === $currentSection || ($section && $currentSection && $section->id == $currentSection->id));
}

/**
 * Returns whether the exhibit section has pages.
 *
 * @param ExhibitSection $section
 * @return boolean
 **/
function exhibit_builder_section_has_pages($section) 
{
    return $section->hasPages();
}

/**
 * Returns an exhibit section by id
 *
 * @param $sectionId The id of the exhibit section
 * @return ExhibitSection
 **/
function exhibit_builder_get_exhibit_section_by_id($sectionId) 
{
    return get_db()->getTable('ExhibitSection')->find($sectionId);
}

/**
 * Returns the HTML code of the exhibit section navigation
 *
 * @param Exhibit|null $exhibit If null, will use the current exhibit.
 * @return string
 **/
function exhibit_builder_section_nav($exhibit=null)
{
    if (!$exhibit) {
        if (!($exhibit = exhibit_builder_get_current_exhibit())) {
            return;
        }    
    }
    $html = '<ul class="exhibit-section-nav">';
    foreach ($exhibit->Sections as $key => $section) {
        if ($section->hasPages()) {
            $html .= '<li' . (exhibit_builder_is_current_section($section) ? ' class="current"' : ''). '><a class="exhibit-section-title" href="' . html_escape(exhibit_builder_exhibit_uri($exhibit, $section)) . '">' . html_escape($section->title) . '</a></li>';            
        }      
    }
    $html .= '</ul>';
    return $html;
}

/**
 * Returns the HTML for a nested navigation for exhibit sections and pages
 *
 * @param Exhibit|null $exhibit If null, will use the current exhibit
 * @param boolean $showAllPages
 * @return string
 **/
function exhibit_builder_nested_nav($exhibit = null, $showAllPages = false)
{
    if (!$exhibit) {
        if (!($exhibit = exhibit_builder_get_current_exhibit())) {
            return;
        }    
    }
    $html = '<ul class="exhibit-section-nav">';
    foreach ($exhibit->Sections as $section) {
        $html .= '<li class="exhibit-nested-section' . (exhibit_builder_is_current_section($section) ? ' current' : '') . '"><a class="exhibit-section-title" href="' . html_escape(exhibit_builder_exhibit_uri($exhibit, $section)) . '">' . html_escape($section->title) . '</a>';
        if ($showAllPages || exhibit_builder_is_current_section($section)) {
            $html .= exhibit_builder_page_nav($section);
        }
        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}

/**
* Gets the current exhibit section
*
* @return ExhibitSection|null
**/
function get_current_exhibit_section()
{
    return exhibit_builder_get_current_section();
}

/**
 * Sets the current exhibit section
 *
 * @see loop_exhibit_sections()
 * @param ExhibitSection
 * @return void
 **/
function set_current_exhibit_section(ExhibitSection $exhibitSection)
{
   exhibit_builder_set_current_section($exhibitSection);
}

/**
 * Sets the exhibit sections for loop
 *
 * @param array|null $exhibitSections
 * @return void
 **/
function set_exhibit_sections_for_loop($exhibitSections = null)
{
    __v()->exhibitSections = $exhibitSections;
}

/**
 * Sets the exhibit sections for loop to the exhibit sections for the current exhibit
 *
 * @param Exhibit|null $exhibit
 * @return void
 **/
function set_exhibit_sections_for_loop_by_exhibit($exhibit = null) 
{
    if (!$exhibit) {
        $exhibit = get_current_exhibit();
    }    
    if ($exhibit) {
        set_exhibit_sections_for_loop($exhibit->Sections);
    }
}

/**
 * Get the set of exhibit sections for the current loop.
 * 
 * @return array
 **/
function get_exhibit_sections_for_loop()
{
    return __v()->exhibitSections;
}

/**
 * Loops through exhibit sections assigned to the view.
 * 
 * @return mixed The current exhibit section
 */
function loop_exhibit_sections()
{
    return loop_records('exhibitSections', get_exhibit_sections_for_loop(), 'set_current_exhibit_section');
}

/**
 * Determine whether or not there are any exhibit sections in the database.
 * 
 * @return boolean
 **/
function has_exhibit_sections()
{
    return (total_exhibit_sections() > 0);    
}

/**
 * Determines whether there are any exhibit sections for loop.
 * @return boolean
 */
function has_exhibit_sections_for_loop()
{
    $view = __v();
    return ($view->exhibitSections and count($view->exhibitSections));
}

/**
  * Returns the total number of exhibit sections in the database
  *
  * @return integer
  **/
 function total_exhibit_sections() 
 {	
 	return get_db()->getTable('ExhibitSection')->count();
 }

/**
* Gets a property from an exhibit section
*
* @param string $propertyName
* @param array $options
* @param Exhibit $exhibitSection  The exhibit section
* @return mixed The exhibit section property value
**/
function exhibit_section($propertyName, $options=array(), $exhibitSection=null)
{
    if (!$exhibitSection) {
        $exhibitSection = get_current_exhibit_section();
    }
        
	if (property_exists(get_class($exhibitSection), $propertyName)) {
	    return $exhibitSection->$propertyName;
	} else {
	    return null;
	}
}