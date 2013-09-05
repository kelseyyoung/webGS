
  </head>

  <body>
    <div class="navbar">
      <div class="navbar-inner">
        <a class="brand" href="<?php echo base_url(); ?>">WebGS<sub><small>BETA</small></sub></a>
        <?php if ($this->session->userdata("user_id")) { ?>
        <a href="<?php echo base_url()."logout"; ?>" class="btn btn-danger pull-right">Logout</a>
        <ul class="nav">
          <li class="divider-vertical"></li>
          <li><a href="<?php echo site_url($this->session->userdata("type")."s/view"); ?>"><i class="icon-home"></i> Home</a></li>
        </ul>
        <ul class="nav">
          <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">Classes <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <?php $classes = null;
              if ($this->session->userdata("type") == "instructor") {
                $classes = $this->class_model->get_classes_by_instructor($this->session->userdata("user_id"));
              } else {
                $classes = $this->class_model->get_classes_by_student($this->session->userdata("user_id"));
              }
              if ($classes) {
                foreach ($classes as $c) { 
                  if ($this->session->userdata("type") == "instructor") { ?>
                <li><a href="<?php echo site_url("classes/view/".$c['id']); ?>"><?php echo $c['name']; ?></a></li>
                <?php } else { ?>
                <li><a href="<?php echo site_url("classes/student_view/".$c['id']); ?>"><?php echo $c['name']; ?></a></li>
                <?php }
                }
              } else { ?>
                <li><a href="#">No Classes Available</a></li>
              <?php } ?>
            </ul>
          </li>
        </ul>
        <?php } ?>
      </div>
    </div>
    <div class="container-fluid" id="wrap">
