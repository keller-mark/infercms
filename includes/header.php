<h1><a href="/">InferCMS Example Site</a></h1>
<p>Administrative Links
  <ul>
    <li><a href="/action.php?a=redirectAdmin">Go to Portal</a></li>
  <?php
    if($auth->loggedIn()) {
      echo '<li><a href="/action.php?a=logout">Log Out</a></li>';
    }
  ?>
  </ul>
</p>