<?php foreach ($students as $student): ?>
  <h2><?php echo $student['name'] ?></h2>
    <div>
      <p>ID: <?php echo $student['id'] ?></p>
    </div>
<?php endforeach ?>
