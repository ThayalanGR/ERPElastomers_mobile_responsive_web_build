<h2>Performing update</h2>

<?php 
  $suite = $this->model->test_suite;
  include "view/install/_test_suite.php";
?>

<?php if ($this->model->valid): ?>
  <h2>Update complete</h2>
  <p>myDBR has been successfully updated.</p>
<?php endif; ?>

<?php echo $this->getButtonsHTML() ?>

