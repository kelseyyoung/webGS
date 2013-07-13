    </style>
  </head>

  <body>
    <div class="navbar">
      <div class="navbar-inner">
        <a class="brand" href="<?php echo base_url(); ?>">WebGS</a>
        <?php if ($this->session->userdata("user_id")) { ?>
        <a href="<?php echo base_url()."logout"; ?>" class="btn btn-danger pull-right">Logout</a>
        <ul class="nav pull-right">
          <li><a href="<?php echo site_url($this->session->userdata("type")."s/view"); ?>"><i class="icon-home"></i> Home</a></li>
        </ul>
        <?php } ?>
      </div>
    </div>
    <div class="container-fluid">
