<?php if (isset($_GET['msg']) && $_GET['msg'] === 'success' && isset($_GET['id'])): ?>
  <div id="popupMsg" style="
    position: fixed;
    top: 30px;
    right: 30px;
    max-width: 320px;
    background: #1a73e8; /* Google Blue */
    color: #fff;
    padding: 20px 25px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 16px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(26, 115, 232, 0.3);
    z-index: 9999;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 15px;
  ">
    <svg width="24" height="24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
         style="flex-shrink: 0;" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
    <div>
      <strong>Registration Successful</strong>
      <div>Your ID: <span style="font-weight: 600;"><?php echo htmlspecialchars($_GET['id']); ?></span></div>
    </div>
    <button onclick="document.getElementById('popupMsg').style.display='none'" style="
      margin-left:auto;
      background: transparent;
      border: none;
      color: #fff;
      font-size: 20px;
      line-height: 1;
      cursor: pointer;
      font-weight: 700;
    ">&times;</button>
  </div>

  <script>
    // Auto-hide popup after 5 seconds
    setTimeout(() => {
      const popup = document.getElementById('popupMsg');
      if (popup) popup.style.display = 'none';
    }, 5000);
  </script>
<?php endif; ?>
