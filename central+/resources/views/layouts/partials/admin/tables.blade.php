<main class="content">
      <header class="content-header">
        <h1>Gestion des Permissions</h1>
        <button class="btn-primary"><a href="layouts/partials/modals/forms">+ Nouvelle Permission</a></button>
      </header>

      <!-- Tableau des permissions -->
      <section class="table-section">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Permission</th>
              <th>Description</th>
              <th>Attribué à (Rôles)</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>#1</td>
              <td>Créer Utilisateur</td>
              <td>Autorise la création d’un nouvel utilisateur</td>
              <td>Admin, Manager</td>
              <td>
                <button class="btn-sm">✏️ Modifier</button>
                <button class="btn-sm btn-danger">🗑️ Supprimer</button>
              </td>
            </tr>
            <tr>
              <td>#2</td>
              <td>Voir Patients</td>
              <td>Autorise la consultation des données patients</td>
              <td>Docteur, Manager</td>
              <td>
                <button class="btn-sm">✏️ Modifier</button>
                <button class="btn-sm btn-danger">🗑️ Supprimer</button>
              </td>
            </tr>
          </tbody>
        </table>
      </section>