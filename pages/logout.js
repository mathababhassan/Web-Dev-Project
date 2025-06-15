const logoutLink = document.querySelector('a[href="logout.php"]');

if (logoutLink) {
  logoutLink.onclick = function (event) {
    if (!confirm("Are you sure you want to log out?")) {
      event.preventDefault(); // prevent logout
    }
  };
}
