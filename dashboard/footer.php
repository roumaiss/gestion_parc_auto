<footer class="footer">
    © <?= date("Y") ?> Mobilis Fleet Management — All rights reserved.
</footer>

<!-- ── GLOBAL DELETE MODAL ─────────────────────────────────────────── -->
<div id="deleteModal" style="
    display:none; position:fixed; inset:0; z-index:9999;
    background:rgba(0,0,0,0.5);
    align-items:center; justify-content:center;
">
    <div style="
        background:var(--card-bg, #fff);
        border-radius:14px;
        padding:32px 28px;
        width:100%; max-width:400px;
        box-shadow:0 20px 60px rgba(0,0,0,0.25);
        text-align:center;
        font-family:'Segoe UI',sans-serif;
    ">
        <div style="
            width:60px; height:60px; border-radius:50%;
            background:#fef2f2; color:#ef4444;
            display:flex; align-items:center; justify-content:center;
            font-size:26px; margin:0 auto 16px;
        ">
            <i class="fas fa-trash-alt"></i>
        </div>
        <h3 style="margin:0 0 8px;font-size:18px;color:var(--text,#222);">Confirmer la suppression</h3>
        <p id="deleteModalMsg" style="margin:0 0 24px;color:#888;font-size:14px;line-height:1.5;">
            Êtes-vous sûr de vouloir supprimer cet élément ?
        </p>
        <div style="display:flex;gap:12px;justify-content:center;">
            <button onclick="closeDeleteModal()" style="
                padding:10px 24px; border-radius:8px;
                border:2px solid #dcdcdc; background:transparent;
                color:var(--text,#222); font-size:14px; cursor:pointer;
                font-weight:600; transition:0.2s;
            " onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='transparent'">
                Annuler
            </button>
            <a id="deleteModalBtn" href="#" style="
                padding:10px 24px; border-radius:8px;
                background:#ef4444; color:white;
                font-size:14px; font-weight:600;
                text-decoration:none; cursor:pointer;
                transition:0.2s; display:inline-block;
                border:2px solid #ef4444;
            " onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='#ef4444'">
                🗑 Supprimer
            </a>
        </div>
    </div>
</div>

<script>
function openDeleteModal(url, message) {
    document.getElementById('deleteModalMsg').textContent = message || 'Êtes-vous sûr de vouloir supprimer cet élément ?';
    document.getElementById('deleteModalBtn').href = url;
    const modal = document.getElementById('deleteModal');
    modal.style.display = 'flex';
}
function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDeleteModal();
});
</script>

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
