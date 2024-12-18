<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administration</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#4CAF50',
            secondary: '#FF5722',
          },
        },
      },
    };
  </script>

  <!-- Alpine.js -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

  <script>
    $(document).ready(function () {
      $('#userTable').DataTable({
        language: {
          url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
        }
      });
    });

    function deleteUser(userId) {
      if (confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur?")) {
        $.ajax({
          url: 'delete_user.php',
          type: 'POST',
          data: { id: userId },
          success: function (response) {
            alert(response);
            location.reload(); // Rafraîchit la page après la suppression
          },
          error: function (xhr, status, error) {
            console.error(xhr.responseText);
          }
        });
      }
    }
  </script>
</head>

<body class="bg-gray-100 text-gray-800" x-data="{ activeTab: 'users' }">

  <!-- Header -->
  <header class="bg-primary text-white shadow">
    <div class="container mx-auto flex justify-between items-center p-4">
      <div class="flex items-center space-x-4">
        <img src="https://cdn.discordapp.com/attachments/1073409893668757626/1251245016496476180/Design_sans_titre_1.png"
          alt="Logo" class="w-12 h-12">
        <h1 class="text-2xl font-bold">CDP Administration</h1>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="container mx-auto py-8 flex">
    <!-- Sidebar Navigation -->
    <nav class="w-64 bg-white shadow-lg rounded-lg p-4">
      <ul class="space-y-4">
        <li>
          <button @click="activeTab = 'users'" class="w-full text-left py-2 px-4 rounded hover:bg-primary hover:text-white transition">Utilisateurs</button>
        </li>
        <li>
          <button @click="activeTab = 'addUser'" class="w-full text-left py-2 px-4 rounded hover:bg-primary hover:text-white transition">Ajouter un Utilisateur</button>
        </li>
        <li>
          <button @click="activeTab = 'addVote'" class="w-full text-left py-2 px-4 rounded hover:bg-primary hover:text-white transition">Ajouter un Vote</button>
        </li>
      </ul>
      <!-- "CDP Votes" Section at the Bottom -->
      <div class="mt-8 text-center">
        <p class="text-xl font-bold">CDP Votes</p>
      </div>
    </nav>

    <!-- Content Area -->
    <div class="flex-1 ml-4">
      <!-- Users Section -->
      <div x-show="activeTab === 'users'" x-transition class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-bold mb-4">Liste des Utilisateurs</h2>
        <table id="userTable" class="display stripe hover w-full text-sm">
          <thead>
            <tr>
              <th>Username</th>
              <th>Email</th>
              <th>Type</th>
              <th>Ville</th>
              <th>Nom</th>
              <th>Prénom</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Code PHP pour afficher la liste des utilisateurs
            define('DB_SERVER', 'localhost');
            define('DB_USERNAME', 'root');
            define('DB_PASSWORD', '');
            define('DB_NAME', 'formulaire_inscription');

            $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

            if ($conn === false) {
              die("ERREUR : Impossible de se connecter. " . mysqli_connect_error());
            }

            $requete = "SELECT * FROM users";
            $resultat = mysqli_query($conn, $requete);

            while ($row = mysqli_fetch_assoc($resultat)) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($row['username']) . "</td>";
              echo "<td>" . htmlspecialchars($row['email']) . "</td>";
              echo "<td>" . htmlspecialchars($row['type']) . "</td>";
              echo "<td>" . htmlspecialchars($row['ville'] ?? '') . "</td>";
              echo "<td>" . htmlspecialchars($row['nom'] ?? '') . "</td>";
              echo "<td>" . htmlspecialchars($row['prenom'] ?? '') . "</td>";
              echo "<td>
                      <a href='edit_user.php?id=" . $row['id'] . "' class='text-blue-500 hover:underline'>Modifier</a> |
                      <a href='#' onclick='deleteUser(" . $row['id'] . ")' class='text-red-500 hover:underline'>Supprimer</a>
                    </td>";
              echo "</tr>";
            }

            mysqli_close($conn);
            ?>
          </tbody>
        </table>
      </div>

      <!-- Add User Section -->
      <div x-show="activeTab === 'addUser'" x-transition class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-bold mb-4">Ajouter un Utilisateur</h2>
        <form id="addUserForm" class="space-y-4" action="add_user.php" method="POST">
          <!-- Form Fields -->
          <div class="form-group">
            <label for="username" class="block text-sm font-medium">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" class="mt-1 w-full border-gray-300 rounded-lg p-2" required>
          </div>
          <div class="form-group">
            <label for="type" class="block text-sm font-medium">Type</label>
            <input type="type" id="type" name="type" class="mt-1 w-full border-gray-300 rounded-lg p-2" required>
          </div>
          <div class="form-group">
            <label for="email" class="block text-sm font-medium">Email</label>
            <input type="email" id="email" name="email" class="mt-1 w-full border-gray-300 rounded-lg p-2" required>
          </div>
          <div class="form-group">
            <label for="nom" class="block text-sm font-medium">Nom</label>
            <input type="text" id="nom" name="nom" class="mt-1 w-full border-gray-300 rounded-lg p-2" required>
          </div>
          <div>
            <label for="ville" class="block text-sm font-medium text-gray-700">Ville :</label>
            <input type="text" id="ville" name="ville" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
          </div>
          <div class="form-group">
            <label for="prenom" class="block text-sm font-medium">Prénom</label>
            <input type="text" id="prenom" name="prenom" class="mt-1 w-full border-gray-300 rounded-lg p-2" required>
          </div>
          <div class="form-group">
            <label for="password" class="block text-sm font-medium">Mot de passe</label>
            <input type="password" id="password" name="password" class="mt-1 w-full border-gray-300 rounded-lg p-2" required>
          </div>
          <button type="submit" class="w-full bg-primary text-white py-2 rounded shadow hover:bg-green-600">Ajouter</button>
        </form>
      </div>

        
      <div x-show="activeTab === 'addVote'" x-transition class="bg-white shadow rounded-lg p-6">
  <div x-show="activeTab === 'addVote'" x-transition class="bg-white shadow rounded-lg p-6">
  <!-- Ajouter un vote -->
  <section class="bg-white shadow rounded-lg p-6 mt-6">
    <h2 class="text-xl font-bold mb-4">Ajouter un Vote</h2>
    <form action="add_vote.php" method="POST" enctype="multipart/form-data">
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nom du Vote :</label>
        <input type="text" id="name" name="name" class="mt-1 block w-full rounded border-gray-300 shadow-sm" required>
      </div>
      <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Description :</label>
        <textarea id="description" name="description" class="mt-1 block w-full rounded border-gray-300 shadow-sm" required></textarea>
      </div>
      <div>
        <label for="image" class="block text-sm font-medium text-gray-700">Image :</label>
        <input type="file" id="image" name="image" class="mt-1 block w-full rounded border-gray-300 shadow-sm" required>
      </div>
      <div>
        <label for="options" class="block text-sm font-medium text-gray-700">Options :</label>
        <input type="text" name="options[]" class="mt-1 block w-full rounded border-gray-300 shadow-sm" placeholder="Option 1" required>
        <input type="text" name="options[]" class="mt-1 block w-full rounded border-gray-300 shadow-sm" placeholder="Option 2" required>
        <input type="text" name="options[]" class="mt-1 block w-full rounded border-gray-300 shadow-sm" placeholder="Option 3" required>
      </div>
      <button type="submit" class="w-full bg-primary text-white py-2 rounded shadow hover:bg-green-600">Ajouter</button>
    </form>
  </section>

  <!-- Liste des votes -->
  <section class="py-12">
    <div class="max-w-6xl mx-auto px-4">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($votes as $vote): ?>
          <div class="card flex flex-col bg-white shadow-lg rounded-lg">
            <img src="data:image/jpeg;base64,<?php echo htmlspecialchars($vote['image']); ?>" alt="<?php echo htmlspecialchars($vote['name']); ?>" class="h-60 object-cover rounded-t-lg">
            <div class="p-4 flex-grow">
              <h2 class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars($vote['name']); ?></h2>
              <p class="text-gray-600 mt-2"><?php echo htmlspecialchars($vote['description']); ?></p>
              <div class="flex justify-between mt-4 items-center">
                <button
                  @click="selectedVote = <?php echo htmlspecialchars(json_encode($vote)); ?>; showModal = true;"
                  class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-400">
                  Voter
                </button>
                <span class="text-gray-600"><?php echo htmlspecialchars($vote['votes'] ?? '0'); ?> votes</span>
              </div>
              <!-- Modifier et Supprimer -->
              <div class="mt-4 flex justify-between">
                <a href="edit_vote.php?id=<?php echo $vote['id']; ?>" class="text-blue-500 hover:text-blue-700">Modifier</a>
                <a href="delete_vote.php?id=<?php echo $vote['id']; ?>" class="text-red-500 hover:text-red-700">Supprimer</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</div>

<!-- Modal de Voter -->
<div x-show="showModal" x-transition class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white p-8 rounded-lg shadow-lg w-1/3">
    <h2 class="text-2xl font-bold mb-4">Voter pour: <span x-text="selectedVote.name"></span></h2>
    <form action="vote_action.php" method="POST">
      <input type="hidden" name="vote_id" x-bind:value="selectedVote.id">
      <label for="voteOption" class="block text-sm font-medium text-gray-700">Choisissez une option :</label>
      <select id="voteOption" name="voteOption" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
        <template x-for="(option, index) in selectedVote.options" :key="index">
          <option x-text="option" :value="option"></option>
        </template>
      </select>
      <button type="submit" class="mt-4 w-full bg-blue-500 text-white py-2 rounded shadow hover:bg-blue-600">Voter</button>
    </form>
    <button @click="showModal = false" class="mt-4 w-full bg-gray-500 text-white py-2 rounded shadow hover:bg-gray-600">Fermer</button>
  </div>
</div>


    </div>
  </main>

</body>

</html>

