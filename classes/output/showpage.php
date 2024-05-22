<?php

namespace mod_collaborate\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;
use moodle_url;

/**
 * collaborate: Create a new view page renderable object
 *
 * @param object simnplemod - instance of collaborate.
 * @param int id - course module id.
 * @copyright  2020 Richard Jones <richardnz@outlook.com>
 */

class showpage implements renderable, templatable {

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output Output renderer.
     * @return stdClass Template context.
     */
    protected $collaborate;

    protected $cm;

    protected $page;

    public function __construct($collaborate, $cm, $page) {

        $this->collaborate = $collaborate;
        $this->cm = $cm;
        $this->page = $page;
    }

    public function export_for_template(renderer_base $output) {

        $data = new stdClass();

        $data->heading = $this->collaborate->title;

        $data->user = get_string('user', 'mod_collaborate', strtoupper($this->page));

        // Get the content from the database.
        $content = ($this->page == 'a') ? $this->collaborate->instructionsa : $this->collaborate->instructionsb;
        
        $filearea = 'instructions'.$this->page; 
        $context = \context_module::instance($this->cm->id);
        $content = file_rewrite_pluginfile_urls($content, 'pluginfile.php', $context->id,
        'mod_collaborate', $filearea, $this->collaborate->id);

        // Run the content through format_text to enable streaming video etc.
        $formatoptions = new stdClass;
        $formatoptions->overflowdiv = true;
        $formatoptions->context = $context;
        $format = ($this->page == 'a') ? $this->collaborate->instructionsaformat : $this->collaborate->instructionsbformat;

        $data->body = format_text($content, $format, $formatoptions);

        // Get a return url back to view page.
        $urlv = new moodle_url('/mod/collaborate/view.php', ['id' => $this->cm->id]);
        $data->url_view = $urlv->out(false);

        return $data;
    }
}