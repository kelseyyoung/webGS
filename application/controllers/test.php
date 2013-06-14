<?php
  
  class Test extends CI_Controller {

    public function __construct() {
      parent::__construct();
    }

    public function index() {
      //Run java program here
      $data['title'] = "Test";
      $file = "TestJUnit.java";
      echo "Running file <br />";
      //echo shell_exec("java -version 2>&1") . "<br />";
      //$string = "javac -cp .:" . asset_path() . "java/junit-4.10.jar.:" . asset_path()."java/ant.jar -d ".substr(upload_path(), 0, -1) . " " . upload_path(). $file . " 2>&1 &";
      $string = "javac -cp .:" . asset_path() . "java/junit-4.10.jar:" . asset_path()."java/ant.jar -d ".substr(upload_path(), 0, -1) . " " . upload_path(). $file . " 2>&1 &";

      echo $string . "<br />";
      echo shell_exec($string) . "<br />";
      //$string = "java -cp .:" . asset_path(). "java/junit-4.10.jar:" .asset_path() ."java/ant.jar:" . upload_path(). " org.junit.runner.JUnitCore TestJUnit 2>&1 &";
      //$string = "java -cp .:" . asset_path(). "java/junit-4.10.jar:" .asset_path() ."java/ant.jar:". asset_path()."java/ant-junit.jar:" . upload_path(). " org.apache.tools.ant.taskdefs.optional.junit.JUnitTestRunner TestJUnit formatter=org.apache.tools.ant.taskdefs.optional.junit.SummaryJUnitResultFormatter showoutput=true 2>&1 &";
      $string = "java -cp .:" . asset_path(). "java/junit-4.10.jar:" .asset_path() ."java/ant.jar:". asset_path()."java/ant-junit.jar:" . upload_path(). " org.apache.tools.ant.taskdefs.optional.junit.JUnitTestRunner TestJUnit formatter=org.apache.tools.ant.taskdefs.optional.junit.XMLJUnitResultFormatter,".upload_path()."test.xml 2>&1 &";
      $xml = new DOMDocument();
      $xml->load(upload_path()."test.xml");
      $header = $xml->getElementsByTagName('testsuite');
      foreach($header as $h) {
	echo $h->getAttribute('errors');
	echo $h->getAttribute('failures');
	echo $h->getAttribute('tests');
      }
      echo $string . "<br />";
      echo shell_exec($string) . "<br />";
      $this->load->view('templates/header', $data);
      $this->load->view('test/index');
      $this->load->view('templates/footer');
    }

  }

?>
