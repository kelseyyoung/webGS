<?php

$xmlentities = array(
  '&amp;' => '&',
  '&lt;' => '<',
  '&gt;' => '>',
  '&apos;' => '\'',
  '&quot;' => '"',
);

  class Home extends CI_Controller {

    public function __construct() {
      parent::__construct();
      $this->load->model('instructor_model');
      $this->load->model('student_model');
      $this->load->model('class_model');
      require(function_path()."inc_authfunctions.php");
    }

    public function index() {

      $path_redirect = $_SERVER["REQUEST_URI"];
      $service = "http://webgs.cs.arizona.edu/index.php";
      $auth_redirect = $service;
      $fullservice = $service;

      $webauth = "https://webauth.arizona.edu:8443/webauth/serviceValidate";
      $banner = "Department of Computer Science - WebAuth Interface - WebGS";

      //session_name('pt_sid');
      //session_start();
      if (!$_GET['ticket']) {
        //Not authorized
        $location = "Location: https://webauth.arizona.edu:8443/webauth/login?service=".rawurlencode($fullservice);
        if ($banner != "") {
          $location .= "&banner=".rawurlencode($banner);
        }
        header($location);
        exit;
      } else {
        //Validate ticket
        $user = extended_validate_webauth_ticket($webauth, $_GET['ticket'], $service);
        if (!is_array($user)) {
          //Validation error
          echo ("Authentication failure");
          exit;
        } else {
          if (!isset($user['DBKEY'])) {
            $user['DBKEY'] = "";
          }
          if (!isset($user['EMPLID'])) {
            $user['EMPLID'] = "";
          }

          $this->session->set_userdata(array(
            'valid' => 1,
            'netid' => $user['USER'],
            'dbkey' => $user['DBKEY'],
            'emplId', $user['EMPLID']
            )
          );

          $this->load->helper('cookie');
          set_cookie('CASAUTHOK', $_COOKIE['CASAUTHOK']);

          $instructor = $this->instructor_model->get_instructor_by_username($this->session->userdata('netid'));
          //if ($instructor) {
          if( false) {
            $this->session->set_userdata('type', 'instructor');
            $this->session->set_userdata('user_id', $instructor['id']);
            redirect(site_url('instructors/view'));
          } else {
            $student = $this->student_model->get_student_by_username($this->session->userdata('netid'));
            if ($student) {
              $this->session->set_userdata('type', 'student');
              $this->session->set_userdata('user_id', $student['id']);
            } else {
              $this->student_model->create_student($this->session->userdata('netid'));
              $student = $this->student_model->get_student_by_username($this->session->userdata('netid'));
              $this->session->set_userdata('type', 'student');
              $this->session->set_userdata('user_id', $student['id']);
            }
            chmod_R(upload_path().'127A_Summer', 0777, 0777);
            redirect(site_url('students/view'));
          }
        }
      }
    }

  }

function StartHandler(&$parser, &$elem, &$attr) {
	global $data, $cdata, $xmlentities;
	
	/*
	echo "StartHandler";
	echo "<pre>";
	print_r($data);
	echo "cdata: ";
	print_r($cdata);
	echo "</pre>";
	*/
	
	// Start with empty cdata array.
	$cdata = array();

	// Put each attribute into the data array.
	foreach ($attr as $key => $value) {
			$data[$elem.":".$key] = strtr(trim($value), $xmlentities);
			// echo "$elem:$key = {$data["$elem:$key"]}<br />";
	}
}

function CharacterHandler(&$parser, &$line) {
	global $data, $cdata, $xmlentities;
	
	/*
	echo "CharacterHandler";
	echo "<pre>";
	print_r($data);
	echo "cdata: ";
	print_r($cdata);
	echo "</pre>";
	*/
	
  /*
  * Place lines into an array because elements
  * can contain more than one line of data.
  */
  $cdata[] = $line;
}

function EndHandler(&$parser, &$elem) {
	global $data, $cdata, $dataprobs, $Sym, $xmlentities;
	
	/*
	echo "EndHandler";
	echo "<pre>";
	print_r($data);
	echo "cdata: ";
	print_r($cdata);
	echo "</pre>";
	*/
	
	/*
	 * Mush all of the cdata lines into a string
	 * and put it into the $data array.
	 */
	$data["$elem"] = strtr( trim( implode('', $cdata) ), $xmlentities);

	// debug
	// echo "$elem = {$data[$elem]}<br />";
}

function extended_validate_webauth_ticket($webauth, $ticket, $service) {
	global $data, $cdata, $dataprobs, $Sym, $xmlentities;
	
	//  Make sure there's no other data with these names.
	$parserprobs = array();
	$dataprobs   = array();
	
	$u = $webauth . "?ticket=" . rawurlencode($ticket) . "&service=" . rawurlencode($service);
	
	//echo "U: ".$u."<br />";
	$contents = @file_get_contents($u);
	
	// echo "Contents: <pre>".$contents."</pre>";
	
	if (!$contents) {
		$parserprobs[] = "$u<br />    Had problem opening file.";
	}
	
	// Escape ampersands that aren't part of entities.
	$contents = preg_replace('/&(?!\w{2,6};)/', '&amp;', $contents);
	
	// Remove all non-visible characters except SP, TAB, LF and CR.
	$contents = preg_replace('/[^\x20-\x7E\x09\x0A\x0D]/', "\n", $contents);
	
	// remove the "cas:" string from the xml entity names
	$contents = str_replace('cas:', '', $contents);
	
	$data = array();
	
	// Initialize the parser.
	$parser = xml_parser_create('ISO-8859-1');
	xml_set_element_handler($parser, 'StartHandler', 'EndHandler');
	xml_set_character_data_handler($parser, 'CharacterHandler');
	
	// Pass the content string to the parser.
	if ( !xml_parse($parser, $contents, TRUE) ) {
			$parserprobs[] = "Had problem parsing data<br />".xml_error_string(xml_get_error_code($parser));
	}
	
	// DEBUG 
	/*
	echo "<pre>";
	echo "Data: ";
	print_r($data);
	echo "</pre>";
	*/
	
	// Problems?
	if ( count($parserprobs) ) {
		echo "\n" . implode("\n", $parserprobs);
		return -1;
	} else {
		return $data;
	}
	
} // end function extended_validate_webauth_ticket($ticket, $service)

function chmod_R($path, $filemode, $dirmode) {
  if (is_dir($path)) {
    if (!chmod($path, $dirmode)) {
      return;
    }
    $dh = opendir($path);
    while (($file = readdir($dh)) !== false) {
      if ($file != '.' && $file != '..') {
        $fullpath = $path.'/'.$file;
        chmod_R($fullpath, $filemode, $dirmode);
      }
    }
    closedir($dh);
  } else {
    if (is_link($path)) {
      return;
    }
    if (!chmod($path, $filemode)) {
      return;
    }
  }
}

  ?>
