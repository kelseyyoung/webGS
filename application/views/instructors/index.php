<?php foreach ($instructors as $instructor): ?>
  <h2><?php echo $instructor['name'] ?></h2>
    <div>
      <p>ID: <?php echo $instructor['id'] ?></p>
    </div>
<?php endforeach ?>
<?php echo $id."\n"; echo $my_id; ?>
