</div> <!-- End Container Fluid -->
</div> <!-- End Page Content Wrapper -->
</div> <!-- End Wrapper -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Sidebar Toggle Script -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var el = document.getElementById("wrapper");
        var toggleButtons = document.querySelectorAll(".sidebar-toggle-btn");

        toggleButtons.forEach(function(btn) {
            btn.onclick = function (e) {
                e.preventDefault();
                el.classList.toggle("toggled");
            };
 });
    });
</script>
</body>

</html>