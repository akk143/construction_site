<section id="sidebar" style="background-color: #A7C1E1; color: aliceblue;">
    <a href="#" class="main-title" style="background-color: #84A1C4; color: aliceblue; padding: 1rem 3rem;">Lotus
        Skyline</a>
    <ul class="sidebar-menu">
        <li><a href="index.php" style="background-color: #5F84A2; color: aliceblue;"><i
                    class='bx bxs-dashboard icon'></i> Dashboard</a></li>
        <li>
            <a href="#"><i class='bx bxs-widget icon'></i> Manage Services<i
                    class='bx bx-chevron-right icon-right'></i></a>
            <ul class="sidebar-dropdown">
                <li><a href="serviceCate.php">Service category</a></li>
                <li><a href="serviceSubcate.php">Service Subcategory</a></li>
                <li><a href="service.php">Service</a></li>
            </ul>
        </li>
        <li>
            <a href="#"><i class='bx bxs-widget icon'></i> Manage Projects<i
                    class='bx bx-chevron-right icon-right'></i></a>
            <ul class="sidebar-dropdown">
                <!-- <li><a href="pjType.php">Project Type</a></li> -->
                <li><a href="project.php">Projects</a></li>
            </ul>
        </li>
        <li>
            <a href="#"><i class='bx bxs-widget icon'></i> Manage Property<i
                    class='bx bx-chevron-right icon-right'></i></a>
            <ul class="sidebar-dropdown">
                <li><a href="ptypes.php">Property Types</a></li>
                <li><a href="property.php">Property</a></li>
            </ul>
        </li>
        <li><a href="booking.php"><i class='bx bx-table icon'></i> Manage Service Bookings</a></li>
        <li><a href="psales.php"><i class='bx bx-bar-chart-square icon'></i> Manage Property Sales</a></li>
        <li><a href="payment.php"><i class='bx bx-calculator icon'></i> Payment Transaction</a></li>
        <li><a href="faq.php"><i class='bx bxs-chart icon'></i> Manage FAQs</a></li>
        <li><a href="blog.php"><i class='bx bxs-book icon'></i> Manage Blogs</a></li>
        <li>
            <a href="#"><i class='bx bxs-widget icon'></i>Manage Gallery<i
                    class='bx bx-chevron-right icon-right'></i></a>
            <ul class="sidebar-dropdown">
                <li><a href="ppgallery.php">Property Gallery</a></li>
                <li><a href="projgallery.php">Project Gallery</a></li>
            </ul>
        </li>
        <li><a href="feedback.php"><i class='bx bxs-smile icon'></i> Client Feedback</a></li>
        <li><a href="msg.php"><i class='bx bxs-message-square-dots icon'></i> Contact Messages</a></li>
        <li>
            <a href="#"><i class='bx bxs-notepad icon'></i> Reports <i class='bx bx-chevron-right icon-right'></i></a>
            <ul class="sidebar-dropdown">
                <li><a href="report1.html">Top-rated Projects</a></li>
                <li><a href="#">Service Booking Earnings</a></li>
                <li><a href="#">Property Sales Earnings</a></li>
                <li><a href="#">Most Booked Services</a></li>
            </ul>
        </li>
    </ul>
</section>
<script>
// SIDEBAR DROPDOWN
document.addEventListener("DOMContentLoaded", function() {

    const allDropdown = document.querySelectorAll('#sidebar .sidebar-dropdown');
    const sidebar = document.getElementById('sidebar');

    allDropdown.forEach(item => {
        const a = item.parentElement.querySelector('a:first-child');
        a.addEventListener('click', function(e) {
            e.preventDefault();

            if (!this.classList.contains('active')) {
                allDropdown.forEach(i => {
                    const aLink = i.parentElement.querySelector('a:first-child');

                    aLink.classList.remove('active');
                    i.classList.remove('show');
                })
            }

            this.classList.toggle('active');
            item.classList.toggle('show');
        })
    });

});

// SIDEBAR COLLAPSE
const toggleSidebar = document.querySelector('nav .toggle-sidebar');
const allSideDivider = document.querySelectorAll('#sidebar .divider');

if (sidebar.classList.contains('hide')) {
    allDropdown.forEach(item => {
        const a = item.parentElement.querySelector('a:first-child');
        a.classList.remove('active');
        item.classList.remove('show');
    })
}

toggleSidebar.addEventListener('click', function() {
    sidebar.classList.toggle('hide');

    if (sidebar.classList.contains('hide')) {

        allDropdown.forEach(item => {
            const a = item.parentElement.querySelector('a:first-child');
            a.classList.remove('active');
            item.classList.remove('show');
        })
    }
})

sidebar.addEventListener('mouseleave', function() {
    if (this.classList.contains('hide')) {
        allDropdown.forEach(item => {
            const a = item.parentElement.querySelector('a:first-child');
            a.classList.remove('active');
            item.classList.remove('show');
        })
    }
})

sidebar.addEventListener('mouseenter', function() {
    if (this.classList.contains('hide')) {
        allDropdown.forEach(item => {
            const a = item.parentElement.querySelector('a:first-child');
            a.classList.remove('active');
            item.classList.remove('show');
        })

    }
})
</script>