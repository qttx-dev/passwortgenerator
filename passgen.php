<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Passwortgenerator</title>
  <style>
    body {
      font-family: "Segoe UI", sans-serif;
      background: linear-gradient(to bottom, #e9ecef, #f8f9fa);
      color: #333;
      padding: 2em;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .container {
      background: #fff;
      border-radius: 12px;
      padding: 2em;
      max-width: 500px;
      width: 100%;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    h1 {
      text-align: center;
      color: #2a2a2a;
      margin-bottom: 1em;
    }

    .form-group {
      margin-bottom: 1.2em;
    }

    label {
      font-weight: bold;
      display: block;
      margin-bottom: 0.4em;
    }

    input[type="text"],
    input[type="number"],
    textarea {
      width: 100%;
      padding: 0.6em;
      font-size: 1em;
      border-radius: 6px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }

    input[type="checkbox"] {
      margin-right: 0.5em;
    }

    .checkboxes label {
      display: block;
      margin-bottom: 0.3em;
      font-weight: normal;
    }

    button {
      background-color: #007bff;
      color: white;
      border: none;
      padding: 0.8em;
      border-radius: 6px;
      font-size: 1em;
      cursor: pointer;
      width: 100%;
      margin-top: 1em;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #0056b3;
    }

    #password-output {
      margin-top: 1.5em;
      text-align: center;
    }

    #password-text {
      display: inline-block;
      background: #f1f3f5;
      font-family: monospace;
      padding: 0.8em 1em;
      font-size: 1.2em;
      border-radius: 8px;
      word-break: break-word;
      max-width: 100%;
    }

    .copy-btn {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 0.6em;
      font-size: 1em;
      border-radius: 6px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-top: 1em;
      width: 100%;
      transition: background-color 0.3s;
    }

    .copy-btn:hover {
      background-color: #1e7e34;
    }

    .copy-btn i {
      margin-right: 0.5em;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>🔐 Passwortgenerator</h1>

    <div class="form-group">
      <label for="length">Passwortlänge:</label>
      <input type="number" id="length" value="16" min="4" max="64">
    </div>

    <div class="form-group checkboxes">
      <label>Zeichenarten:</label>
      <label><input type="checkbox" id="include-lowercase" checked> Kleinbuchstaben (a-z)</label>
      <label><input type="checkbox" id="include-uppercase" checked> Großbuchstaben (A-Z)</label>
      <label><input type="checkbox" id="include-numbers" checked> Zahlen (0-9)</label>
      <label><input type="checkbox" id="include-symbols" checked> Sonderzeichen (!@#...)</label>
    </div>

    <div class="form-group">
      <label for="custom-include">Zusätzliche eigene Zeichen (optional):</label>
      <input type="text" id="custom-include" placeholder="z. B. äöüß€">
    </div>

    <div class="form-group">
      <label for="exclude">Zeichen ausschließen (optional):</label>
      <input type="text" id="exclude" placeholder="z. B. 0oO1Il">
    </div>

    <button onclick="generatePassword()">🎲 Passwort generieren</button>

    <div id="password-output" style="display:none;">
      <div id="password-text"></div>
      <button class="copy-btn" onclick="copyPassword()">
        <i>📝</i> Passwort kopieren
      </button>
    </div>
  </div>

  <script>
    function generatePassword() {
      const length = parseInt(document.getElementById('length').value);
      const includeLower = document.getElementById('include-lowercase').checked;
      const includeUpper = document.getElementById('include-uppercase').checked;
      const includeNumbers = document.getElementById('include-numbers').checked;
      const includeSymbols = document.getElementById('include-symbols').checked;
      const customInclude = document.getElementById('custom-include').value;
      const excludeChars = document.getElementById('exclude').value;

      let charset = "";
      if (includeLower) charset += "abcdefghijklmnopqrstuvwxyz";
      if (includeUpper) charset += "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
      if (includeNumbers) charset += "0123456789";
      if (includeSymbols) charset += "!@#$%^&*()_-+=[]{}<>?/|~";
      charset += customInclude;

      // Entferne ausgeschlossene Zeichen
      if (excludeChars) {
        charset = charset.split('').filter(c => !excludeChars.includes(c)).join('');
      }

      if (charset.length === 0) {
        alert("Bitte mindestens eine Zeichenquelle wählen oder definieren.");
        return;
      }

      let password = "";
      const array = new Uint32Array(length);
      window.crypto.getRandomValues(array);

      for (let i = 0; i < length; i++) {
        password += charset[array[i] % charset.length];
      }

      document.getElementById('password-text').textContent = password;
      document.getElementById('password-output').style.display = 'block';
    }

    function copyPassword() {
      const password = document.getElementById('password-text').textContent;
      navigator.clipboard.writeText(password).then(() => {
        alert("✅ Passwort wurde in die Zwischenablage kopiert!");
      });
    }
  </script>
</body>
</html>
