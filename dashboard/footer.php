<footer class="footer">
    © <?= date("Y") ?> Mobilis Fleet Management — All rights reserved.
</footer>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const themeBtn   = document.getElementById("themeToggle");
    const menuToggle = document.getElementById("menuToggle");
    const overlay    = document.getElementById("sidebarOverlay");
    const isMobile   = () => window.innerWidth <= 768;

    // ── Theme ──────────────────────────────────────────────────────
    if (localStorage.getItem("theme") === "dark") {
        document.body.classList.add("dark");
        if (themeBtn) themeBtn.innerHTML = '<i class="fas fa-sun"></i>';
    }
    if (themeBtn) {
        themeBtn.addEventListener("click", function () {
            document.body.classList.toggle("dark");
            const dark = document.body.classList.contains("dark");
            localStorage.setItem("theme", dark ? "dark" : "light");
            themeBtn.innerHTML = dark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
        });
    }

    // ── Sidebar toggle ─────────────────────────────────────────────
    if (menuToggle) {
        menuToggle.addEventListener("click", function () {
            if (isMobile()) {
                document.body.classList.toggle("sidebar-open");
            } else {
                document.body.classList.toggle("sidebar-collapsed");
            }
        });
    }

    // ── Close on overlay click (mobile) ───────────────────────────
    if (overlay) {
        overlay.addEventListener("click", function () {
            document.body.classList.remove("sidebar-open");
        });
    }

    // ── Cleanup on resize ─────────────────────────────────────────
    window.addEventListener("resize", function () {
        if (!isMobile()) document.body.classList.remove("sidebar-open");
        else document.body.classList.remove("sidebar-collapsed");
    });
});

window.addEventListener("pageshow", function (e) {
    if (e.persisted) window.location.reload();
});
</script>

</body>
</html>
