  <!-- Main Footer -->
  <footer class="main-footer">
  	<div class="float-right d-none d-sm-inline">
  		Version 3.0.0
  	</div>

  	<!-- Default to the left -->
  	<div>
  		<strong>Copyright &copy; 2026 </strong> All rights reserved.
  	</div>
    
  </footer>


  <!-- jQuery -->
  <script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
  <!-- Bootstrap 4 -->
  <script src="<?php echo base_url('assets/plugins/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
  <!-- AdminLTE App -->
  <script src="<?php echo base_url('assets/plugins/adminlte/adminlte.min.js'); ?>"></script>


  <script>
    // Disable right-click
document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
});

// Disable common copy shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl+U (view source), Ctrl+S (save), Ctrl+A (select all), F12 (devtools)
    if (e.ctrlKey && (e.key === 'u' || e.key === 's' || e.key === 'a')) {
        e.preventDefault();
    }
    if (e.key === 'F12') {
        e.preventDefault();
    }
});
```

---

## 3. Protect Your PHP Source Code

Since you're on XAMPP/shared hosting, your **PHP files are the real valuable asset** — not the rendered HTML. Protect them:

**Use Ioncube or SourceGuardian** to encode/encrypt your `.php` files so they can't be read even if someone gets server access. These are paid tools but standard for commercial PHP apps.

**For free obfuscation**, use [PHP Obfuscator](https://www.ensconce.org/) on your controllers and models before deployment.

**Set proper file permissions** on your server:
```
chmod 644 *.php      # files readable by server only
chmod 755 directories
  </script>