<?php
declare(strict_types=1);
/** Simple front-end that posts to process_contact.php */
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Contact</title>
  <style>
    :root { color-scheme: light dark; }
    body { font-family: system-ui, Arial, sans-serif; margin: 2rem; }
    .wrap { max-width: 720px; margin: 0 auto; }
    label { display:block; margin:.75rem 0 .25rem; font-weight: 600; }
    input, textarea {
      width:100%; padding:.65rem; border:1px solid #ccc; border-radius:.45rem;
      background: inherit; color: inherit;
    }
    button { margin-top:1rem; padding:.65rem 1rem; border:0; border-radius:.45rem; cursor:pointer; }
    .alert { margin:1rem 0; padding:.8rem 1rem; border-radius:.5rem; border:1px solid transparent; display:none; }
    .success { background:#e6ffed; border-color:#a7f3d0; }
    .error   { background:#fff5f5; border-color:#feb2b2; }
    .hp { position:absolute; left:-5000px; width:1px; height:1px; overflow:hidden; }
  </style>
</head>
<body>
  <div class="wrap">
    <h1>Contact Us</h1>

    <div id="msg" class="alert"></div>

    <form id="contact" method="POST" action="process_contact.php" novalidate>
      <label for="name">Name</label>
      <input id="name" name="name" required>

      <label for="email">Email</label>
      <input id="email" type="email" name="email" required>

      <label for="message">Message</label>
      <textarea id="message" name="message" rows="6" required></textarea>

      <!-- Honeypot anti-bot -->
      <input class="hp" type="text" name="company" tabindex="-1" autocomplete="off">

      <button type="submit">Send</button>
    </form>

    <noscript>
      <p><em>Note:</em> JavaScript is disabled; the form will submit normally and return JSON.</p>
    </noscript>
  </div>

  <script>
    const form = document.getElementById('contact');
    const box  = document.getElementById('msg');

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const body = new URLSearchParams(new FormData(form));
      const res  = await fetch(form.action, {
        method: 'POST',
        headers: {
          'X-Requested-With':'XMLHttpRequest',
          'Content-Type':'application/x-www-form-urlencoded'
        },
        body
      });

      let json;
      try { json = await res.json(); }
      catch { json = {status:'error', message:'Unexpected response from server.'}; }

      box.style.display = 'block';
      box.className = 'alert ' + (json.status === 'success' ? 'success' : 'error');
      box.textContent = json.message || 'Unknown result.';
      if (json.status === 'success') form.reset();
    });
  </script>
</body>
</html>
