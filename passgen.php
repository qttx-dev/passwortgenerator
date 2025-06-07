<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Passwortgenerator</title>
  <style>
    body {
      font-family: "Segoe UI", sans-serif;
      background: #f1f3f5;
      margin: 0;
      padding: 1em;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .container {
      background: #fff;
      border-radius: 10px;
      padding: 1em;
      width: 100%;
      max-width: 480px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      box-sizing: border-box;
    }

    h1 {
      text-align: center;
      font-size: 1.3em;
      margin-bottom: 1em;
    }

    .form-group {
      margin-bottom: 0.8em;
    }

    label {
      font-size: 0.95em;
      font-weight: 500;
      margin-bottom: 0.3em;
      display: block;
    }

    input[type="text"],
    input[type="number"] {
      width: 100%;
      padding: 0.5em;
      font-size: 1em;
      border-radius: 6px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }

    input[type="checkbox"] {
      margin-right: 0.4em;
    }

    .checkboxes label {
      font-weight: normal;
      font-size: 0.9em;
      display: flex;
      align-items: center;
    }

    button {
      width: 100%;
      padding: 0.7em;
      font-size: 1em;
      background: #007bff;
      color: #fff;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 0.5em;
    }

    button:hover {
      background: #0056b3;
    }

    #password-output {
      margin-top: 1em;
      text-align: center;
    }

    #password-text {
      font-family: monospace;
      background: #f8f9fa;
      padding: 0.5em 1em;
      border-radius: 6px;
      display: inline-block;
      font-size: 1.1em;
      word-break: break-word;
      max-width: 100%;
    }

    .copy-btn {
      background: #28a745;
      margin-top: 0.5em;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .copy-btn i {
      margin-right: 0.4em;
    }

    .copy-feedback {
      text-align: center;
      font-weight: 500;
      color: #28a745;
      font-size: 0.9em;
      margin-top: 0.5em;
      transition: opacity 0.5s ease;
    }

    @media (min-height: 700px) {
      body {
        justify-content: center;
        height: 100vh;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>🔐 Passwortgenerator</h1>

    <div class="form-group">
      <label for="length">Länge:</label>
      <input type="number" id="length" value="16" min="4" max="64">
    </div>

    <div class="form-group checkboxes">
      <label><input type="checkbox" id="include-lowercase" checked> Kleinbuchstaben (a-z)</label>
      <label><input type="checkbox" id="include-uppercase" checked> Großbuchstaben (A-Z)</label>
      <label><input type="checkbox" id="include-numbers" checked> Zahlen (0-9)</label>
      <label><input type="checkbox" id="include-symbols" checked> Sonderzeichen (!@#$%&*()_-+=?/)</label>
    </div>

    <div class="form-group">
      <label for="custom-include">Eigene Zeichen (optional):</label>
      <input type="text" id="custom-include" placeholder="z. B. äöüß">
    </div>

    <div class="form-group">
      <label for="exclude">Zeichen ausschließen:</label>
      <input type="text" id="exclude" placeholder="z. B. 0oO1Il">
    </div>

    <button onclick="generatePassword()">🎲 Generieren</button>

    <div id="password-output" style="display:none;">
      <div id="password-text"></div>
      <button class="copy-btn" onclick="copyPassword()">
        <i>📝</i> Kopieren
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
      if (includeSymbols) charset += "!@#$%&*()_-+=?/";
      charset += customInclude;

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
        const existing = document.querySelector(".copy-feedback");
        if (existing) existing.remove();

        const message = document.createElement("div");
        message.textContent = "✅ Kopiert!";
        message.className = "copy-feedback";

        const output = document.getElementById("password-output");
        output.appendChild(message);

        setTimeout(() => message.style.opacity = "0", 1500);
        setTimeout(() => message.remove(), 2000);
      });
    }
  </script>
</body>
</html>
