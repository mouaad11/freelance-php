<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title>S'authentifier</title>
  <link rel="stylesheet" href="styles/stylesecon.css">
  <!-- Fontawesome CDN Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <div class="container">
    <input type="checkbox" id="flip">
    <div class="cover">
      <div class="front">
        <img class="frontImg" src="images/frontImg.jpg" alt="">
        <div class="text"></div>
      </div>
      <div class="back">
        <img class="backImg" src="images/backImg.jpg" alt="">
        <div class="text2">
          <span class="text-1">Enrichissez votre carrière <br> Avec une seule étape</span>
          <span class="text-2">Commençer</span>
        </div>
      </div>
    </div>
    <div class="forms">
      <div class="form-content">
        <div class="login-form">
          <div class="title">S'authentifier</div>
          <form action="login.php" method="post">
            <div class="input-boxes">
              <div class="input-box">
                <i class="fas fa-envelope"></i>
                <input type="text" name="email" placeholder="Enterer votre email" required>
              </div>
              <div class="input-box">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Enterer votre mot de passe" required>
              </div>
              <div class="text"><a href="motdepasse_oublie.html">Mot de passe oublié?</a></div>
              <div class="button input-box">
                <input type="submit" value="S'authentifier">
              </div>
              <div class="text sign-up-text">Vous n'avez pas de compte? <label for="flip">Créer un compte maintenant</label></div>
            </div>
          </form>
        </div>
        <div class="signup-form">
          <div class="title">Créer votre compte</div>
          <form action="signup.php" method="post">
            <div class="input-boxes">
              <div class="input-box">
                <i class="fas fa-user"></i>
                <input type="text" name="name" placeholder="Enterer votre nom" required>
              </div>
              <div class="input-box">
                <i class="fas fa-envelope"></i>
                <input type="text" name="email" placeholder="Enterer votre email" required>
              </div>
              <div class="input-box">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Enterer votre mot de passe" required>
              </div>
              <div class="button input-box">
                <input type="submit" value="Créer le compte">
              </div>
              <div class="text sign-up-text">Vous avez déjà un compte? <label for="flip">Login</label></div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="error">
    <?php
    if (isset($_GET['error'])) {
      $error = $_GET['error'];
      switch ($error) {
        case 'invalid_login':
          echo "<p style='color: red;'>Adresse e-mail ou mot de passe incorrect.</p>";
          break;
        case 'user_not_found':
          echo "<p style='color: red;'>Utilisateur non trouvé.</p>";
          break;
        case 'invalid_request':
          echo "<p style='color: red;'>Requête invalide.</p>";
          break;
        default:
          echo "<p style='color: red;'>Erreur non spécifiée.</p>";
          break;
      }
    }
    ?>
  </div>

  <script>
    const flipCheckbox = document.getElementById('flip');
    const frontText = document.querySelector('.cover .front .text');
    const backText = document.querySelector('.cover .back .text');

    flipCheckbox.addEventListener('change', function() {
      if (this.checked) {
        frontText.style.opacity = '0';
        backText.style.opacity = '1';
      } else {
        frontText.style.opacity = '1';
        backText.style.opacity = '0';
      }
    });
  </script>
</body>
</html>
