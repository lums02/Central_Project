<!-- Formulaire d’ajout -->
      <section class="form-section">
        <h2>Ajouter une nouvelle permission</h2>
        <form>
          <div class="form-group">
            <label>Nom de la Permission</label>
            <input type="text" placeholder="ex: Gérer Rendez-vous">
          </div>
          <div class="form-group">
            <label>Description</label>
            <textarea placeholder="Décrire l’utilisation de cette permission"></textarea>
          </div>
          <div class="form-group">
            <label>Attribuer aux rôles</label>
            <select multiple>
              <option>Admin</option>
              <option>Manager</option>
              <option>Docteur</option>
              <option>Infirmier</option>
            </select>
          </div>
          <button type="submit" class="btn-primary">Enregistrer</button>
        </form>
      </section>