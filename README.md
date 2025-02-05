<h1 align="center">📝 Visapages || Blog in PHP: Article Publishing and Management 📝</h1>

<p align="center">
  This project is a PHP-based blog that includes both public and private sections. Users can read articles in the public section, 
  while administrators manage and organize the content in the private section. Additionally, registered users can leave comments, 
  and the blog features advanced functionalities like IP detection to assign a flag based on the user's country.
</p>

---

<h2>🚀 Key Features</h2>

<h3>📖 Public and Private Sections</h3>
<ul>
  <li><strong>Public Section:</strong> Visitors can read articles on various topics related to visas.</li>
  <li><strong>Private Section:</strong> Accessible only to administrators, who can create, edit, delete, and organize articles.</li>
</ul>

<h3>💬 Comments and User Profiles</h3>
<ul>
  <li><strong>Comment System:</strong> Registered users can leave comments on the articles.</li>
  <li><strong>User Profiles:</strong> Users can register and manage their profiles within the blog.</li>
</ul>

<h3>🌍 Additional Functionalities</h3>
<ul>
  <li><strong>IP Detection:</strong> The system detects the user's IP address and automatically assigns a flag based on the country of origin.</li>
  <li><strong>Article Organization by Categories:</strong> Articles are grouped into categories to facilitate navigation.</li>
  <li><strong>Search by Title:</strong> Users can search for articles by title on the platform.</li>
</ul>

---

<h2>🧰 Requirements</h2>
<ul>
  <li><strong>XAMPP or WAMP:</strong> You'll need a local server like XAMPP or WAMP to run the project.</li>
  <li><strong>Database:</strong> The database is included in the <code>visapages.sql</code> file.</li>
</ul>

<h2>🚀 Instructions to Run</h2>
<ol>
  <li><strong>Clone or Download the Repository:</strong>
    <ul>
      <li>Clone this repository using Git or download the ZIP file.</li>
    </ul>
  </li>
  <li><strong>Set Up the Local Server:</strong>
    <ul>
      <li>Install and configure XAMPP or WAMP on your machine if you don't have it already.</li>
      <li>Place the repository folder inside the <code>htdocs</code> folder of XAMPP or the <code>www</code> folder of WAMP.</li>
    </ul>
  </li>
  <li><strong>Create the Database:</strong>
    <ul>
      <li>Open phpMyAdmin (usually at <code>http://localhost/phpmyadmin</code>).</li>
      <li>Create a new database with your desired name.</li>
      <li>Import the <code>visapages.sql</code> file into the created database.</li>
    </ul>
  </li>
  <li><strong>Configure the Database Connection:</strong>
    <ul>
      <li>Open the database configuration file (e.g., <code>config.php</code> or a similar file in the project).</li>
      <li>Make sure the connection details (username, password, and database name) are correct according to your local setup.</li>
    </ul>
  </li>
  <li><strong>Run the Project:</strong>
    <ul>
      <li>Start the Apache and MySQL servers from the XAMPP or WAMP control panel.</li>
      <li>Open your browser and go to <code>http://localhost/visapages</code>.</li>
    </ul>
  </li>
</ol>

<p><strong>Done!</strong> You can now access the blog, explore articles, and if you're an administrator, manage the content.</p>

---

<h2>🔧 Contributions</h2>
<p>If you'd like to contribute, fork this repository, make your changes, and submit a pull request.</p>

---

The template used in this project is the following: https://www.free-css.com/free-css-templates/page200/magexpress

The database used to determine the user's country is from IP2Location.

Note: the code and the site are in Spanish.
